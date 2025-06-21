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

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task - TaskBuddy</title>
        <link rel="icon" href="fav.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            max-width: 800px;
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
        .header-section {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(102, 126, 234, 0.1);
            text-align: center;
        }

        h2 {
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            font-size: 1.8rem;
        }

        /* Form Container */
        .form-container {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            animation: slideInForm 0.6s ease-out 0.2s both;
        }

        @keyframes slideInForm {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Elements */
        .form-label {
            font-weight: 600;
            color: #2d3748;
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
            color: #2d3748;
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
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
            font-size: 0.95rem;
            margin-right: 0.5rem;
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

        .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
            background: linear-gradient(135deg, #38a169, #2f855a);
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

        /* Form Animation */
        .mb-3 {
            animation: slideInField 0.5s ease-out;
            animation-fill-mode: both;
        }

        .mb-3:nth-child(1) { animation-delay: 0.1s; }
        .mb-3:nth-child(2) { animation-delay: 0.15s; }
        .mb-3:nth-child(3) { animation-delay: 0.2s; }
        .mb-3:nth-child(4) { animation-delay: 0.25s; }
        .mb-3:nth-child(5) { animation-delay: 0.3s; }

        @keyframes slideInField {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Button Container */
        .button-container {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-start;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            animation: slideInField 0.5s ease-out 0.35s both;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 1rem;
                border-radius: 16px;
            }

            .form-container {
                padding: 1.5rem;
            }

            .header-section {
                padding: 1rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .button-container {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                margin-right: 0;
            }
        }

        /* Input focus effects */
        .form-control:focus, .form-select:focus {
            transform: translateY(-1px);
        }

        /* Hover effects for form elements */
        .form-control:hover, .form-select:hover {
            border-color: #cbd5e0;
        }

        /* Priority select styling */
        select[name="priority"] option[value="High"] {
            color: #f56565;
        }
        select[name="priority"] option[value="Medium"] {
            color: #ed8936;
        }
        select[name="priority"] option[value="Low"] {
            color: #48bb78;
        }
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
</body>
</html>