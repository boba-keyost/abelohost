<?php
// Load environment-specific configuration
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

$connection = getenv('MIGRATION_CONNECTION') ?: 'mysql://root@localhost/mydb';
$path = getenv('MIGRATION_PATH') ?: './database/migrations';
$table = getenv('MIGRATION_TABLE') ?: 'migration_version';
$command = $argv[1] ?? 'version';

$cmd = sprintf(
    '%s/migrate %s --connection %s --path %s --table %s',
    __DIR__ . '/../vendor/bin',
    escapeshellarg($command),
    escapeshellarg($connection),
    escapeshellarg($path),
    escapeshellarg($table)
);

passthru($cmd, $exitCode);
exit($exitCode);