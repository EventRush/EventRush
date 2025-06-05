<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventFavoriNot extends Notification
{
    use Queueable;

    public $event;
    protected $user; // user qui a favorisé l'évènement

    /**
     * Create a new notification instance.
     */
    public function __construct(Utilisateur $user, Event $event)
    {
        //
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // stocké dan la bd
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable)
    {
        return [
            'message' => "Un événement que vous avez publié a été ajouté en favori : {$this->event->titre}",
            'utilisateur' => $this->user->nom,
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
