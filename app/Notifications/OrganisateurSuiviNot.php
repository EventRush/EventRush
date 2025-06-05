<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrganisateurSuiviNot extends Notification
{
    use Queueable;

    protected $utilisateur;

    /**
     * Create a new notification instance.
     */
    public function __construct($utilisateur)
    {
        //
        $this->utilisateur = $utilisateur;

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
            'message' => $this->utilisateur->nom . ' a commencé à vous suivre.',
            'role' => $this->utilisateur->role,
            'link' => route('users.show', $this->utilisateur->id),

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
