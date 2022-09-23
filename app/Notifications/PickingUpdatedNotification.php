<?php

namespace App\Notifications;

use App\Models\Picking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class PickingUpdatedNotification extends Notification
{
    use Queueable;

    private $picking;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Picking $picking)
    {
        $this->picking = $picking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = [
            'codigo' => $this->picking->codigo,
            'fecha_clave' => $this->picking->fecha_clave,
            'sucursalNombre' =>$this-> picking->branchOffice->nombre,
            'sucursalDomicilio' => $this->picking->branchOffice->domicilio,
            'clave' => $this->picking->clave,
            'sucursalColonia' => $this->picking->branchOffice->colonia,
            'sucursalCiudad' => $this->picking->branchOffice->ciudad,
            'sucursalEstado' => $this->picking->branchOffice->estado
        ];
        Mail::send('mail.template',$data, function ($message) {
			$message->to('motorocool@gmail.com');
			$message->subject('Dagpacket Notificación');
 		});

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
            //
        ];
    }
}
