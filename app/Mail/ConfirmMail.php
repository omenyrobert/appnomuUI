<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\AuthenticationController as Auth;
use App\Models\User;

class ConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public $code;
    public $fullname;
    public function __construct($emailx,$codex)
    {
        $this->email = $emailx;
        $this->code = $codex;
        $user = User::where('email',$this->email)->first();
        $this->fullname = $user->name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.verify2');
        
    }
}
