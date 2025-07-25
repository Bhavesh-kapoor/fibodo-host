<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTypeFactory> */
    use HasFactory, HasFactory, HasUlids;


    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['title'];
}
