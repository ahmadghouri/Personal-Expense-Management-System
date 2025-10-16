<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'donation_type_id',
        'recipient_name',
        'donation_date',
        'amount',
        'value',
        'description',
        'proof',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function donationType()
    {
        return $this->belongsTo(DonationType::class);
    }
}
