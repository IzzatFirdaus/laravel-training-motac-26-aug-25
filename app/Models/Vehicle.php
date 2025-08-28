<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    /**
     * Inventories attached to this vehicle (many-to-many).
     * Requires a pivot table `inventory_vehicle` with inventory_id and vehicle_id.
     *
     * @return BelongsToMany<\App\Models\Inventory>
     */
    public function inventories()
    {
        return $this->belongsToMany(
            Inventory::class,
            'inventory_vehicle',
            'vehicle_id',
            'inventory_id'
        )->withTimestamps();
    }

    // No placeholder one-to-many relations defined.
}
