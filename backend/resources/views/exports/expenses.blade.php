<style>
    .expense-table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        margin-top: 20px;
    }

    .expense-table th, .expense-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .expense-table th {
        background-color: #f4f4f4;
        color: #333;
        font-weight: bold;
    }

    .expense-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .expense-table tr:hover {
        background-color: #e6f7ff;
    }

    .no-data {
        text-align: center;
        font-style: italic;
        color: #888;
        padding: 20px;
    }
</style>

@if($expenses->isNotEmpty() && isset($expenses[0]['message']) === false)
    <table class="expense-table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Amount</th>
                <th>Payment Mode</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->category->name ?? 'N/A' }}</td>
                    <td>{{ $expense->subcategory }}</td>
                    <td>{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ ucfirst($expense->payment_mode) }}</td>
                    <td>{{ $expense->expense_date }}</td>
                    <td>{{ $expense->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="no-data">No expense data found.</div>
@endif
