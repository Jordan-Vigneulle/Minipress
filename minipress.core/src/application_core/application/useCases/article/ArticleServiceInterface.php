<?php
declare(strict_types=1);

namespace minipress\appli\application_core\application\useCases\article;

interface ArticleServiceInterface
{
    public function getArticlesPourAdmin(): array;
    public function basculerPublication(int $id): void;
}
