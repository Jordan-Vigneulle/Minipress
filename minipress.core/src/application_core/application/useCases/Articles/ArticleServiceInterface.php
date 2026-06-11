<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\Articles;

interface ArticleServiceInterface
{
    public function getArticles(?string $sort = null): array;

    public function togglePublication(int $id): void;
    
    public function getArticleById(int $id): array;

    public function markdownToHTML(string $md): string;
}
