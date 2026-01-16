<?php
require 'db_conn.php';

if (isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO tasks (title, deadline) VALUES (?, ?)"); 
        $stmt->execute([$title, $deadline]);
    }
    header("Location: index.php");
    exit();
}

if (isset($_POST['update_task'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $deadline = !empty($_POST['deadline']) ? $_POST['deadline'] : null;

    if (!empty($title) && !empty($id)) {
        $stmt = $conn->prepare("UPDATE tasks SET title=?, deadline=? WHERE id=?");
        $stmt->execute([$title, $deadline, $id]);
    }
    header("Location: index.php");
    exit();
}

if (isset($_GET['del_task'])) {
    $id = $_GET['del_task'];

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'toggle') {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT checked FROM tasks WHERE id=?");
    $stmt->execute([$id]);
    $task = $stmt->fetch();
    
    $new_checked = $task['checked'] ? 0 : 1;
    
    $stmt = $conn->prepare("UPDATE tasks SET checked=? WHERE id=?");
    $stmt->execute([$new_checked, $id]);
    
    header("Location: index.php");
    exit();
}
?>
