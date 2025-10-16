<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseExport;
use App\Models\Donation;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportingController extends Controller
{
    public function summary()
    {
        $user = Auth::user();

        $queryExpense = Expense::query();
        $queryDonation = Donation::query();

        if ($user->role === 'manager') {
            $queryExpense->where('user_id', $user->id);
            $queryDonation->where('user_id', $user->id);
        }

        $totalExpense = $queryExpense->sum('amount');
        $totalDonation = $queryDonation->sum('amount');

        return response()->json([
            'total_expense' => $totalExpense,
            'total_donation' => $totalDonation,
            'overall_total' => $totalExpense + $totalDonation,
        ]);
    }

    public function categoryBreakdown()
    {
        $user = Auth::user();

        $query = Expense::select(
            'categories.name as category',
            DB::raw('SUM(expenses.amount) as total')
        )
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->groupBy('categories.name');

        if ($user->role === 'manager') {
            $query->where('expenses.user_id', $user->id);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function summarizedByCategory()
    {
        $user = Auth::user();

        $query = Expense::select(
            'categories.name as category',
            DB::raw('SUM(expenses.amount) as total'),
            DB::raw('COUNT(expenses.id) as entries')
        )
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->orderByDesc('total');

        if ($user->role === 'manager') {
            $query->where('expenses.user_id', $user->id);
        }

        $data = $query->get();

        return response()->json([
            'summary' => $data,
            'grand_total' => $data->sum('total'),
        ]);
    }

    public function topCategories()
    {
        $top = Expense::with('category')
            ->select('category_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category->name ?? 'Unknown',
                    'total_amount' => $item->total_amount,
                ];
            });

        return response()->json(['top_5_categories' => $top]);
    }

    public function drillDown($categoryId)
    {
        $user = Auth::user();

        $query = Expense::with('category')
            ->where('category_id', $categoryId)
            ->orderByDesc('expense_date');

        if ($user->role === 'manager') {
            $query->where('user_id', $user->id);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function donationBreakdown()
    {
        $user = Auth::user();

        $query = Donation::select(
            'donation_types.name as donation_type',
            DB::raw('SUM(donations.amount) as total')
        )
            ->join('donation_types', 'donations.donation_type_id', '=', 'donation_types.id')
            ->groupBy('donation_types.name');

        if ($user->role === 'manager') {
            $query->where('donations.user_id', $user->id);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function monthlyTrends()
    {
        $user = Auth::user();

        $query = Expense::select(
            DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as month"),
            DB::raw('SUM(amount) as total')
        )
            ->groupBy('month')
            ->orderBy('month');

        if ($user->role === 'manager') {
            $query->where('user_id', $user->id);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function expensePieChart(Request $request)
    {
        $user = Auth::user();

        $query = Expense::select(
            'categories.name as category',
            DB::raw('SUM(expenses.amount) as total')
        )
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->groupBy('categories.name');

        if ($user->role === 'manager') {
            $query->where('expenses.user_id', $user->id);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('expenses.expense_date', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();

        $total = $data->sum('total');
        $pieData = $data->map(function ($item) use ($total) {
            $item->percentage = $total > 0 ? round(($item->total / $total) * 100, 2) : 0;

            return $item;
        });

        return response()->json($pieData);
    }

    public function filter(Request $request)
    {

        $request->replace($request->query());

        $user = Auth::user();
        $query = Expense::query();

        if ($user->role === 'manager') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('category_id')) {
            $categoryId = intval($request->category_id);
            $query->where('category_id', $categoryId);
        }

        if ($request->filled('payment_mode')) {
            $query->where('payment_mode', $request->payment_mode);
        }

        if ($request->filled('donation_type')) {
            $query->where('subcategory', $request->donation_type);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        $data = $query->get();

        $total = $data->sum('amount');
        $topCategories = $data
            ->groupBy(fn ($item) => $item->category->name ?? 'Unknown')
            ->map(fn ($group) => $group->sum('amount'))
            ->sortDesc()
            ->take(5);

        return response()->json([
            'filters' => [
                'category_id' => $request->category_id,
                'payment_mode' => $request->payment_mode,
                'donation_type' => $request->donation_type,
                'date_range' => [$request->start_date, $request->end_date],
            ],
            'total' => $total,
            'top_categories' => $topCategories,
            'data' => $data,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $request->replace($request->query());

        return Excel::download(new ExpenseExport($request), 'expense_report.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $request->replace($request->query());
        Log::info('Export request received', $request->query());
        $query = Expense::with('category');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $expenses = $query->get();

        $pdf = Pdf::loadView('exports.expenses', compact('expenses'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('expense_report.pdf');
    }
}
