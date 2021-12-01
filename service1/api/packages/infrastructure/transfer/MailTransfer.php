<?php

namespace packages\Infrastructure\Transfer;

interface MailTransfer
{
    public static function send(SMTPSendRequest $request);
}
