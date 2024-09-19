<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

// Dummy data for dashboard
$user = [
    'name' => $_SESSION['username'],
    'role' => 'Senior Administrator',
    'email' => 'john.doe@example.com',
    'avatar' => 'https://i.pravatar.cc/150?img=68'
];

$stats = [
    ['title' => 'Total Users', 'value' => 15234, 'increase' => 12.5, 'icon' => 'fas fa-users'],
    ['title' => 'Active Sessions', 'value' => 1432, 'increase' => 5.7, 'icon' => 'fas fa-desktop'],
    ['title' => 'New Signups', 'value' => 458, 'increase' => 22.3, 'icon' => 'fas fa-user-plus'],
    ['title' => 'Support Tickets', 'value' => 73, 'increase' => -8.1, 'icon' => 'fas fa-ticket-alt']
];

$recentActivities = [
    ['action' => 'New user registered', 'user' => 'Alice Johnson', 'time' => '2 minutes ago', 'icon' => 'fas fa-user-plus'],
    ['action' => 'Support ticket resolved', 'user' => 'Bob Smith', 'time' => '15 minutes ago', 'icon' => 'fas fa-check-circle'],
    ['action' => 'New order placed', 'user' => 'Charlie Brown', 'time' => '1 hour ago', 'icon' => 'fas fa-shopping-cart'],
    ['action' => 'Payment received', 'user' => 'David Wilson', 'time' => '3 hours ago', 'icon' => 'fas fa-dollar-sign'],
    ['action' => 'New blog post published', 'user' => 'Eva Martinez', 'time' => '5 hours ago', 'icon' => 'fas fa-blog']
];

$todoList = [
    ['task' => 'Review new user applications', 'priority' => 'high'],
    ['task' => 'Prepare monthly report', 'priority' => 'medium'],
    ['task' => 'Update privacy policy', 'priority' => 'low'],
    ['task' => 'Schedule team meeting', 'priority' => 'medium']
];

// Dummy data for charts
$monthlyRevenue = [
    ['month' => 'Jan', 'revenue' => 12000],
    ['month' => 'Feb', 'revenue' => 15000],
    ['month' => 'Mar', 'revenue' => 18000],
    ['month' => 'Apr', 'revenue' => 16000],
    ['month' => 'May', 'revenue' => 21000],
    ['month' => 'Jun', 'revenue' => 22000]
];

