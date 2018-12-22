<?php

/**
 *  @package        laravel-api.app.Notifications
 *
 *  @author         Daniel Rodríguez | idepixel (idepixel@gmail.com).
 *  @copyright      idepixel (c) 2018 - Todos los derechos reservados.
 *
 *  @since          Versión 1.0, revisión 22/12/2018.
 *  @version        1.0
 *
 *  @final
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailActivate extends Notification implements ShouldQueue {

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via( $notifiable ) {

        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail( $notifiable ) {

        $url = url( '/api/user/activate/' . $notifiable->email_token );

        return ( new MailMessage )
                ->subject('Verifica tu cuenta.')
                ->line('¡Gracias por registrarte! Antes de continuar, debes verificar tu cuenta de correo.')
                ->action('¡Confirmar!', $url )
                ->line('Gracias por usar LiquidStock.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray( $notifiable ) {

        return [ ];
    }
}
