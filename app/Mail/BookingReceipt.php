<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class BookingReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $pdfContent;

    public function __construct(Booking $booking, $pdfContent = null)
    {
        $this->booking = $booking;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        $mail = $this->subject('Your booking receipt')
                    ->view('emails.booking_receipt')
                    ->with(['booking' => $this->booking]);

        if ($this->pdfContent) {
            $mail->attachData($this->pdfContent, 'receipt_'.$this->booking->id.'.pdf', [
                'mime' => 'application/pdf'
            ]);
        }

        return $mail;
    }
}
