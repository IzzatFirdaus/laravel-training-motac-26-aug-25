<?php

namespace App\Http\Controllers;

use App\Exports\InventoryExport;
use App\Imports\InventoryImport;
use App\Models\Inventory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Download an Excel template for Inventory. */
    public function exportInventory(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->authorize('viewAny', Inventory::class);

        $columns = array_values(array_filter(
            Schema::getColumnListing((new Inventory)->getTable()),
            fn ($c) => ! in_array($c, ['id', 'created_at', 'updated_at', 'deleted_at'], true)
        ));

        return Excel::download(new InventoryExport($columns), 'inventories-template.xlsx');
    }

    /** Show upload form and optionally preview parsed rows. */
    public function importInventoryForm(): View
    {
        $this->authorize('viewAny', Inventory::class);

        return view('excel.inventory-import');
    }

    /** Parse uploaded file and show preview of rows and validation failures; no DB writes. */
    public function previewInventory(Request $request): View
    {
        $this->authorize('viewAny', Inventory::class);

        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv,txt'],
        ]);

        $import = new InventoryImport(null, previewOnly: true);
        $import->import($data['file']);

        return view('excel.inventory-import', [
            'previewRows' => $import->getPreviewRows(),
            'failures' => $import->failures(),
        ]);
    }

    /** Perform import: validate and insert rows into DB. */
    public function importInventory(Request $request): RedirectResponse
    {
        $this->authorize('create', Inventory::class);

        $data = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv,txt'],
        ]);

        $import = new InventoryImport;
        $import->import($data['file']);

        if ($import->failures()->isNotEmpty()) {
            return back()->withErrors(['file' => 'Sebahagian baris gagal diimport. Sila semak ralat.'])->with('import_failures', $import->failures());
        }

        return redirect()->route('inventories.index')->with('toast', 'Import inventori berjaya.');
    }
}
