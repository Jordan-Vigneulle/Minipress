<?php

namespace minipress\appli\application_core\application\useCases\Categories\create;

interface CategorieCreateInterface {

    public function create(string $titre): void;
}