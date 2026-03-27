<?php

declare(strict_types=1);

namespace App\Utils\Session;

use Symfony\Component\HttpFoundation\Session\Session;

class UserSession
{
    private const string BAG_NAME = 'user';

    public static function signIn(Session $session): void {
        $session->set(self::BAG_NAME . '/signed', true);
    }

    public static function signOut(Session $session): void {
        $session->set(self::BAG_NAME . '/signed', false);
    }

    public static function isSignedIn(Session $session): bool {
        return $session->get(self::BAG_NAME . '/signed');
    }
}
