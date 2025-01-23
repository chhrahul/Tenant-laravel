<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataEntry extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'data_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'building_name',
        'address',
        'tower',
        'tenant_name',
        'suit',
        'rent',
        'square_feet',
        'percentage_of_total',
        'lease_expiration',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'lease_expiration' => 'date',
        'percentage_of_total' => 'decimal:2',
    ];
}
