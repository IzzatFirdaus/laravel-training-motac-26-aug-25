<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Vehicle extends Model
{
    // Allow mass assignment for these fields used by the create form.
    protected $fillable = [
        'name',
        'user_id',
        'qty',
        'price',
        'description',
    ];

    /**
     * Owner relationship: a vehicle belongs to a user (optional)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
