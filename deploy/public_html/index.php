<?php
/**
 * Modified index.php for Hostinger public_html
 * -----------------------------------------------
 * Place this file in:  public_html/index.php
 * Place the app in:    payout_app/ (one level above public_html)
 *
 * Hostinger file structure:
 *   /home/u910898544/
 *       public_html/         ← web root (this file lives here)
 *       payout_app/          ← Laravel root (app/, vendor/, etc.)
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Path to Laravel root (one folder above public_html)
$laravelRoot = __DIR__ . '/../payout_app';

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $laravelRoot . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $laravelRoot . '/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $laravelRoot . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
