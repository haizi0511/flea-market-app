<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'payment_methods_id',
        'postal_code',
        'address',
        'building',
        'user_id',
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(Payment_method::class);
    }
}
