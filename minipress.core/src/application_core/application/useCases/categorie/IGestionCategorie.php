<?php 

namespace minipress\appli\application_core\application\useCases\categorie;

Interface IGestionCategorie {
    public static function getCategories(): array; 

    public function getArticlesByCategorie(int $id): array; 
}