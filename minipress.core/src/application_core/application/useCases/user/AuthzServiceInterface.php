<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\user;

use minipress\appli\application_core\domain\entities\Utilisateur;
use minipress\appli\application_core\domain\entities\Box;

interface AuthzServiceInterface
{
    // Constantes opérations
    const CREATE_ARTICLE = 'create_article';
    const CREATE_CATEGORY = 'create_category';
    const CREATE_USER = 'create_user';
    const VIEW_ALL_ARTICLES = 'view_articles';
    const VIEW_OWN_ARTICLES = 'view_own_articles';

    /**
     * Vérifie si $user est autorisé à réaliser $operation.
     * Lève une \RuntimeException si non autorisé.
     */
    public function checkAuthorization(Utilisateur $user, string $operation): void;
}