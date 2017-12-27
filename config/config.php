<?php

$config = [
    'class_components' => [
        'user' => '\vendor\core\User',
        'cache' => '\vendor\core\Cache',
    ],
    'super_user' => '1',
    'date_format' => "Y-m-d, H:i",
    'url' => "http://localhost",
    'allowed_tags' => '<ul><li><img><p><b><i><span><div><ol><strong><em><table><tbody><tr><td><br><hr>',
];


return $config;