<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Components\SettingsComponent;

class ContactForm extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public $name;
    // public $email;
    // public $phone;
    // public $message;
    public function __construct($name,$email,$phone,$message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->msg = $message;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact-form')
        ->to(SettingsComponent::get('emailNotifications'))
        // ->to('mauro.perez@goguytravel.com')
        // ->to('uicab2593@gmail.com')
        ->bcc('webmaster@neo-emarketing.com')
        ->with([
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'msg'=>$this->msg,
        ]);
    }
}
