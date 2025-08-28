<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Inventory;

class StoreInventoryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private Inventory $inventory;

    /**
     * Create a new notification instance.
     */
    public function __construct(Inventory $inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $inv = $this->inventory;
        return (new MailMessage)
            ->subject('Inventori dicipta: ' . ($inv->name ?? '—'))
            ->greeting('Makluman')
            ->line('Inventori "' . ($inv->name ?? '—') . '" telah dicipta.')
            ->line('ID: ' . ($inv->getKey() ?? '—'))
            ->action('Lihat inventori', route('inventories.show', $inv->getKey()))
            ->line('Tarikh: ' . now()->format('d/m/Y H:i'))
            ->line('Terima kasih.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Inventory created.',
            'inventory_id' => $this->inventory->getKey(),
            'inventory_name' => $this->inventory->name ?? null,
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
