<?php
session_start();
include 'db/config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$task_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    echo "Task not found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $category = htmlspecialchars($_POST['category']);

    $update = $conn->prepare("UPDATE tasks SET title = ?, description = ?, due_date = ?, priority = ?, category = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("sssssii", $title, $description, $due_date, $priority, $category, $task_id, $user_id);
    $update->execute();

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - TaskBuddy</title>
        <link rel="icon" href="fav.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            color: #2d3748;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2rem;
            margin: 2rem auto;
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeInUp 0.8s ease-out;
            max-width: 600px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header Styling */
        h2 {
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-align: center;
        }

        /* Form Styling */
        .form-container {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            animation: slideInUp 0.6s ease-out 0.2s both;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            margin-bottom: 1.5rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
            outline: none;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Button Styling */
        .btn {
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
            font-size: 0.95rem;
            margin-right: 1rem;
            margin-bottom: 1rem;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ed8936, #dd6b20);
            color: white;
            box-shadow: 0 4px 15px rgba(237, 137, 54, 0.3);
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(237, 137, 54, 0.4);
            background: linear-gradient(135deg, #dd6b20, #c53030);
            color: white;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
            box-shadow: 0 4px 15px rgba(113, 128, 150, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(113, 128, 150, 0.4);
            background: linear-gradient(135deg, #4a5568, #2d3748);
            color: white;
        }

        /* Icon spacing */
        .btn i {
            margin-right: 0.5rem;
        }

        /* Button container */
        .button-group {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 2rem;
        }

        /* Priority selector styling */
        .form-select option {
            background: white;
            color: #2d3748;
            padding: 0.5rem;
        }

        /* Form animations */
        .mb-3 {
            animation: fadeInLeft 0.5s ease-out;
            animation-fill-mode: both;
        }

        .mb-3:nth-child(1) { animation-delay: 0.1s; }
        .mb-3:nth-child(2) { animation-delay: 0.2s; }
        .mb-3:nth-child(3) { animation-delay: 0.3s; }
        .mb-3:nth-child(4) { animation-delay: 0.4s; }
        .mb-3:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 1.5rem;
                border-radius: 16px;
            }

            h2 {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            .btn {
                width: 100%;
                margin-right: 0;
                margin-bottom: 0.75rem;
            }

            .button-group {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        /* Input focus effects */
        .form-control:focus, .form-select:focus {
            transform: translateY(-1px);
        }

        /* Label hover effects */
        .form-label {
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .form-label:hover {
            color: #667eea;
        }
    </style>
</head>
<body class="container mt-4">
    <h2><i class="fas fa-edit"></i> Edit Task</h2>
    
    <div class="form-container">
        <form method="post">
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-heading"></i> Title</label>
                <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-align-left"></i> Description</label>
                <textarea name="description" class="form-control" placeholder="Enter task description..."><?= htmlspecialchars($task['description']) ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-calendar-alt"></i> Due Date</label>
                <input type="date" name="due_date" value="<?= $task['due_date'] ?>" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-exclamation-triangle"></i> Priority</label>
                <select name="priority" class="form-select">
                    <option value="High" <?= $task['priority'] === 'High' ? 'selected' : '' ?>>🔴 High Priority</option>
                    <option value="Medium" <?= $task['priority'] === 'Medium' ? 'selected' : '' ?>>🟡 Medium Priority</option>
                    <option value="Low" <?= $task['priority'] === 'Low' ? 'selected' : '' ?>>🟢 Low Priority</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-folder"></i> Category</label>
                <input type="text" name="category" value="<?= htmlspecialchars($task['category']) ?>" class="form-control" placeholder="e.g., Work, Personal, Study">
            </div>
            
            <div class="button-group">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i>Update Task
                </button>
                <a href="dashboard.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</body>
</html>