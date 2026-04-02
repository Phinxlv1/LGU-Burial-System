<?php

/**
 * migrate_data.php (v2 — retries burial_permits and deceased_persons)
 * Run this from your project root: php migrate_data.php
 */

define('BASE_PATH', __DIR__);

$mysql_host = 'localhost';
$mysql_port = '3306';
$mysql_db = 'burial_permit_db';
$mysql_user = 'root';
$mysql_pass = '';

$sqlite_path = BASE_PATH . '/database/database.sqlite';

// Only retry the two tables that failed last time
$retry_tables = ['burial_permits', 'deceased_persons'];

echo "\n";
echo "╔══════════════════════════════════════════════╗\n";
echo "║     MySQL → SQLite  (retry failed tables)    ║\n";
echo "╚══════════════════════════════════════════════╝\n\n";

// ─── Connect to MySQL ────────────────────────────────────────────────────────
echo "[1/3] Connecting to MySQL...\n";
try {
    $mysql = new PDO(
        "mysql:host={$mysql_host};port={$mysql_port};dbname={$mysql_db};charset=utf8mb4",
        $mysql_user,
        $mysql_pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "      ✓ Connected\n\n";
} catch (PDOException $e) {
    die("      ✗ MySQL failed: " . $e->getMessage() . "\n\n");
}

// ─── Connect to SQLite ───────────────────────────────────────────────────────
echo "[2/3] Connecting to SQLite...\n";
try {
    $sqlite = new PDO(
        "sqlite:{$sqlite_path}",
        null,
        null,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $sqlite->exec('PRAGMA foreign_keys = OFF;');
    echo "      ✓ Connected\n\n";
} catch (PDOException $e) {
    die("      ✗ SQLite failed: " . $e->getMessage() . "\n\n");
}

// ─── Run the new migration to add missing columns ────────────────────────────
echo "[3/3] Applying missing columns + copying data...\n\n";

putenv('DB_CONNECTION=sqlite');
putenv("DB_DATABASE={$sqlite_path}");

$output = [];
$return_code = 0;
exec('php artisan migrate --force 2>&1', $output, $return_code);

foreach ($output as $line) {
    echo "      " . $line . "\n";
}

if ($return_code !== 0) {
    echo "\n      ✗ artisan migrate failed. Fix errors above before continuing.\n\n";
    exit(1);
}
echo "\n";

// ─── Copy only the failed tables ────────────────────────────────────────────
$total_rows = 0;
$failed_tables = [];

foreach ($retry_tables as $table) {
    try {
        // Clear any partial data from the previous attempt
        $sqlite->exec("DELETE FROM `{$table}`");

        $rows = $mysql->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            echo "      ○ Empty:   {$table} (0 rows)\n";
            continue;
        }

        $columns = array_keys($rows[0]);
        $col_list = implode(', ', array_map(fn($c) => "`{$c}`", $columns));
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $insert_sql = "INSERT OR REPLACE INTO `{$table}` ({$col_list}) VALUES ({$placeholders})";

        $sqlite->beginTransaction();
        $stmt = $sqlite->prepare($insert_sql);

        foreach ($rows as $row) {
            $stmt->execute(array_values($row));
        }

        $sqlite->commit();

        $count = count($rows);
        $total_rows += $count;
        echo "      ✓ Copied:  {$table} ({$count} rows)\n";

    } catch (PDOException $e) {
        if ($sqlite->inTransaction()) {
            $sqlite->rollBack();
        }
        $failed_tables[] = $table;
        echo "      ✗ FAILED:  {$table} — " . $e->getMessage() . "\n";
    }
}

// ─── Summary ─────────────────────────────────────────────────────────────────
echo "\n";
echo "════════════════════════════════════════════════\n";

if (empty($failed_tables)) {
    echo "  ✓ All tables copied successfully!\n";
    echo "  Total rows copied this run : {$total_rows}\n";
    echo "\n  Next steps:\n";
    echo "  1. Update .env  →  DB_CONNECTION=sqlite\n";
    echo "  2. Remove DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD lines\n";
    echo "  3. Stop MySQL in XAMPP — you're done with it\n";
    echo "  4. Run: php artisan db:show  (should say sqlite)\n";
} else {
    echo "  ✗ Still failing: " . implode(', ', $failed_tables) . "\n";
    echo "  Share the errors above and we'll fix them.\n";
}

echo "════════════════════════════════════════════════\n\n";