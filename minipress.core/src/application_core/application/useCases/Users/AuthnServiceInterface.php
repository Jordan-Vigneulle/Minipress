<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\Users;

use minipress\appli\application_core\domain\entities\Utilisateur;

interface AuthnServiceInterface
{

    public function register(string $userId, string $pseudo, string $password): Utilisateur;

    public function checkCredentials(string $userId, string $password): ?Utilisateur;

    public function changePassword(Utilisateur $user, string $oldPassword, string $newPassword): void;
}