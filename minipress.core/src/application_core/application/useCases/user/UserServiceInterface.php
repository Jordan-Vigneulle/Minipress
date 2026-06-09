<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\Utilisateur;

interface UserServiceInterface
{
    public function changeUsername(Utilisateur $user, string $newUsername): void;
    public function changeAvatar(Utilisateur $user, string $cheminAccesImg): void;
}