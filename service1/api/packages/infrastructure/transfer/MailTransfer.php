<?php

namespace packages\infrastructure\transfer;

interface MailTransfer
{
    public static function send(SMTPSendRequest $request);
}
