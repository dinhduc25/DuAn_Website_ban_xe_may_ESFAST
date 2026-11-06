<?php

return [
    'name'     => getenv('APP_NAME') ?: 'ESFAST - Xe Máy Điện Thông Minh',
    'env'      => getenv('APP_ENV') ?: 'production',
    'debug'    => (bool) (getenv('APP_DEBUG') ?: true),
    'url'      => getenv('APP_URL') ?: 'http://localhost/DuAN',
    'timezone' => 'Asia/Ho_Chi_Minh',
    'currency' => 'VNĐ',
];
