<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuiveurEventCreateNot extends Notification
{
    use Queueable;

    protected $event;
    protected $orga;

    /**
     * Create a new notification instance.
     */
    public function __construct(Utilisateur $orga, Event $event)
    {
        //
        $this->event = $event;
        $this->orga = $orga;
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
            'titre' => "Evènement - {$this->event->titre}" ,
            'message' => "Votre organisateur {$this->orga} vient de publier un nouvel evenement" ,
            'date' => $this->event->date_debut ,

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
