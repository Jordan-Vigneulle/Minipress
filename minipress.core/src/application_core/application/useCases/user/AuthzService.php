<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\Box;
use minipress\appli\application_core\domain\entities\User;

class AuthzService implements AuthzServiceInterface
{
    public function checkAuthorization(User $user, string $operation, ?Box $box = null): void
    {
        match ($operation) {
            self::CREATE_BOX => $this->requireRole($user, 1),

            self::VIEW_BOX,
            self::VALIDATE_BOX,
            self::ADD_PRESTATION,
            self::GENERATE_URL => $this->requireRoleAndOwnership($user, 1, $box),

            default => throw new \RuntimeException("Opération inconnue : $operation"),
        };
    }

    private function requireRole(User $user, int $minRole): void
    {
        if ($user->role < $minRole) {
            throw new \RuntimeException('Accès refusé : rôle insuffisant');
        }
    }

    private function requireRoleAndOwnership(User $user, int $minRole, ?Box $box): void
    {
        $this->requireRole($user, $minRole);

        if ($box === null || $box->createur_id !== $user->id) {
            throw new \RuntimeException('Accès refusé : vous n\'êtes pas propriétaire de cette box');
        }
    }
}