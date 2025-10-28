<?php

namespace App\Http\Controllers;

use App\Exports\ExpenseExport;
use App\Models\Category;
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
    // 🔹 1. Summary (Expense + Donation)
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

    // 🔹 2. Category-wise Expense + Donation Breakdown
    public function categoryBreakdown()
    {
        $user = Auth::user();

        // Get all categories and donation types names
        $categories = \App\Models\Category::pluck('name')->toArray();
        $donationTypes = \App\Models\DonationType::pluck('name')->toArray();

        // Merge both for result initialization
        $allCategories = array_unique(array_merge($categories, $donationTypes));

        // Initialize result with 0 totals
        $result = collect($allCategories)->mapWithKeys(function ($category) {
            return [$category => [
                'category' => $category,
                'expense_total' => 0,
                'donation_total' => 0,
            ]];
        });

        // Expenses
        $expenseQuery = Expense::select('categories.name as category', DB::raw('SUM(expenses.amount) as total'))
            ->join('categories', 'expenses.category_id', '=', 'categories.id');

        if ($user->role === 'manager') {
            $expenseQuery->where('expenses.user_id', $user->id);
        }

        $expenses = $expenseQuery->groupBy('categories.name')->get();

        foreach ($expenses as $item) {
            if ($result->has($item->category)) {
                $result[$item->category] = array_merge($result[$item->category], [
                    'expense_total' => $item->total,
                ]);
            }
        }

        // Donations
        $donationQuery = Donation::select('donation_types.name as category', DB::raw('SUM(donations.amount) as total'))
            ->join('donation_types', 'donations.donation_type_id', '=', 'donation_types.id');

        if ($user->role === 'manager') {
            $donationQuery->where('donations.user_id', $user->id);
        }

        $donations = $donationQuery->groupBy('donation_types.name')->get();

        foreach ($donations as $item) {
            if ($result->has($item->category)) {
                $result[$item->category] = array_merge($result[$item->category], [
                    'donation_total' => $item->total,
                ]);
            }
        }

        return response()->json(array_values($result->toArray()));
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
        $user = Auth::user();

        // Expenses
        $expenseQuery = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id');

        if ($user->role === 'manager') {
            $expenseQuery->where('user_id', $user->id);
        }

        $expenses = $expenseQuery->get();

        // Donations
        $donationQuery = Donation::select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id');

        if ($user->role === 'manager') {
            $donationQuery->where('user_id', $user->id);
        }

        $donations = $donationQuery->get();

        // Combine totals
        $combined = collect();

        foreach ($expenses as $item) {
            $combined->put($item->category_id, [
                'category_id' => $item->category_id,
                'total_amount' => (float) $item->total,
            ]);
        }

        foreach ($donations as $item) {
            if ($combined->has($item->category_id)) {
                $combined[$item->category_id]['total_amount'] += (float) $item->total;
            } else {
                $combined->put($item->category_id, [
                    'category_id' => $item->category_id,
                    'total_amount' => (float) $item->total,
                ]);
            }
        }

        // Preload category names to avoid repeated DB hits
        $categoryNames = Category::pluck('name', 'id')->toArray();

        $top = $combined->sortByDesc('total_amount')->take(5)->map(function ($item) use ($categoryNames) {
            return [
                'category' => $categoryNames[$item['category_id']] ?? 'Unknown',
                'total_amount' => number_format($item['total_amount'], 2),
            ];
        })->values();

        return response()->json(['top_5_categories' => $top]);

    }

    public function drillDown($categoryId)
    {

        $user = Auth::user();

        // Validate category
        if (! Category::where('id', $categoryId)->exists()) {
            return response()->json(['error' => 'Invalid category ID'], 404);
        }

        // Query expenses
        $query = Expense::with('category')
            ->where('category_id', $categoryId)
            ->orderByDesc('expense_date');

        if ($user->role === 'manager') {
            $query->where('user_id', $user->id);
        }

        $data = $query->get();

        return response()->json([
            'category_id' => $categoryId,
            'expenses' => $data,
            'total_amount' => $data->sum('amount'),
        ]);
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

        $expenseQuery = Expense::select(
            DB::raw("DATE_FORMAT(expense_date, '%Y-%m') as month"),
            DB::raw('SUM(amount) as total')
        );
        if ($user->role === 'manager') {
            $expenseQuery->where('user_id', $user->id);
        }
        $expenses = $expenseQuery->groupBy('month')->orderBy('month')->get();

        $donationQuery = Donation::select(
            DB::raw("DATE_FORMAT(donation_date, '%Y-%m') as month"),
            DB::raw('SUM(amount) as total')
        );
        if ($user->role === 'manager') {
            $donationQuery->where('user_id', $user->id);
        }
        $donations = $donationQuery->groupBy('month')->orderBy('month')->get();

        // Merge expenses + donations by month
        $combined = [];
        foreach ($expenses as $item) {
            $combined[$item->month]['month'] = $item->month;
            $combined[$item->month]['expense_total'] = $item->total;
            $combined[$item->month]['donation_total'] = 0;
        }

        foreach ($donations as $item) {
            if (isset($combined[$item->month])) {
                $combined[$item->month]['donation_total'] = $item->total;
            } else {
                $combined[$item->month]['month'] = $item->month;
                $combined[$item->month]['expense_total'] = 0;
                $combined[$item->month]['donation_total'] = $item->total;
            }
        }

        return response()->json(array_values($combined));
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
           $query = Donation::query();
              if ($user->role === 'manager') {
            $query->where('user_id', $user->id);
        }
           $query->where('donation_type_id', (int) $request->donation_type);

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

        // Optional: validate filters
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        // Optional: dynamic filename
        $filename = 'expense_report_'.now()->format('Y_m_d').'.xlsx';

        return Excel::download(new ExpenseExport($request), $filename);


    }

    public function exportPdf(Request $request)
    {
        // Replace query parameters
        $request->replace($request->query());
        Log::info('Export request received', $request->query());

        $user = Auth::user();

        // Expenses query
        $expenseQuery = Expense::with('category');

        if ($request->filled(['start_date', 'end_date'])) {
            $expenseQuery->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('category_id')) {
            $expenseQuery->where('category_id', $request->category_id);
        }

        if ($user->role === 'manager') {
            $expenseQuery->where('user_id', $user->id);
        }

        $expenses = $expenseQuery->get();

        // Donations query
        $donationQuery = Donation::with('donationType');

        if ($request->filled(['start_date', 'end_date'])) {
            $donationQuery->whereBetween('donation_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('donation_type_id')) {
            $donationQuery->where('donation_type_id', $request->donation_type_id);
        }

        if ($user->role === 'manager') {
            $donationQuery->where('user_id', $user->id);
        }

        $donations = $donationQuery->get();

        // Handle empty results
        if ($expenses->isEmpty()) {
            $expenses = collect([['message' => 'No expense data found']]);
        }

        if ($donations->isEmpty()) {
            $donations = collect([['message' => 'No donation data found']]);
        }

        // Generate PDF
        $pdf = Pdf::loadView('exports.expenses', compact('expenses', 'donations'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('expense_report.pdf');
    }
}


