<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr style="background-color: #f2f2f2;">
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
