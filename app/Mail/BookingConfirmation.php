<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Components\ReservationsComponent;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($res)
    {
        $this->res = $res;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = ReservationsComponent::getConfirmationPdf($this->res,false);
        return $this->view('emails.booking-confirmation')
        ->to($this->res->clientEmail)
        ->cc(\SettingsComponent::get('emailNotifications'))
        ->bcc(['webmaster@neo-emarketing.com','mauro.perez@goguytravel.com'])
        ->attachData($pdf,'Confirmation.pdf',['mime'=>'application/pdf']);
    }
}
