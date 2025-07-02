<?php
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);
$path = trim($path, '/');

// Routes définies
$routes = [
// à faire quand les routes seront prêtes
];

if (array_key_exists($path, $routes)) {
    include $routes[$path];
} else {
    http_response_code(404);
}
?>