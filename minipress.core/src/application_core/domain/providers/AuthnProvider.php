<?php
declare(strict_types=1);

namespace minipress\appli\application_core\domain\providers;

use minipress\appli\application_core\domain\entities\Utilisateur;
use minipress\appli\application_core\application\useCases\Users\AuthnServiceInterface;

class AuthnProvider implements AuthnProviderInterface
{
    public function __construct(
        private readonly AuthnServiceInterface $authnService,
    ) {
    }

    public function getSignedInUser(): ?Utilisateur
    {
        if (empty($_SESSION['user'])) {
            return null;
        }
        return Utilisateur::find($_SESSION['user']);
    }

    public function signin(string $email, string $password): ?Utilisateur
    {
        $user = $this->authnService->checkCredentials($email, $password);

        if ($user === null) {
            return null;
        }

        $_SESSION['user'] = $user->id;



        return $user;
    }
}