$userTypes = [
    ['type' => 'Premium', 'count' => 3500],
    ['type' => 'Standard', 'count' => 7500],
    ['type' => 'Basic', 'count' => 4234]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #2952ff;
            --secondary-color: #2ecc71;
            --background-color: #f4f6f9;
            --text-color: #333;
            --card-background: #ffffff;
            --sidebar-color: #2952ff;
            --hover-color: #e74c3c;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-color);
            color: white;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
            text-align: center;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            margin-bottom: 15px;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar ul li a i {
            margin-right: 10px;
            font-size: 18px;
        }
        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background-color: var(--card-background);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }
        .user-details h3 {
            margin: 0;
            font-size: 18px;
        }
        .user-details small {
            color: #666;
        }
        .logout-btn {
            background-color: var(--hover-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: var(--card-background);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .stat-icon {
            font-size: 36px;
            color: var(--primary-color);
        }
        .stat-details h3 {
            font-size: 16px;
            margin: 0 0 5px 0;
            color: #666;
        }
        .stat-details .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        .stat-details .stat-increase {
            font-size: 14px;
            color: var(--secondary-color);
        }
        .stat-details .stat-decrease {
            font-size: 14px;
            color: var(--hover-color);
        }
        .chart-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .chart-card {
            background-color: var(--card-background);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1;
        }
        .recent-activity, .todo-list {
            background-color: var(--card-background);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .activity-item, .todo-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .activity-item:last-child, .todo-item:last-child {
            border-bottom: none;
        }
        .activity-icon, .todo-icon {
            margin-right: 15px;
            font-size: 20px;
            color: var(--primary-color);
        }
        .activity-details, .todo-details {
            flex: 1;
        }
        .activity-details strong, .todo-details strong {
            display: block;
            margin-bottom: 5px;
        }
        .activity-time, .todo-priority {
            font-size: 12px;
            color: #666;
        }
        .todo-priority.high { color: var(--hover-color); }
        .todo-priority.medium { color: #f39c12; }
        .todo-priority.low { color: var(--secondary-color); }
        .todo-actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
            color: #666;
            transition: color 0.3s ease;
        }
        .todo-actions button:hover {
            color: var(--primary-color);
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                order: 2;
            }
            .main-content {
                order: 1;
            }
            .chart-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <button class="sidebar-toggle" id="sidebarToggle">☰</button>
            <h2>Dashboard</h2>
            <nav>
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i> <span>Home</span></a></li>
                    <li><a href="#"><i class="fas fa-chart-bar"></i> <span>Analytics</span></a></li>
                    <li><a href="#"><i class="fas fa-users"></i> <span>Users</span></a></li>
                    <li><a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                    <li><a href="#"><i class="fas fa-question-circle"></i> <span>Help</span></a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="header">
                <div class="user-info">
                    <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="User Avatar">
                    <div class="user-details">
                        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                        <small><?php echo htmlspecialchars($user['role']); ?></small>
                    </div>
                </div>
                <form action="logout.php" method="post">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </header>

            <section class="stats-grid">
                <?php foreach ($stats as $stat): ?>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="<?php echo $stat['icon']; ?>"></i>
                        </div>
                        <div class="stat-details">
                            <h3><?php echo htmlspecialchars($stat['title']); ?></h3>
                            <p class="stat-value"><?php echo number_format($stat['value']); ?></p>
                            <p class="<?php echo $stat['increase'] >= 0 ? 'stat-increase' : 'stat-decrease'; ?>">
                                <?php echo $stat['increase'] >= 0 ? '↑' : '↓'; ?> <?php echo abs($stat['increase']); ?>%
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

            <section class="chart-container">
                <div class="chart-card">
                    <h3>Monthly Revenue</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3>User Distribution</h3>
                    <canvas id="userPieChart"></canvas>
                </div>
            </section>

            <section class="recent-activity">
                <h2>Recent Activity</h2>
                <?php foreach ($recentActivities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="<?php echo $activity['icon']; ?>"></i>
                        </div>
                        <div class="activity-details">
                            <strong><?php echo htmlspecialchars($activity['action']); ?></strong>
                            <span>by <?php echo htmlspecialchars($activity['user']); ?></span>
                            <small class="activity-time"><?php echo htmlspecialchars($activity['time']); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

            <section class="todo-list">
                <h2>To-Do List</h2>
                <?php foreach ($todoList as $index => $todo): ?>
                    <div class="todo-item">
                        <div class="todo-icon">
                        <i class="fas fa-tasks"></i>
                        </div>
                        <div class="todo-details">
                            <strong><?php echo htmlspecialchars($todo['task']); ?></strong>
                            <span class="todo-priority <?php echo $todo['priority']; ?>">
                                Priority: <?php echo htmlspecialchars(ucfirst($todo['priority'])); ?>
                            </span>
                        </div>
                        <div class="todo-actions">
                            <button>✔️</button>
                            <button>❌</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>
        </main>
    </div>

    <script>
        // Chart.js implementation for Monthly Revenue
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($monthlyRevenue, 'month')); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode(array_column($monthlyRevenue, 'revenue')); ?>,
                    borderColor: 'var(--primary-color)',
                    backgroundColor: 'rgba(52, 152, 219, 0.2)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart.js implementation for User Distribution
        const userCtx = document.getElementById('userPieChart').getContext('2d');
        const userChart = new Chart(userCtx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode(array_column($userTypes, 'type')); ?>,
                datasets: [{
                    label: 'User Types',
                    data: <?php echo json_encode(array_column($userTypes, 'count')); ?>,
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.5)',
                        'rgba(46, 204, 113, 0.5)',
                        'rgba(241, 196, 15, 0.5)'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>
</body>
</html>
