<?php

namespace App\Utils\Session;

use Symfony\Component\HttpFoundation\Session\Session;

class UserSession
{
    const BAG_NAME = "user";

    public static function signIn(Session $session)
    {
        $session->set(self::BAG_NAME . "/signed", true);
    }

    public static function signOut(Session $session)
    {
        $session->set(self::BAG_NAME . "/signed", false);
    }

    public static function isSignedIn(Session $session): bool
    {
        return $session->get(self::BAG_NAME . "/signed");
    }
}
