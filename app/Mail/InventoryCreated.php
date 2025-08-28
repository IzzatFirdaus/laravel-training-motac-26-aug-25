<?php

namespace App\Mail;

use App\Models\Inventory;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InventoryCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Inventory $inventory;

    /**
     * Create a new message instance.
     */
    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
    return $this->subject('New Inventory Created')
            ->view('emails.inventory_created')
            ->text('emails.inventory_created_plain')
            ->with(['inventory' => $this->inventory]);
    }
}
