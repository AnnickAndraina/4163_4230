<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;
    public string $defaultGroup = 'default';

    public array $default = [
        'database'    => '',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => '',
        'DBDebug'     => true,
        'swapPre'     => '',
        'failover'    => [],
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'synchronous' => null,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public array $tests = [
        'DSN'         => '',
        'hostname'    => '127.0.0.1',
        'username'    => '',
        'password'    => '',
        'database'    => ':memory:',
        'DBDriver'    => 'SQLite3',
        'DBPrefix'    => 'db_',
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => '',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => true,
        'failover'    => [],
        'port'        => 3306,
        'foreignKeys' => true,
        'busyTimeout' => 1000,
        'synchronous' => null,
        'dateFormat'  => [
            'date'     => 'Y-m-d',
            'datetime' => 'Y-m-d H:i:s',
            'time'     => 'H:i:s',
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
            return;
        }

        // 1. Définir le chemin absolu vers la racine pour base.db
        $dbPath = ROOTPATH . 'base.db';
        $this->default['database'] = $dbPath;

        // 2. Si le fichier base.db n'existe pas, on le crée et l'initialise avec l'extension native PHP
        if (!file_exists($dbPath)) {
            $sqlPath = ROOTPATH . 'base.sql';

            if (file_exists($sqlPath)) {
                // Utilisation de la classe SQLite3 native de PHP pour éviter la boucle infinie de CI4
                $sqlite = new \SQLite3($dbPath);
                
                // Lecture complète du fichier base.sql
                $sqlCommands = file_get_contents($sqlPath);
                
                // exec() gère parfaitement les requêtes multiples séparées par des ";" avec SQLite native
                $sqlite->exec($sqlCommands);
                
                // Fermeture propre du pointeur de fichier
                $sqlite->close();
            }
        }
    }
}