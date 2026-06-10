<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\Utilisateur;
use minipress\appli\application_core\domain\entities\Article;

class UserService implements UserServiceInterface
{
    public function changeUsername(Utilisateur $user, string $newUsername): void
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

    public function changeAvatar(Utilisateur $user, string $cheminAccesImg): void
    {
        $user->chemin_acces_img = $cheminAccesImg;
        $user->save();
    }


    public function getArticlesByUser(int $user_id): array {
        $user = Utilisateur::where('id', $user_id)->first();
        if (!$user) {
            throw new \Exception("Aucun utilisateur trouvé avec l'identifiant $user_id");
        }

        $articles = Article::where('id_utilisateur', $user_id)->get();
        if ($articles->isEmpty()) {
            throw new \Exception("Aucun article trouvé pour l'auteur $user_id");
        }

        return [
            'auteur' => $user->toArray(),
            'articles' => $articles->toArray(),
        ];
    }
}