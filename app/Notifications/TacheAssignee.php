<?php

namespace App\Notifications;

use App\Models\Tache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TacheAssignee extends Notification implements ShouldQueue
{
    use Queueable;

    protected $tache;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Tache $tache)
    {
        $this->tache = $tache;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route('taches.show', $this->tache);

        return (new MailMessage)
                    ->subject('Nouvelle tâche assignée : ' . $this->tache->titre)
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('Une nouvelle tâche vous a été assignée dans le projet "' . $this->tache->projet->titre . '".')
                    ->line('Titre de la tâche : ' . $this->tache->titre)
                    ->line('Date d\'échéance : ' . date('d/m/Y', strtotime($this->tache->date_echeance)))
                    ->action('Voir la tâche', $url)
                    ->line('Merci d\'utiliser notre application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'tache_id' => $this->tache->id,
            'tache_titre' => $this->tache->titre,
            'projet_id' => $this->tache->projet->id,
            'projet_titre' => $this->tache->projet->titre,
            'date_echeance' => $this->tache->date_echeance
        ];
    }
}