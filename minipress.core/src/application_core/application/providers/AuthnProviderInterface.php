<?php
declare(strict_types=1);

namespace minipress\appli\application_core\providers;

use minipress\appli\application_core\domain\entities\Utilisateur;

interface AuthnProviderInterface
{
    public function getSignedInUser(): ?Utilisateur;

    public function signin(string $email, string $password): ?Utilisateur;
}