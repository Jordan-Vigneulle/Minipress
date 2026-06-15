<?php
/**
 * File:  Eloquents.php
 * Creation Date: 27/12/2022
 * description: classe Eloquent, service de connexion à la base de données
 *
 * @author: canals
 */

namespace minipress\appli\infrastructure;

use Illuminate\Database\Capsule\Manager as DB ;

class Eloquent {

    public static function init($filename): void
    {
        if (!file_exists($filename)) {
            die("Fichier introuvable : " . $filename);
        }

        $config = parse_ini_file($filename);

        if ($config === false) {
            die("Erreur de lecture du fichier INI");
        }

        $db = new DB;
        $db->addConnection([
            'driver'    => $config['driver'],
            'host'      => $config['host'],
            'port'      => $config['port'],
            'database'  => $config['database'],
            'username'  => $config['username'],
            'password'  => $config['password'],
            'charset'   => $config['charset'],
            'collation' => 'utf8_unicode_ci',
        ]);
        $db->setAsGlobal();
        $db->bootEloquent();

    }
}

