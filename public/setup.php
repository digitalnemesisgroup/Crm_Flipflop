<?php
// Flipflop Payout System - Production Update Setup Script
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Flipflop Payout System - Update Setup</h1>";
echo "<pre>";

$baseDir = null;
if (file_exists(__DIR__ . '/artisan')) {
    $baseDir = __DIR__;
} elseif (file_exists(dirname(__DIR__) . '/artisan')) {
    $baseDir = dirname(__DIR__);
}

if ($baseDir) {
    echo "Artisan file found at: $baseDir\n\n";
    
    // Step 0: Ensure required storage & cache directories exist with permissions
    echo "0. Ensuring required storage and cache directories exist...\n";
    $requiredDirs = [
        $baseDir . '/storage/framework/views',
        $baseDir . '/storage/framework/cache',
        $baseDir . '/storage/framework/cache/data',
        $baseDir . '/storage/framework/sessions',
        $baseDir . '/storage/logs',
        $baseDir . '/storage/app/public',
        $baseDir . '/bootstrap/cache',
    ];
    foreach ($requiredDirs as $dir) {
        if (!is_dir($dir)) {
            if (mkdir($dir, 0775, true)) {
                echo "   Created: $dir\n";
            } else {
                echo "   Failed to create: $dir\n";
            }
        } else {
            @chmod($dir, 0775);
            echo "   Exists: $dir\n";
        }
    }
    echo "\n";
    
    // Clear caches safely
    echo "1. Clearing application caches...\n";
    echo shell_exec("php $baseDir/artisan optimize:clear 2>&1") . "\n";
    
    // Run safe migrations only (NO db:wipe, NO migrate:fresh, NO database truncation)
    echo "2. Running migrations safely (updating schema, preserving existing DB data)...\n";
    echo shell_exec("php $baseDir/artisan migrate --force 2>&1") . "\n";
    
    // Storage link
    echo "3. Linking storage directory...\n";
    echo shell_exec("php $baseDir/artisan storage:link 2>&1") . "\n";

    // Cache views and routes for optimal performance
    echo "4. Re-building view and config caches...\n";
    echo shell_exec("php $baseDir/artisan view:cache 2>&1") . "\n";
    echo shell_exec("php $baseDir/artisan config:cache 2>&1") . "\n";
    echo shell_exec("php $baseDir/artisan route:cache 2>&1") . "\n";
    
    echo "\n<b>Update Setup Completed Successfully!</b>\n";
    echo "\n⚠️ <b>IMPORTANT:</b> Please delete this setup.php file from your server after updating for security reasons.\n";
} else {
    echo "Error: artisan file not found. Make sure setup.php is located in public/ or the project root directory.";
}
echo "</pre>";
