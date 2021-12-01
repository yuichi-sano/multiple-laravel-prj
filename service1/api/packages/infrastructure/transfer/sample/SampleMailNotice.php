<?php

namespace packages\Infrastructure\Transfer\Sample;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use packages\Domain\Basic\Mail\Header;
use packages\Domain\Basic\Mail\Message;

class SampleMailNotice extends Mailable
{
    use Queueable, SerializesModels;
    public Header $header;
    public Message $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Header $header,Message $message)
    {
        $this->header = $header;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): SampleMailNotice
    {
        return $this->view('emails.contact')
            ->subject($this->message->getSubject())
            ->from($this->header->getFrom()->address, $this->header->getFrom()->personal)
            ->to($this->header->getTo()->toArray())
            ->with('data', $this->message->getContent()->toString());
    }
}
