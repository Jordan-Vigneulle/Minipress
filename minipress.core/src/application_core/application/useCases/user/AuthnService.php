<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\User;
use Ramsey\Uuid\Uuid;

class AuthnService implements AuthnServiceInterface
{
    private const PASSWORD_REGEX =
        '/^(?=.*[A-Za-z])(?=.*[!@#$%^&*()\-+])(?=.*\d)[A-Za-z\d!@#$%^&*()\-+]{8,}$/';

    public function register(string $userId, string $password): User
    {
        if (empty($userId) || empty($password)) {
            throw new \RuntimeException('Veuillez saisir un identifiant et un mot de passe');
        }

        if (User::where('user_id', $userId)->exists()) {
            throw new \RuntimeException('Cet identifiant existe déjà');
        }

        if (!preg_match(self::PASSWORD_REGEX, $password)) {
            throw new \RuntimeException(
                'Le mot de passe doit comporter au moins 8 caractères, '
                . 'dont une lettre, un chiffre et un caractère spécial'
            );
        }

        return User::create([
            'id' => Uuid::uuid4()->toString(),
            'user_id' => $userId,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 1,
        ]);
    }

    public function checkCredentials(string $userId, string $password): ?User
    {
        $user = User::where('user_id', $userId)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return null;
        }

        return $user;
    }

    public function changePassword(User $user, string $oldPassword, string $newPassword): void
    {
        if (!password_verify($oldPassword, $user->password)) {
            throw new \RuntimeException('Ancien mot de passe incorrect');
        }

        if (!preg_match(self::PASSWORD_REGEX, $newPassword)) {
            throw new \RuntimeException(
                'Le mot de passe doit comporter au moins 8 caractères, '
                . 'dont une lettre, un chiffre et un caractère spécial'
            );
        }

        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->save();
    }
}