<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Warehouse;
use App\Models\Shelf;

class WarehousesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure the JSON endpoints are available for the test environment
    \Illuminate\Support\Facades\Route::model('warehouse', \App\Models\Warehouse::class);
    \Illuminate\Support\Facades\Route::get('/warehouses', [\App\Http\Controllers\InventoryController::class, 'warehouses']);
    \Illuminate\Support\Facades\Route::get('/warehouses/{warehouse}/shelves', [\App\Http\Controllers\InventoryController::class, 'shelvesByWarehouse']);
    }

    public function test_warehouses_index_returns_json()
    {
        Warehouse::factory()->create(['name' => 'Alpha']);
        Warehouse::factory()->create(['name' => 'Beta']);

        $res = $this->getJson('/warehouses');

        $res->assertStatus(200)->assertJsonStructure([
            ['id', 'name']
        ]);
    }

    public function test_warehouse_shelves_endpoint_returns_shelves()
    {
        $w = Warehouse::factory()->create(['name' => 'Gamma']);
    $w->shelves()->create(['shelf_number' => 'G-01']);
    $w->shelves()->create(['shelf_number' => 'G-02']);

    // Ensure shelves were created correctly in the DB
    $this->assertDatabaseCount('shelves', 2);

    $this->withoutExceptionHandling();
    $res = $this->getJson('/warehouses/' . $w->id . '/shelves');

    $res->assertStatus(200)->assertJsonCount(2)->assertJsonFragment(['shelf_number' => 'G-01']);
    }
}
