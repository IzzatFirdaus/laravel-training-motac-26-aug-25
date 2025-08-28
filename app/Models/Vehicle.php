<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * Casts for numeric columns.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'qty' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Owner relationship: a vehicle belongs to a user (optional)
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Inventories attached to this vehicle (many-to-many).
     * Requires a pivot table `inventory_vehicle` with inventory_id and vehicle_id.
     *
     * @return BelongsToMany<\App\Models\Inventory>
     */
    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(
            Inventory::class,
            'inventory_vehicle',
            'vehicle_id',
            'inventory_id'
        )->withTimestamps();
    }

    /**
     * Local scope: filter by search term across name and description.
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $q = trim((string) $term);
        if ($q === '') {
            return $query;
        }

        $like = '%'.strtolower($q).'%';

        return $query->where(function (Builder $qbuilder) use ($like): void {
            $qbuilder->whereRaw('LOWER(name) LIKE ?', [$like])
                ->orWhereRaw('LOWER(COALESCE(description, \'\')) LIKE ?', [$like]);
        });
    }

    /**
     * Local scope: filter vehicles owned by a specific user id.
     */
    public function scopeOwnedBy(Builder $query, int|string|null $userId): Builder
    {
        if ($userId === null || $userId === '') {
            return $query;
        }

        return $query->where('user_id', (int) $userId);
    }
}
