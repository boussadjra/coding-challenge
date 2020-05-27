<?php

return [

    'supportsCredentials' => true,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['GET', 'POST', 'PUT',  'DELETE'],
    'exposedHeaders' => ['DAV', 'content-length', 'Allow'],
    'maxAge' => 86400,
    'hosts' => [],
];