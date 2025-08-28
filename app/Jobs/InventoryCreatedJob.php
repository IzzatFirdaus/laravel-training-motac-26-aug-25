<?php

namespace App\Jobs;

use App\Mail\InventoryCreated as InventoryCreatedMailable;
use App\Models\Inventory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class InventoryCreatedJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * For static analysis: the inventory instance serialized into the job.
     */
    public Inventory $inventory;

    /**
     * Create a new job instance.
     */
    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Ensure we have a fresh inventory instance with relations.
        $inventory = Inventory::with('user')->find($this->inventory->getKey() ?? $this->inventory->id ?? null);

        if (! $inventory) {
            // The inventory was removed before the job ran — nothing to do.
            return;
        }

        $recipient = $inventory->user?->email ?? config('mail.from.address', 'user@example.com');

        // Use the mailable and queue it so delivery is handled by the mail queue worker.
        Mail::to($recipient)->queue(new InventoryCreatedMailable($inventory));
    }
}
