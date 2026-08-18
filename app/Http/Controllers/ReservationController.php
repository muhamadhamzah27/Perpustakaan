<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'book']);

        if (auth()->user()->isMember()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->paginate(15)->withQueryString();

        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::findOrFail($data['book_id']);
        $userId = auth()->id();

        // Check already has active reservation
        if (Reservation::where('user_id', $userId)->where('book_id', $book->id)->whereIn('status', ['waiting', 'ready'])->exists()) {
            return back()->with('error', 'Anda sudah memiliki reservasi untuk buku ini.');
        }

        // Check already borrowed
        if (\App\Models\Loan::where('user_id', $userId)->where('book_id', $book->id)->whereIn('status', ['active', 'overdue'])->exists()) {
            return back()->with('error', 'Anda sedang meminjam buku ini.');
        }

        Reservation::create([
            'user_id' => $userId,
            'book_id' => $data['book_id'],
            'status'  => 'waiting',
        ]);

        return back()->with('success', 'Reservasi berhasil. Anda akan diberitahu saat buku tersedia.');
    }

    public function cancel(Reservation $reservation)
    {
        if (auth()->user()->isMember() && $reservation->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($reservation->status, ['waiting', 'ready'])) {
            return back()->with('error', 'Reservasi ini tidak dapat dibatalkan.');
        }

        // If was "ready", increment back available_copies
        if ($reservation->status === 'ready') {
            $reservation->book->increment('available_copies');
        }

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
