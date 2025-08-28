<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The user's owned inventories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Inventory>
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get the vehicles owned by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Vehicle>
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Example one-to-one relationship: primary vehicle (if the app designates one).
     * This demonstrates 1-1; adjust column/state if you store a primary_vehicle_id.
     *
     * @return HasOne<\App\Models\Vehicle>
     */
    public function primaryVehicle()
    {
        return $this->hasOne(Vehicle::class, 'user_id');
    }

    /**
     * Many-to-many example between users and vehicles if needed (e.g., shared vehicles).
     * Requires a pivot table 'user_vehicle'. This is optional and illustrative.
     */
    public function sharedVehicles()
    {
        return $this->belongsToMany(Vehicle::class, 'user_vehicle', 'user_id', 'vehicle_id')->withTimestamps();
    }
}
