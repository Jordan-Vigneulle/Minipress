<?php 

namespace minipress\appli\application_core\application\useCases\categorie;

Interface CategorieServiceInterface {
    public static function getCategories(): array; 

    public function getArticlesByCategorie(int $categ_id): array;
}