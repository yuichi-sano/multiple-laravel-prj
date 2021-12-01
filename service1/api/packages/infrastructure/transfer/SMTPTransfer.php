<?php

namespace packages\Infrastructure\Transfer;


use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 * TODO Queueに関する決めごと及び仕組の設計要
 */

class SMTPTransfer implements MailTransfer
{
    public static function send(SMTPSendRequest $request){
        Mail::mailer('smtp')->to($request->getTo()->toArray())->send($request->getMailable());
    }
}
