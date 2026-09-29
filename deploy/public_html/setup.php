<?php
/**
 * ====================================================
 *  FlipFlop CRM — Hostinger Setup Script
 * ====================================================
 *  Upload this file to: public_html/setup.php
 *  Open in browser:     https://yourdomain.com/setup.php
 *  DELETE THIS FILE after setup is complete!
 * ====================================================
 */

// ── Security: restrict to known IPs or set a secret token ──────────────────
define('SETUP_TOKEN', 'flipflop_setup_2026');   // Change this!

if (!isset($_GET['token']) || $_GET['token'] !== SETUP_TOKEN) {
    http_response_code(403);
    die('<h2 style="font-family:sans-serif;color:red;">403 Forbidden — Pass ?token=flipflop_setup_2026 in the URL</h2>');
}

// ── Paths ───────────────────────────────────────────────────────────────────
$appRoot    = dirname(__DIR__) . '/payout_app';
$publicPath = __DIR__;
$envFile    = $appRoot . '/.env';
$artisan    = $appRoot . '/artisan';

$results = [];

function run(string $label, callable $fn): array
{
    ob_start();
    try {
        $fn();
        $output = ob_get_clean();
        return ['label' => $label, 'status' => 'ok', 'output' => $output];
    } catch (\Throwable $e) {
        ob_get_clean();
        return ['label' => $label, 'status' => 'error', 'output' => $e->getMessage()];
    }
}

// ── Step 1: Verify directory structure ─────────────────────────────────────
$results[] = run('Check app directory', function() use ($appRoot) {
    if (!is_dir($appRoot)) {
        throw new \Exception("payout_app directory not found at: $appRoot");
    }
    echo "Found at: $appRoot";
});

// ── Step 2: Check .env exists ──────────────────────────────────────────────
$results[] = run('Check .env file', function() use ($envFile) {
    if (!file_exists($envFile)) {
        throw new \Exception(".env not found at: $envFile — copy .env.production to .env");
    }
    echo ".env exists ✓";
});

// ── Step 3: Storage symlink ────────────────────────────────────────────────
$results[] = run('Storage symlink', function() use ($appRoot, $publicPath) {
    $link   = $publicPath . '/storage';
    $target = $appRoot . '/storage/app/public';
    if (!is_link($link)) {
        if (!symlink($target, $link)) {
            throw new \Exception("Could not create storage symlink. Run: php artisan storage:link");
        }
        echo "Symlink created ✓";
    } else {
        echo "Symlink already exists ✓";
    }
});

// ── Step 4: Run artisan commands ───────────────────────────────────────────
$artisanCommands = [
    'Migrate DB'            => 'migrate --force',
    'Cache Config'          => 'config:cache',
    'Cache Routes'          => 'route:cache',
    'Cache Views'           => 'view:cache',
    'Optimize'              => 'optimize',
    'Create Admin (seeder)' => 'db:seed --class=RolesAndPermissionsSeeder --force',
];

foreach ($artisanCommands as $label => $cmd) {
    $results[] = run($label, function() use ($appRoot, $cmd) {
        chdir($appRoot);
        $output = shell_exec("php artisan $cmd 2>&1");
        echo nl2br(htmlspecialchars($output ?? 'No output'));
    });
}

// ── Step 5: Directory permissions ─────────────────────────────────────────
$results[] = run('Set storage permissions', function() use ($appRoot) {
    $dirs = [
        $appRoot . '/storage',
        $appRoot . '/bootstrap/cache',
    ];
    foreach ($dirs as $dir) {
        if (is_dir($dir)) {
            chmod($dir, 0775);
            $iter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            foreach ($iter as $item) {
                chmod($item->getPathname(), $item->isDir() ? 0775 : 0664);
            }
        }
    }
    echo "Permissions set ✓";
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>FlipFlop CRM — Setup</title>
<style>
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f1f5f9;padding:2rem}
    .card{background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.08);max-width:800px;margin:auto;overflow:hidden}
    .header{background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#fff;padding:1.5rem 2rem}
    .header h1{font-size:1.5rem;font-weight:700}
    .header p{opacity:.8;font-size:.9rem;margin-top:.25rem}
    .body{padding:1.5rem 2rem}
    .step{border:1px solid #e2e8f0;border-radius:8px;margin-bottom:1rem;overflow:hidden}
    .step-header{display:flex;align-items:center;padding:.75rem 1rem;font-weight:600;font-size:.9rem}
    .ok .step-header{background:#f0fdf4;color:#15803d;border-bottom:1px solid #bbf7d0}
    .error .step-header{background:#fef2f2;color:#dc2626;border-bottom:1px solid #fecaca}
    .badge{display:inline-block;padding:.2rem .6rem;border-radius:999px;font-size:.75rem;font-weight:700;margin-left:.5rem}
    .ok .badge{background:#dcfce7;color:#15803d}
    .error .badge{background:#fee2e2;color:#dc2626}
    .step-body{padding:.75rem 1rem;font-size:.82rem;color:#475569;background:#f8fafc;font-family:monospace;line-height:1.6;max-height:200px;overflow-y:auto;white-space:pre-wrap}
    .warning{background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:1rem;margin-top:1.5rem;color:#92400e;font-size:.9rem}
    .warning strong{display:block;margin-bottom:.25rem}
</style>
</head>
<body>
<div class="card">
    <div class="header">
        <h1>🚀 FlipFlop CRM — Server Setup</h1>
        <p>Running deployment steps on Hostinger…</p>
    </div>
    <div class="body">
        <?php foreach ($results as $r): ?>
        <div class="step <?= $r['status'] ?>">
            <div class="step-header">
                <?= $r['status'] === 'ok' ? '✅' : '❌' ?>
                &nbsp; <?= htmlspecialchars($r['label']) ?>
                <span class="badge"><?= strtoupper($r['status']) ?></span>
            </div>
            <?php if ($r['output']): ?>
            <div class="step-body"><?= $r['output'] ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

        <div class="warning">
            <strong>⚠️ IMPORTANT — Delete this file after setup!</strong>
            Remove <code>public_html/setup.php</code> from your server immediately.
            This file exposes server commands and must not remain publicly accessible.
        </div>
    </div>
</div>
</body>
</html>
