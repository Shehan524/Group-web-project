<?php
session_start();
include 'db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $category = htmlspecialchars($_POST['category']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, due_date, priority, category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $title, $description, $due_date, $priority, $category);
    $stmt->execute();

    // Redirect with success flag
    header("Location: add_task.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Task - TaskBuddy</title>
  <link rel="icon" href="fav.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* (Your custom CSS remains unchanged here...) */
    /* To save space, I’m skipping repeating the CSS block.
       You can copy-paste it from your previous version above. */
  </style>
</head>

<body class="container mt-4">
  <div class="header-section">
    <h2>✨ Add New Task</h2>
  </div>

  <div class="form-container">
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Task Title</label>
        <input type="text" name="title" class="form-control" placeholder="Enter your task title..." required>
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" placeholder="Add task details and notes..."></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Priority Level</label>
        <select name="priority" class="form-select">
          <option value="High">🔴 High Priority</option>
          <option value="Medium" selected>🟡 Medium Priority</option>
          <option value="Low">🟢 Low Priority</option>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" placeholder="e.g., Work, Personal, Study...">
      </div>
      
      <div class="button-container">
        <button type="submit" class="btn btn-success">✅ Add Task</button>
        <a href="dashboard.php" class="btn btn-secondary">❌ Cancel</a>
      </div>
    </form>
  </div>

  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
  <script>
    window.onload = function() {
      alert("✅ Task added successfully!");
    };
  </script>
  <?php endif; ?>
</body>
</html>
