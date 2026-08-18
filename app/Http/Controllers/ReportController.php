<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->integer('month', now()->month);
        $year  = $request->integer('year', now()->year);

        $loans = Loan::with(['user', 'book'])
            ->whereYear('loan_date', $year)
            ->whereMonth('loan_date', $month)
            ->get();

        $totalLoans    = $loans->count();
        $returned      = $loans->where('status', 'returned')->count();
        $overdue       = $loans->where('status', 'overdue')->count();
        $totalFines    = $loans->sum('fine_amount');
        $paidFines     = $loans->where('fine_paid', true)->sum('fine_amount');

        // Top 10 most borrowed books this month
        $topBooks = Book::withCount(['loans' => function ($q) use ($year, $month) {
            $q->whereYear('loan_date', $year)->whereMonth('loan_date', $month);
        }])->orderByDesc('loans_count')->limit(10)->get();

        // Active members this month
        $activeMembers = User::whereHas('loans', function ($q) use ($year, $month) {
            $q->whereYear('loan_date', $year)->whereMonth('loan_date', $month);
        })->count();

        $years  = range(2020, now()->year);
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('reports.index', compact(
            'loans', 'totalLoans', 'returned', 'overdue', 'totalFines', 'paidFines',
            'topBooks', 'activeMembers', 'month', 'year', 'years', 'months'
        ));
    }
}
