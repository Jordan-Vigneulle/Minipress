<?php 

namespace minipress\appli\application_core\application\useCases\categorie;

Interface IGestionCategorie {
    public static function getCategories(): array; 

    public function getArticlesByCategorie(int $categ_id): array;

    public function getArticleById(int $id): array;

    public function markdownToHTML(string $md): string;
}