<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sale';

    public function seller()
    {
        return $this->belongsTo(User::class, 'id_seller');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'id_buyer');
    }

    public function sale_detail()
    {
        return $this->hasMany(Sale_detail::class);
    }
}
