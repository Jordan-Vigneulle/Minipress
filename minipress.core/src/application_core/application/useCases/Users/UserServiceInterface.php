<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\Users;

use minipress\appli\application_core\domain\entities\Utilisateur;

interface UserServiceInterface
{
    public function changeUsername(Utilisateur $user, string $newUsername): void;
    public function ChangeAvatar(Utilisateur $user, string $cheminAccesImg): void;
    public function getPublishedArticlesByUser(int $user_id): array;
    public function getArticlesByUser(int $user_id): array;
    public function changeUserToAuthor(int $user_id): void;
    public function getUsers(): array;
}