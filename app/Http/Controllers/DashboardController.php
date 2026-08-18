<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->memberDashboard();
    }

    private function adminDashboard()
    {
        $totalBooks       = Book::sum('total_copies');
        $totalTitles      = Book::count();
        $totalMembers     = User::where('role', 'member')->where('status', 'active')->count();
        $totalActiveLoans = Loan::whereIn('status', ['active', 'overdue'])->count();
        $totalOverdue     = Loan::where('status', 'overdue')->count();
        $totalReservations = Reservation::where('status', 'waiting')->count();

        // Update overdue status
        Loan::where('status', 'active')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);

        // Monthly loans chart data (last 6 months)
        $monthlyLoans = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $month->translatedFormat('M Y');
            $monthlyLoans[]  = Loan::whereYear('loan_date', $month->year)
                                   ->whereMonth('loan_date', $month->month)
                                   ->count();
        }

        // Top 5 most borrowed books
        $topBooks = Book::withCount(['loans'])
            ->orderByDesc('loans_count')
            ->limit(5)
            ->get();

        // Recent loans
        $recentLoans = Loan::with(['user', 'book'])
            ->latest()
            ->limit(8)
            ->get();

        // Overdue loans that need attention
        $overdueLoans = Loan::with(['user', 'book'])
            ->where('status', 'overdue')
            ->latest('due_date')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalBooks', 'totalTitles', 'totalMembers', 'totalActiveLoans',
            'totalOverdue', 'totalReservations', 'monthlyLoans', 'monthlyLabels',
            'topBooks', 'recentLoans', 'overdueLoans'
        ));
    }

    private function memberDashboard()
    {
        $user = auth()->user();

        // Update overdue status for this user
        Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'overdue']);

        $activeLoans   = Loan::where('user_id', $user->id)
                             ->whereIn('status', ['active', 'overdue'])
                             ->with('book')
                             ->get();
        $totalBorrowed = Loan::where('user_id', $user->id)->count();
        $totalReservations = Reservation::where('user_id', $user->id)
                                        ->whereIn('status', ['waiting', 'ready'])
                                        ->count();
        $totalFines    = Loan::where('user_id', $user->id)
                             ->where('fine_paid', false)
                             ->where('fine_amount', '>', 0)
                             ->sum('fine_amount');

        // Recently added books
        $newBooks = Book::with('category')->latest()->limit(6)->get();

        return view('dashboard.member', compact(
            'activeLoans', 'totalBorrowed', 'totalReservations',
            'totalFines', 'newBooks'
        ));
    }
}
