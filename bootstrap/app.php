<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$script_name = $server_https = $server_host = '';
if (!empty($_SERVER['SCRIPT_NAME'])) {
    $script_name = $_SERVER['SCRIPT_NAME'];
}
if (!empty($_SERVER['HTTPS'])) {
    $server_https = $_SERVER['HTTPS'];
}
if (!empty($_SERVER['HTTP_HOST'])) {
    $server_host = $_SERVER['HTTP_HOST'];
}

if (!defined('SUPER')) {
    define("SUPER", "superpnl"); //Super-admin
    define("BKND", "bckpnl"); //Back-End
    define("FRNT", "frntpnl"); //Front-End
    define("BS", "/");
    defined('DS') || define("DS", DIRECTORY_SEPARATOR);
    define("ROOTFOLDER", dirname($script_name));

    $http = ($server_https == "on") ? "https://" : "http://";
    $site_url = $http . $server_host . ROOTFOLDER;
    define('SITE_URL', trim($site_url, BS));
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \App\Http\Middleware\TeamsPermission::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
