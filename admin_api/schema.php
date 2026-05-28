<?php
require 'config.php';

function printSchema($pdo, $dbName) {
    echo "=== SCHEMA FOR $dbName ===\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "Table: $table\n";
        $stmt2 = $pdo->query("DESCRIBE $table");
        $cols = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        foreach ($cols as $col) {
            echo "  - " . $col['Field'] . " (" . $col['Type'] . ")\n";
        }
    }
    echo "\n";
}

printSchema($pdo_portofolio, DB_PORTFOLIO_LOCAL);
printSchema($pdo_proyek, DB_PROYEK_LOCAL);
