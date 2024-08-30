<?php

namespace App\Models;


use App\Http\Controllers\CategoriesController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'price'
    ];

    public function Categories()
    {
        return $this->belongsTo(CategoriesController::class);
    }
}
