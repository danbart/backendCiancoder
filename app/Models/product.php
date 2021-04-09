<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    public function seller()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function sale_detail()
    {
        return $this->hasMany(Sale_detail::class);
    }
}
