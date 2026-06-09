<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\User;

class UserService implements UserServiceInterface
{
    public function changeUsername(User $user, string $newUsername): void
    {
        $newUsername = trim($newUsername);

        if (empty($newUsername)) {
            throw new \RuntimeException('Le username ne peut pas être vide');
        }

        if (strlen($newUsername) > 50) {
            throw new \RuntimeException('Le username est trop long (50 caractères max)');
        }

        $user->username = htmlspecialchars($newUsername);
        $user->save();
    }

    public function changeAvatar(User $user, string $cheminAccesImg): void
    {
        $user->chemin_acces_img = $cheminAccesImg;
        $user->save();
    }
}