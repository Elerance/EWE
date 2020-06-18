<?

return [
    'defaultDriver' => 'mysql',
    'drivers' => [
        'mysql' => [
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'database' => 'ewe',
            'driverClass' => '\\App\\DataBase\\Drivers\\MySQLDriver'
        ]
    ]
];