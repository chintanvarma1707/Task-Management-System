<?php
require 'db_conn.php';

try {
    $stmt = $conn->query("SHOW COLUMNS FROM tasks LIKE 'deadline'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $sql = "ALTER TABLE tasks ADD COLUMN deadline DATETIME NULL AFTER title";
        $conn->exec($sql);
        echo "Successfully added 'deadline' column to 'tasks' table.\n";
    } else {
        echo "'deadline' column already exists.\n";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
