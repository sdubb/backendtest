<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=venn-server.mysql.database.azure.com;dbname=vennverse',
            'username' => 'venn-database',
            'password' => 'Ay0413!1',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '#####',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'username' => '#####',
                'password' => '#####',
                'port' => '587', // Port 25 is a very common port too
                'encryption' => 'tls', // It is often used, check your provider or mail server specs
            ],
        ],


    ],
];
