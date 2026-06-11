<?php

namespace minipress\appli\application_core\application\useCases\Categories\create;

use minipress\appli\application_core\application\useCases\Categories\create\CategorieCreateInterface;
use minipress\appli\application_core\domain\entities\Categorie;


class CategorieCreate implements CategorieCreateInterface {

    public function create(string $titre): void {
        $catagorie = new Categorie();
        $catagorie->titre = $titre;
        $catagorie->save();
    }
}