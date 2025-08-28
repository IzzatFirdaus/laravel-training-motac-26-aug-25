<?php

namespace App\Imports;

use App\Models\Inventory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;

class InventoryImport implements SkipsOnFailure, ToModel, WithHeadingRow, WithValidation
{
    use Importable, SkipsFailures;

    /** @var array<int, string> */
    protected array $columns;

    /** When true, models are not saved; parsed rows are collected for preview. */
    protected bool $previewOnly = false;

    /** @var array<int, array<string, mixed>> */
    protected array $previewRows = [];

    public function __construct(?array $columns = null, bool $previewOnly = false)
    {
        $this->columns = $columns ?: array_values(array_filter(
            Schema::getColumnListing((new Inventory)->getTable()),
            fn ($c) => ! in_array($c, ['id', 'created_at', 'updated_at', 'deleted_at'], true)
        ));
        $this->previewOnly = $previewOnly;
    }

    /**
     * Build rules heuristically for import; can be customized per-project.
     *
     * @return array<string, string|array<int, string>>
     */
    public function rules(): array
    {
        // Base rules: name (required), qty (integer), price (numeric), description (nullable), user_id (exists)
        // Adjust based on available columns
        $map = [
            'name' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
        ];

        $rules = [];
        foreach ($this->columns as $c) {
            if (isset($map[$c])) {
                $rules[$c] = $map[$c];
            } else {
                // default: nullable scalar
                $rules[$c] = 'nullable';
            }
        }

        return $rules;
    }

    /**
     * Map a row to a model. When previewOnly=true, collect data and return null.
     */
    public function model(array $row)
    {
        // Ensure only known columns are used
        $payload = Arr::only($row, $this->columns);

        // For non-admins, force ownership to current user
        if (! Auth::user()?->hasRole('admin')) {
            $payload['user_id'] = Auth::id();
        }

        if ($this->previewOnly) {
            $this->previewRows[] = $payload;

            return null;
        }

        return new Inventory($payload);
    }

    /**
     * Return the parsed rows if preview mode was used.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getPreviewRows(): array
    {
        return $this->previewRows;
    }
}
