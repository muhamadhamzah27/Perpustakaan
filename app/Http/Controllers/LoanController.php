<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        // Update overdue statuses
        Loan::where('status', 'active')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);

        $query = Loan::with(['user', 'book', 'processedBy']);

        // Members only see their own loans
        if (auth()->user()->isMember()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('book', fn($q) => $q->where('title', 'like', "%{$search}%"));
        }

        $loans = $query->latest()->paginate(15)->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function create(Request $request)
    {
        $users = User::where('role', 'member')->where('status', 'active')->get();
        $books = Book::where('available_copies', '>', 0)->get();
        $selectedBook = $request->filled('book_id') ? Book::find($request->book_id) : null;
        $selectedUser = $request->filled('user_id') ? User::find($request->user_id) : null;

        return view('loans.create', compact('users', 'books', 'selectedBook', 'selectedUser'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'  => 'required|exists:users,id',
            'book_id'  => 'required|exists:books,id',
            'due_date' => 'required|date|after:today',
            'notes'    => 'nullable|string|max:500',
        ]);

        $book = Book::findOrFail($data['book_id']);
        $user = User::findOrFail($data['user_id']);

        if (!$book->isAvailable()) {
            return back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        // Check if user already borrowed this book
        if (Loan::where('user_id', $user->id)->where('book_id', $book->id)->whereIn('status', ['active', 'overdue'])->exists()) {
            return back()->with('error', 'Anggota ini sudah meminjam buku ini.');
        }

        $loan = Loan::create([
            'user_id'      => $data['user_id'],
            'book_id'      => $data['book_id'],
            'processed_by' => auth()->id(),
            'loan_date'    => Carbon::today(),
            'due_date'     => $data['due_date'],
            'status'       => 'active',
            'notes'        => $data['notes'] ?? null,
        ]);

        // Decrement available copies
        $book->decrement('available_copies');

        // Fulfil reservation if exists
        Reservation::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->where('status', 'ready')
            ->update(['status' => 'fulfilled']);

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function show(Loan $loan)
    {
        if (auth()->user()->isMember() && $loan->user_id !== auth()->id()) {
            abort(403);
        }
        $loan->load(['user', 'book.category', 'processedBy']);
        return view('loans.show', compact('loan'));
    }

    public function returnBook(Request $request, Loan $loan)
    {
        if (!in_array($loan->status, ['active', 'overdue'])) {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        $returnDate = Carbon::today();
        $fine = 0;

        if ($returnDate->gt($loan->due_date)) {
            $lateDays = $loan->due_date->diffInDays($returnDate);
            $fine = $lateDays * 1000; // Rp 1.000/hari
        }

        $loan->update([
            'return_date'  => $returnDate,
            'status'       => 'returned',
            'fine_amount'  => $fine,
            'fine_paid'    => $fine == 0,
        ]);

        // Restore available copies
        $loan->book->increment('available_copies');

        // Notify waiting reservations → mark first as "ready"
        $nextReservation = Reservation::where('book_id', $loan->book_id)
            ->where('status', 'waiting')
            ->oldest()
            ->first();

        if ($nextReservation) {
            $nextReservation->update([
                'status'      => 'ready',
                'expiry_date' => Carbon::today()->addDays(3),
            ]);
        }

        $message = $fine > 0
            ? "Buku berhasil dikembalikan. Denda: Rp " . number_format($fine, 0, ',', '.')
            : "Buku berhasil dikembalikan tepat waktu.";

        return redirect()->route('loans.show', $loan)->with('success', $message);
    }

    public function payFine(Loan $loan)
    {
        if ($loan->fine_amount <= 0 || $loan->fine_paid) {
            return back()->with('error', 'Tidak ada denda yang harus dibayar.');
        }

        $loan->update(['fine_paid' => true]);

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}
