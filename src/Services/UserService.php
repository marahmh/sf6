<?php

namespace App\Services;

class UserService
{
private $mailer_code;

    /**
     * @return mixed
     */
    public function getMailerCode()
    {
        return $this->mailer_code;
    }

    /**
     * @param mixed $mailer_code
     */
    public function setMailerCode($mailer_code): void
    {
        $this->mailer_code = $mailer_code;
    }
}