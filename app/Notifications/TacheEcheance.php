<?php

namespace App\Notifications;

use App\Models\Tache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TacheEcheance extends Notification implements ShouldQueue
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
                    ->subject('Rappel : Échéance de tâche approche - ' . $this->tache->titre)
                    ->greeting('Bonjour ' . $notifiable->name . ',')
                    ->line('La date d\'échéance de votre tâche "' . $this->tache->titre . '" approche.')
                    ->line('Projet : ' . $this->tache->projet->titre)
                    ->line('Date d\'échéance : ' . date('d/m/Y', strtotime($this->tache->date_echeance)))
                    ->action('Voir la tâche', $url)
                    ->line('N\'oubliez pas de la terminer à temps!')
                    ->line('Merci d\'utiliser notre application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
}