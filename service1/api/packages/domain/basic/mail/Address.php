<?php

namespace packages\domain\basic\mail;

use Egulias\EmailValidator\EmailLexer;
use Egulias\EmailValidator\Validation\RFCValidation;
use Exception;

class Address
{
    protected Mail $mail;
    public string $personal;
    public string $address;

    public function __construct(Mail $mail, string $personal)
    {
        $this->mail = $mail;
        $this->personal = $personal;
        $this->toInternetAddress();
    }

    public function toInternetAddress()
    {
        $valid = new RFCValidation();
        try {
            $valid->isValid($this->mail->toString(), new EmailLexer());
        } catch (Exception $e) {
            //$valid->getError();
            //$valid->getWarnings();
        }
        $this->address = $this->mail->toString();
    }
}
