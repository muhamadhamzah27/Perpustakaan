<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'member');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->latest()->paginate(15)->withQueryString();

        return view('members.index', compact('members'));
    }

    public function show(User $member)
    {
        $member->load(['loans.book', 'reservations.book']);
        return view('members.show', compact('member'));
    }

    public function toggleStatus(User $member)
    {
        $member->update([
            'status' => $member->status === 'active' ? 'inactive' : 'active',
        ]);

        $status = $member->fresh()->status;
        return back()->with('success', "Status anggota berhasil diubah menjadi {$status}.");
    }

    // Profile for logged-in member
    public function profile()
    {
        $user = auth()->user();
        return view('members.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update(['password' => $request->password]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    // Member card view
    public function memberCard()
    {
        $user = auth()->user();
        return view('members.card', compact('user'));
    }
}
