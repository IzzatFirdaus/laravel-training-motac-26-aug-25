<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Inventory extends Model implements AuditableContract
{
    use Auditable;
    use HasFactory;
    use SoftDeletes;

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

    /**
     * Inventories may be associated with many vehicles (many-to-many).
     * Requires pivot table `inventory_vehicle` with `inventory_id` and `vehicle_id`.
     *
     * @return BelongsToMany<\App\Models\Vehicle>
     */
    public function vehicles()
    {
        return $this->belongsToMany(
            Vehicle::class,
            'inventory_vehicle',
            'inventory_id',
            'vehicle_id'
        )->withTimestamps();
    }

    // getter make sure title is always uppercase
    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
}
