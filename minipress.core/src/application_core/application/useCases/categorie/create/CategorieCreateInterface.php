<?php

namespace minipress\appli\application_core\application\useCases\categorie\create;

interface CategorieCreateInterface {

    public function create(string $titre): void;
}