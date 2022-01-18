<?php

namespace packages\service\authentication;

use packages\domain\model\authentication\Account;

interface AccountAuthenticationInterface
{
    public function execute (Account $account): Account;
}
