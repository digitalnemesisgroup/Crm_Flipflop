<?php
/**
 * FlipFlop CRM Setup Script
 * Access via: https://crm.flipflp.online/setup.php?token=flipflop_setup_2026
 */

define('SETUP_TOKEN', 'flipflop_setup_2026');
if (!isset($_GET['token']) || $_GET['token'] !== SETUP_TOKEN) {
    http_response_code(403);
    die('<h2 style="color:red;">403 Forbidden</h2>');
}

$appRoot = dirname(__DIR__);
chdir($appRoot);

echo "<style>body{font-family:monospace;background:#1e1e1e;color:#d4d4d4;padding:20px}
h2{color:#4ec9b0}h3{color:#dcdcaa}pre{background:#252526;padding:15px;border-radius:6px;
overflow-x:auto;font-size:12px;white-space:pre-wrap}.ok{color:#4ec9b0}.err{color:#f48771}</style>";

echo "<h2>🚀 FlipFlop CRM — Migrate & Clear Cache</h2><pre>";

try {
    require $appRoot . '/vendor/autoload.php';
    $app = require_once $appRoot . '/bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $commands = [
        'migrate'        => ['--force' => true],
        'optimize:clear' => [],
        'config:cache'   => [],
        'route:cache'    => [],
        'view:cache'     => [],
    ];

    foreach ($commands as $cmd => $params) {
        echo "<b>[ $cmd ]</b>\n";
        \Illuminate\Support\Facades\Artisan::call($cmd, $params);
        echo htmlspecialchars(\Illuminate\Support\Facades\Artisan::output()) . "\n";
    }

} catch (\Throwable $e) {
    echo '<span class="err">ERROR: ' . htmlspecialchars($e->getMessage()) . '</span>';
}

echo "\n<b>=== Latest Laravel Log (last 60 lines) ===</b>\n";
$logFile = $appRoot . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    echo htmlspecialchars(implode('', array_slice($lines, -60)));
} else {
    echo 'No log file found.';
}

echo "</pre>";
