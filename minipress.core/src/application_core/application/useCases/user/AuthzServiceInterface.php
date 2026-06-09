<?php
declare(strict_types=1);

namespace gift\appli\application_core\application\useCases\user;

use gift\appli\application_core\domain\entities\User;
use gift\appli\application_core\domain\entities\Box;

interface AuthzServiceInterface
{
    // Constantes opérations
    const CREATE_BOX = 'create_box';
    const VIEW_BOX = 'view_box';
    const VALIDATE_BOX = 'validate_box';
    const ADD_PRESTATION = 'add_prestation';
    const GENERATE_URL = 'generate_url';

    /**
     * Vérifie si $user est autorisé à réaliser $operation (sur $box si fournie).
     * Lève une \RuntimeException si non autorisé.
     */
    public function checkAuthorization(User $user, string $operation, ?Box $box = null): void;
}