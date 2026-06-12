<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\Users;

use minipress\appli\application_core\domain\entities\Utilisateur;
use minipress\appli\application_core\domain\entities\Article;

class UserService implements UserServiceInterface
{
    public function changeUsername(Utilisateur $user, string $newUsername): void
    {
        $newUsername = trim($newUsername);

        if (empty($newUsername)) {
            throw new \RuntimeException('Le pseudo ne peut pas être vide');
        }

        if (strlen($newUsername) > 50) {
            throw new \RuntimeException('Le pseudo est trop long (50 caractères max)');
        }

        $user->pseudo = htmlspecialchars($newUsername);
        $user->save();
    }

    public function ChangeAvatar(Utilisateur $user, string $cheminAccesImg): void
    {
        $user->chemin_acces_img = $cheminAccesImg;
        $user->save();
    }


    public function getPublishedArticlesByUser(int $user_id): array {
        $user = Utilisateur::where('id', $user_id)->first();
        if (!$user) {
            throw new \Exception("Aucun utilisateur trouvé avec l'identifiant $user_id");
        }

        $articles = Article::where([['id_utilisateur', $user_id], ['est_publie', 1]])->get();
        if ($articles->isEmpty()) {
            throw new \Exception("Aucun article trouvé pour l'utilisateur $user_id");
        }

        return [
            'auteur' => $user->toArray(),
            'articles' => $articles->toArray(),
        ];
    }

    public function getArticlesByUser(int $user_id): array {
        $user = Utilisateur::where('id', $user_id)->first();
        if (!$user) {
            throw new \Exception("Aucun utilisateur trouvé avec l'identifiant $user_id");
        }

        $articles = Article::where('id_utilisateur', $user_id)->get();
        if ($articles->isEmpty()) {
            throw new \Exception("Aucun article trouvé pour l'utilisateur $user_id");
        }

        return [
            'auteur' => $user->toArray(),
            'articles' => $articles->toArray(),
        ];
    }

    public function changeUserToAuthor(int $user_id): void
    {
        $updatedUser = Utilisateur::where('id', $user_id)->update(['role' => 2]);

        if (!$updatedUser) {
            throw new \Exception("Aucun utilisateur trouvé avec l'identifiant $user_id");
        }
    }
}