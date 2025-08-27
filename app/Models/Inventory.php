<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id'; // Explicitly define the primary key

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'qty',
        'price',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @return array<string,string>
     */
    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Inventory belongs to a user (owner).
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
