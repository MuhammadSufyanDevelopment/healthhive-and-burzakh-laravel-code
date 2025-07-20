<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_image',
        'product_name',
        'product_details',
        'category',
        'price',
        'discounted_price',
        'vendor_id',
        'created_at',
        'updated_at' 
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'product_image' => 'string',
        'product_name' => 'string',
        'product_details' => 'string',
        'category' => 'string',
        'price' => 'float',
        'discounted_price' => 'float',
        'vendor_id' => 'integer',
    ];

}
