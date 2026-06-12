<?php 

namespace minipress\appli\application_core\application\useCases\Categories;

Interface CategorieServiceInterface {

    public static function getCategories(): array; 

    public static function getCategoriesWithPublishedArticles(): array; 

    public function getPublishedArticlesByCategorie(int $categ_id): array;
    
}