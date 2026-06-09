<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\User;

interface AuthnServiceInterface
{

    public function register(string $userId, string $password): User;

    public function checkCredentials(string $userId, string $password): ?User;

    public function changePassword(User $user, string $oldPassword, string $newPassword): void;
}