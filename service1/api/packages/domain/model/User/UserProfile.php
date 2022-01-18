<?php

declare(strict_types=1);

namespace packages\domain\model\User;

use Doctrine\Common\Collections\ArrayCollection;

class UserProfile
{
    private string $name;
    private string $tel;
    private string $mail;
}
