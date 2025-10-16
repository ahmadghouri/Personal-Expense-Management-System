<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory',
        'expense_date',
        'amount',
        'payment_mode',
        'description',
        'attachment',
    ];

    public function user()
    {
        return $this->belongsTo((User::class));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
