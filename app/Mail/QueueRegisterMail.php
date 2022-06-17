<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QueueRegisterMail extends Mailable {
	use Queueable, SerializesModels;
	public $details;
	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct($details) {
		$this->details = $details;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->from(env('MAIL_FROM_ADDRESS'))->subject('Oyechef')->markdown('frontend.emails.register-mail')->with('details', $this->details);
		// return $this->view('view.name');
	}
}
