<?php

declare(strict_types=1);

namespace App\Utils\Session;

use Symfony\Component\HttpFoundation\Session\Session;

readonly class UserSession
{
    private const string BAG_NAME = 'user';

    public function signIn(Session $session): void {
        $session->set(self::BAG_NAME . '/signed', true);
    }

    public function signOut(Session $session): void {
        $session->set(self::BAG_NAME . '/signed', false);
    }

    public function isSignedIn(Session $session): bool {
        /** @var bool */
        return $session->get(self::BAG_NAME . '/signed');
    }
}
