<?php

namespace minipress\appli\application_core\application\useCases\article\create;

interface IArticleCreate
{
    public function create(
        string $title,
        string $resume,
        string $contenu,
        string $categorieId,
    ): string;

}