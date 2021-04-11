<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sale';

    protected $fillable = [
        'id_user'
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function sale_detail()
    {
        return $this->hasMany(Sale_detail::class);
    }
}
