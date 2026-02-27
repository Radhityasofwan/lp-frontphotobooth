<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/config.php';

if ($pdo) {
    try {
        // Drop the leads table to start fresh with new columns
        $pdo->exec("DROP TABLE IF EXISTS leads");

        // Read schema
        $sql = file_get_contents(__DIR__ . '/schema.sql');

        // Execute schema
        $pdo->exec($sql);

        // Describe table explicitly to verify
        $stmt = $pdo->query("DESCRIBE leads");
        $cols = $stmt->fetchAll();
        echo "Table `leads` created successfully. Columns:\n";
        foreach ($cols as $c) {
            echo "- " . $c['Field'] . " (" . $c['Type'] . ")\n";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "DB connection failed in config.php.";
}
