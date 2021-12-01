<?php

namespace packages\Infrastructure\Transfer;

use Illuminate\Mail\Mailable;
use packages\Domain\Basic\Mail\Address;
use packages\Domain\Basic\Mail\AddressList;
use packages\Domain\Basic\Mail\Content;
use packages\Domain\Basic\Mail\Header;
use packages\Domain\Basic\Mail\Message;
use packages\Domain\Basic\Mail\Subject;

class SMTPSendRequest
{

    private Header $header;
    private Message $message;
    private Mailable $mailable;


    /**
     * Create a new message instance.
     * TODO mailableは冗長
     * @return void
     */
    public function __construct(Header $header, Message $message, Mailable $mailable)
    {
        $this->header = $header;
        $this->message = $message;
        $this->mailable =$mailable;
    }

    public function getFrom(): Address
    {
        return $this->header->getFrom();
    }

    public function getTo(): AddressList
    {
        return $this->header->getTo();
    }
    public function getCc(): AddressList
    {
        return $this->header->getCc();
    }

    public function getSubject(): Subject
    {
        return $this->message->getSubject();
    }
    public function getContent(): Content
    {
        return $this->message->getContent();
    }
    public function getMailable(): Mailable
    {
        return $this->mailable;
    }
}
