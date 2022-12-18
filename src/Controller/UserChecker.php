<?php

namespace App\Controller;

use App\Entity\Login as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }


    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
        if ($user->getActivationToken()!=null) {
            // the message passed to this exception is meant to be displayed to the user
            dump('not act');

            throw new CustomUserMessageAuthenticationException('Votre compte n\'est pas activé');
        }
        if ($user->getBlockedLogin()==true) {
            // the message passed to this exception is meant to be displayed to the user
            dump('not act');

            throw new CustomUserMessageAuthenticationException('Votre compte a été bloquer');
        }
        // user account is expired, the user may be notified
//        if ($user->isExpired()) {
//            throw new AccountExpiredException('...');
//        }
    }
}