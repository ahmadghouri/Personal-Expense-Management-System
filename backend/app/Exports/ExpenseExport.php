<?php

namespace App\Exports;

use App\Models\Expense;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromView;

class ExpenseExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        Log::info('Export request received', $request->request());
        $this->request = $request;
    }

    public function view(): View
    {
        $user = Auth::user();
        $query = Expense::with('category');


        if ($user->role === 'manager') {
            $query->where('user_id', $user->id);
        }


        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('expense_date', [
                $this->request->start_date,
                $this->request->end_date,
            ]);
        }


        if ($this->request->filled('category_id')) {
            $query->where('category_id', $this->request->category_id);
        }


        if ($this->request->filled('caz')) {
            $query->where('caz', 'like', '%'.$this->request->caz.'%');
        }

        $expenses = $query->get();

        return view('exports.expenses', [
            'expenses' => $expenses,
        ]);
    }
}
