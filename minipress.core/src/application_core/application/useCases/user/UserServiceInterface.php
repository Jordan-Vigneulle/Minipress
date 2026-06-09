<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\User;

interface UserServiceInterface
{
    public function changeUsername(User $user, string $newUsername): void;
    public function changeAvatar(User $user, string $cheminAccesImg): void;
}