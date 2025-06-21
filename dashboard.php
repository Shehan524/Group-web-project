<?php
session_start();
include 'db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Handle task complete toggle
if (isset($_GET['complete']) && isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $update = $conn->prepare("UPDATE tasks SET is_completed = NOT is_completed WHERE id = ? AND user_id = ?");
    $update->bind_param("ii", $task_id, $user_id);
    $update->execute();
    header("Location: dashboard.php");
    exit();
}

// Handle task delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $delete = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $delete->bind_param("ii", $task_id, $user_id);
    $delete->execute();
    header("Location: dashboard.php");
    exit();
}

// Search and filters
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? 'all';
$status = $_GET['status'] ?? 'all';

$conditions = ["user_id = ?"];
$params = [$user_id];
$types = "i";

if ($search !== '') {
    $conditions[] = "(title LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}
if ($category !== 'all') {
    $conditions[] = "category = ?";
    $params[] = $category;
    $types .= "s";
}
if ($status === 'completed') {
    $conditions[] = "is_completed = 1";
} elseif ($status === 'pending') {
    $conditions[] = "is_completed = 0";
}

$where = implode(" AND ", $conditions);
$query = "SELECT * FROM tasks WHERE $where ORDER BY due_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TaskBuddy</title>
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
            max-width: 1200px;
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
            font-size: 0.9rem;
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

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #5a6fd8, #6a3e91);
            color: white;
        }

        /* Small button variants */
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .btn-outline-primary {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .btn-outline-primary:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-1px);
        }

        .btn-outline-warning {
            background: rgba(237, 137, 54, 0.1);
            color: #ed8936;
            border: 1px solid rgba(237, 137, 54, 0.3);
        }

        .btn-outline-warning:hover {
            background: #ed8936;
            color: white;
            border-color: #ed8936;
            transform: translateY(-1px);
        }

        .btn-outline-danger {
            background: rgba(245, 101, 101, 0.1);
            color: #f56565;
            border: 1px solid rgba(245, 101, 101, 0.3);
        }

        .btn-outline-danger:hover {
            background: #f56565;
            color: white;
            border-color: #f56565;
            transform: translateY(-1px);
        }

        /* Filter Form Styling */
        .filter-section {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        /* Table Styling */
        .table-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .table {
            margin: 0;
            background: transparent;
        }

        .table thead th {
            background: linear-gradient(135deg, #2d3748, #4a5568);
            color: white;
            font-weight: 600;
            padding: 1rem;
            border: none;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            padding: 1rem;
            border-color: rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.7);
            transition: all 0.3s ease;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table-success td {
            background: rgba(72, 187, 120, 0.1);
        }

        .table-success:hover td {
            background: rgba(72, 187, 120, 0.15);
        }

        .completed {
            text-decoration: line-through;
            color: #718096;
            opacity: 0.8;
        }

        /* Priority and Status Badges */
        .priority-high { color: #f56565; font-weight: 600; }
        .priority-medium { color: #ed8936; font-weight: 600; }
        .priority-low { color: #48bb78; font-weight: 600; }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #718096;
            font-style: italic;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 1rem;
                border-radius: 16px;
            }

            .header-section {
                padding: 1rem;
            }

            h2 {
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .table-responsive {
                border-radius: 12px;
                overflow: hidden;
            }

            .btn {
                font-size: 0.8rem;
                padding: 0.6rem 1rem;
            }

            .btn-sm {
                padding: 0.4rem 0.8rem;
                font-size: 0.75rem;
            }
        }

        /* Animation for table rows */
        .table tbody tr {
            animation: slideInRow 0.5s ease-out;
            animation-fill-mode: both;
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.15s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.25s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.3s; }

        @keyframes slideInRow {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Filter animation */
        .filter-section {
            animation: slideInDown 0.6s ease-out 0.2s both;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Action buttons container */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }
            
            .action-buttons .btn {
                width: 100%;
                margin-bottom: 0.25rem;
            }
        }

        /* Icon spacing */
        .btn i {
            margin-right: 0.5rem;
        }

        .btn-sm i {
            margin-right: 0.3rem;
        }
    </style>
</head>
<body class="container mt-4">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Hello, <?= htmlspecialchars($username) ?> 👋</h2>
            <div>
                <a href="add_task.php" class="btn btn-success"><i class="fas fa-plus"></i>Add Task</a>
                <a href="logout.php" class="btn btn-secondary"><i class="fas fa-sign-out-alt"></i>Logout</a>
            </div>
        </div>
    </div>

    <!-- Filter/Search Form -->
    <div class="filter-section">
        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search tasks..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="all">All Categories</option>
                    <option value="Work" <?= $category == 'Work' ? 'selected' : '' ?>>Work</option>
                    <option value="Personal" <?= $category == 'Personal' ? 'selected' : '' ?>>Personal</option>
                    <option value="Study" <?= $category == 'Study' ? 'selected' : '' ?>>Study</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>All Statuses</option>
                    <option value="completed" <?= $status == 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="pending" <?= $status == 'pending' ? 'selected' : '' ?>>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i>Filter</button>
            </div>
        </form>
    </div>

    <!-- Tasks Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Due</th>
                        <th>Priority</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th width="250">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($task = $result->fetch_assoc()): ?>
                        <tr class="<?= $task['is_completed'] ? 'table-success' : '' ?>">
                            <td class="<?= $task['is_completed'] ? 'completed' : '' ?>"><?= htmlspecialchars($task['title']) ?></td>
                            <td><?= $task['due_date'] ?></td>
                            <td class="priority-<?= strtolower($task['priority']) ?>"><?= $task['priority'] ?></td>
                            <td><?= htmlspecialchars($task['category']) ?></td>
                            <td><?= $task['is_completed'] ? '✅ Completed' : '❌ Pending' ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?complete=1&id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                    <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=1&id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this task?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center empty-state">No tasks found. Create your first task to get started! 🚀</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>