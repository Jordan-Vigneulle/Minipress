<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\article;

interface ArticleServiceInterface
{
    public function getArticlesPourAdmin(): array;

    public function basculerPublication(int $id): void;
    
    public function getArticleById(int $id): array;

    public function markdownToHTML(string $md): string;
}
