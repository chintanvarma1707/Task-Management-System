<?php
require 'db_conn.php';

try {
    $stmt = $conn->query("SHOW COLUMNS FROM tasks LIKE 'checked'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $sql = "ALTER TABLE tasks ADD COLUMN checked TINYINT(1) DEFAULT 0";
        $conn->exec($sql);
        echo "Successfully added 'checked' column to 'tasks' table.\n";
    } else {
        echo "'checked' column already exists.\n";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
