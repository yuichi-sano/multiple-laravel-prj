<?php

namespace packages\domain\basic\mail;

class Header
{
    private Address $from;
    private AddressList $to;
    private ?AddressList $cc;

    public function __construct(Address $from, AddressList $to, AddressList $cc = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->cc = $cc;
    }

    public function getFrom(): Address
    {
        return $this->from;
    }

    public function getTo(): AddressList
    {
        return $this->to;
    }

    public function getCc(): AddressList
    {
        return $this->cc;
    }
}
