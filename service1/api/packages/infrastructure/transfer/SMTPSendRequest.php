<?php

namespace packages\infrastructure\transfer;

use Illuminate\Mail\Mailable;
use packages\domain\basic\mail\Address;
use packages\domain\basic\mail\AddressList;
use packages\domain\basic\mail\Content;
use packages\domain\basic\mail\Header;
use packages\domain\basic\mail\Message;
use packages\domain\basic\mail\Subject;

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
