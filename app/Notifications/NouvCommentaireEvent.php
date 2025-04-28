<?php

namespace App\Notifications;

use App\Models\Commentaire;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvCommentaireEvent extends Notification
{
    use Queueable;

    protected $commentaire;


    /**
     * Create a new notification instance.
     */
    public function __construct(Commentaire $commentaire)
    {
        $this->commentaire = $commentaire;
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
    public function toDatabase($notifiable)
    {
        return [
            'message' => "Votre événement '{$this->commentaire->event->titre}' a reçu un nouveau commentaire.",
            'event_id' => $this->commentaire->event->id,
            'commentaire_id' => $this->commentaire->id,
            'utilisateur' => $this->commentaire->utilisateur->nom ?? 'Utilisateur inconnu',
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
