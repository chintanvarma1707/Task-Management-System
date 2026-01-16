<?php 
require 'db_conn.php'; 

$edit_task = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id=?");
    $stmt->execute([$id]);
    $edit_task = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>My Task Manager</h1>

        <form action="app.php" method="POST" class="task-form">
            <?php if ($edit_task): ?>
                <input type="hidden" name="id" value="<?php echo $edit_task['id']; ?>">
            <?php endif; ?>
            
            <input type="text" name="title" placeholder="What do you need to do?" required autocomplete="off" value="<?php echo $edit_task ? htmlspecialchars($edit_task['title']) : ''; ?>">
            <input type="datetime-local" name="deadline" class="date-input" value="<?php echo ($edit_task && $edit_task['deadline']) ? date('Y-m-d\TH:i', strtotime($edit_task['deadline'])) : ''; ?>">
            
            <?php if ($edit_task): ?>
                <button type="submit" name="update_task" class="add-btn update-btn">Update</button>
                <a href="index.php" class="cancel-btn">x</a>
            <?php else: ?>
                <button type="submit" name="add_task" class="add-btn">Add</button>
            <?php endif; ?>
        </form>

        <div class="task-list">
            <?php 
                $tasks = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
                if ($tasks->rowCount() > 0) {
                    while($task = $tasks->fetch(PDO::FETCH_ASSOC)) { 
                        $urgencyClass = 'safe';
                        if (!empty($task['deadline'])) {
                            $timeLeft = strtotime($task['deadline']) - time();
                            if ($timeLeft < 0) {
                                $urgencyClass = 'overdue';
                            } elseif ($timeLeft < 86400) {
                                $urgencyClass = 'due-soon';
                            }
                        }
                        
                        $completedClass = $task['checked'] ? 'completed' : '';
                        $checkIcon = $task['checked'] ? '&#10004;' : '&#9744;'; 
            ?>
                <div class="task-item <?php echo $urgencyClass . ' ' . $completedClass; ?>">
                    <div class="task-info">
                        <span class="task-text"><?php echo htmlspecialchars($task['title']); ?></span>
                        <?php if (!empty($task['deadline'])): ?>
                            <small class="task-deadline">Due: <?php echo date('M d, H:i', strtotime($task['deadline'])); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="actions">
                        <a href="app.php?id=<?php echo $task['id']; ?>&action=toggle" class="check-btn"><?php echo $checkIcon; ?></a>
                        <?php if (!$task['checked']): ?>
                            <a href="index.php?edit=<?php echo $task['id']; ?>" class="edit-btn">&#9998;</a>
                        <?php endif; ?>
                        <a href="app.php?del_task=<?php echo $task['id']; ?>" class="delete-btn">&times;</a>
                    </div>
                </div>
            <?php 
                    }
                } else {
            ?>
                <p class="empty-state">No tasks yet. Add one above!</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>