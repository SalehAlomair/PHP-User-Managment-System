<?php
$conn = new mysqli("localhost", "root", "", "toggle_app");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"]) && isset($_POST["age"])) {
    $name = $conn->real_escape_string($_POST["name"]);
    $age = intval($_POST["age"]);
    $conn->query("INSERT INTO users (name, age) VALUES ('$name', $age)");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["toggle_id"])) {
    $id = intval($_POST["toggle_id"]);
    $conn->query("UPDATE users SET status = 1 - status WHERE id = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $id = intval($_POST["delete_id"]);
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        :root {
            --primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-solid: #667eea;
            --secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --danger: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            --bg-primary: #0a0a0a;
            --bg-secondary: #1a1a1a;
            --bg-tertiary: #2a2a2a;
            --bg-card: rgba(255, 255, 255, 0.05);
            --text-primary: #ffffff;
            --text-secondary: #b0b0b0;
            --text-muted: #707070;
            --border: rgba(255, 255, 255, 0.1);
            --shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --shadow-lg: 0 35px 60px -12px rgba(0, 0, 0, 0.3);
            --blur: blur(20px);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(118, 75, 162, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(240, 147, 251, 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            padding: 40px 0;
        }

        .header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 15px;
            text-shadow: 0 0 30px rgba(102, 126, 234, 0.3);
        }

        .header p {
            font-size: 1.25rem;
            color: var(--text-secondary);
            font-weight: 300;
        }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: var(--blur);
            -webkit-backdrop-filter: var(--blur);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section-title i {
            width: 45px;
            height: 45px;
            background: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        /* Enhanced Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--bg-card);
            backdrop-filter: var(--blur);
            -webkit-backdrop-filter: var(--blur);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 35px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-lg);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
            line-height: 1;
        }

        .stat-label {
            font-size: 1rem;
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Enhanced Form Styles */
        .form-container {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 25px;
            align-items: end;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 18px 20px;
            border: 2px solid var(--border);
            border-radius: 16px;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            transition: all 0.3s ease;
            backdrop-filter: var(--blur);
            -webkit-backdrop-filter: var(--blur);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-solid);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        /* Enhanced Button Styles */
        .btn {
            padding: 18px 30px;
            border: none;
            border-radius: 16px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-align: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-sm {
            padding: 12px 20px;
            font-size: 0.9rem;
            border-radius: 12px;
        }

        .btn-success {
            background: var(--success);
            color: white;
            box-shadow: 0 8px 16px rgba(79, 172, 254, 0.3);
        }

        .btn-warning {
            background: var(--warning);
            color: #8B4513;
            box-shadow: 0 8px 16px rgba(255, 236, 210, 0.3);
        }

        .btn-danger {
            background: var(--danger);
            color: #8B0000;
            box-shadow: 0 8px 16px rgba(255, 154, 158, 0.3);
        }

        /* Enhanced Table Styles */
        .table-container {
            overflow-x: auto;
            border-radius: 20px;
            border: 1px solid var(--border);
            background: var(--bg-card);
            backdrop-filter: var(--blur);
            -webkit-backdrop-filter: var(--blur);
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modern-table th {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            font-weight: 700;
            padding: 20px;
            text-align: left;
            border-bottom: 2px solid var(--border);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .modern-table td {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
            transform: scale(1.01);
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Enhanced User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .user-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1.1rem;
        }

        /* Enhanced Status Badge */
        .status-badge {
            padding: 10px 18px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: var(--blur);
            -webkit-backdrop-filter: var(--blur);
        }

        .status-active {
            background: rgba(79, 172, 254, 0.2);
            color: #4facfe;
            border: 1px solid rgba(79, 172, 254, 0.3);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.2);
        }

        .status-inactive {
            background: rgba(255, 236, 210, 0.2);
            color: #fcb69f;
            border: 1px solid rgba(255, 236, 210, 0.3);
            box-shadow: 0 5px 15px rgba(255, 236, 210, 0.2);
        }

        /* Enhanced Action Buttons */
        .action-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        /* Enhanced Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            background: var(--primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 25px;
        }

        .empty-state h3 {
            font-size: 1.8rem;
            color: var(--text-secondary);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: var(--text-muted);
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
            
            .modern-table th,
            .modern-table td {
                padding: 15px 12px;
            }
            
            .header h1 {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding: 0 10px;
            }
            
            .glass-card {
                padding: 25px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="floating"><i class="fas fa-users"></i> User Management System</h1>
            <p>Manage your users with style and efficiency</p>
        </div>

        <?php
        $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
        $active_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE status = 1")->fetch_assoc()['count'];
        $inactive_users = $total_users - $active_users;
        ?>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $total_users ?></div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $active_users ?></div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $inactive_users ?></div>
                <div class="stat-label">Inactive Users</div>
            </div>
        </div>

        <div class="glass-card">
            <h2 class="section-title">
                <i class="fas fa-user-plus"></i>
                Add New User
            </h2>
            <form method="POST" class="form-container">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter full name">
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" required min="1" max="120" placeholder="Enter age">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add User
                </button>
            </form>
        </div>

        <div class="glass-card">
            <h2 class="section-title">
                <i class="fas fa-list"></i>
                User Management
            </h2>
            <div class="table-container">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Age</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($users && $users->num_rows > 0): ?>
                            <?php while ($row = $users->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?= $row["id"] ?></strong></td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <?= strtoupper(substr($row["name"], 0, 1)) ?>
                                        </div>
                                        <span class="user-name"><?= htmlspecialchars($row["name"]) ?></span>
                                    </div>
                                </td>
                                <td><?= $row["age"] ?> years</td>
                                <td>
                                    <span class="status-badge <?= $row["status"] ? 'status-active' : 'status-inactive' ?>">
                                        <i class="fas fa-<?= $row["status"] ? 'check-circle' : 'pause-circle' ?>"></i>
                                        <?= $row["status"] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="toggle_id" value="<?= $row["id"] ?>" />
                                            <button type="submit" class="btn btn-sm <?= $row["status"] ? 'btn-warning' : 'btn-success' ?>" title="Toggle Status">
                                                <i class="fas fa-<?= $row["status"] ? 'pause' : 'play' ?>"></i>
                                                <?= $row["status"] ? 'Deactivate' : 'Activate' ?>
                                            </button>
                                        </form>
                                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="delete_id" value="<?= $row["id"] ?>" />
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete User">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <h3>No users found</h3>
                                        <p>Add your first user using the form above!</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
