<?php

namespace App\Exports;

use App\Models\Inventory;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromArray, ShouldAutoSize, WithHeadings
{
    /** @var array<int, string> */
    protected array $columns;

    /** @var array<int, array<string, mixed>> */
    protected array $rows;

    /**
     * @param  array<int, string>  $columns
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function __construct(array $columns = [], array $rows = [])
    {
        $this->columns = $columns ?: Schema::getColumnListing((new Inventory)->getTable());

        // Exclude non-editable columns typically managed by DB
        $this->columns = array_values(array_filter($this->columns, function ($c) {
            return ! in_array($c, ['id', 'created_at', 'updated_at', 'deleted_at'], true);
        }));

        $this->rows = $rows;
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return $this->columns;
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    public function array(): array
    {
        if (! empty($this->rows)) {
            // Map associative rows to column order
            return array_map(function ($row) {
                return array_map(fn ($col) => $row[$col] ?? null, $this->columns);
            }, $this->rows);
        }

        // Provide empty template rows for user convenience (2 rows)
        return [
            array_fill(0, count($this->columns), null),
            array_fill(0, count($this->columns), null),
        ];
    }
}
