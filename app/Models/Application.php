<?php

namespace App\Models;

/**
 * Application model properties for static analysis / IDEs.
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int|null $inventory_id
 * @property int|null $user_id
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory;
use App\Models\User;

class Application extends Model
{
    /** @use HasFactory<\Database\Factories\ApplicationFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
    'description',
    'inventory_id',
    'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        // no special casts yet
    ];

    /**
     * Optional relation to an inventory item associated with this application.
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    /**
     * Optional owner (user) of the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
