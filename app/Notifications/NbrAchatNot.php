<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NbrAchatNot extends Notification
{
    use Queueable;

    public $event;
    public $quantite;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event, $quantite)
    {
        //
        $this->event = $event;
        $this->quantite = $quantite;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable)
    {
        return [
            'title' => 'Nouvel achat de votre ticket',
            'message' => "Ton événement « {$this->event->nom} » a atteint {$this->quantite} achat(s).",
            'event_id' => $this->event->id,
            'quantite' => $this->quantite,
        ];

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
