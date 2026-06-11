<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\Utilisateur;

class AuthzService implements AuthzServiceInterface
{
    public function checkAuthorization(Utilisateur $user, string $operation): void
    {
        match ($operation) {
            self::CREATE_ARTICLE => $this->requireRole($user, 1),
            self::CREATE_CATEGORY => $this->requireRole($user, 1),
            self::CREATE_USER => $this->requireRole($user, 100),
            self::VIEW_OWN_ARTICLES => $this->requireRole($user, 1),
            self::VIEW_ALL_ARTICLES => $this->requireRole($user, 100),
            default => throw new \RuntimeException("Opération inconnue : $operation"),
        };
    }

    private function requireRole(Utilisateur $user, int $minRole): void
    {
        if ($user->role < $minRole) {
            throw new \RuntimeException('Accès refusé : rôle insuffisant');
        }
    }
}