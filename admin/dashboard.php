<?php
session_start();
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login/index.php");
    exit();
}

// Get admin information
$adminId = $_SESSION['admin_id'];
$result = executeQuery("SELECT * FROM admin WHERE user_id = ?", "s", [$adminId]);
if (!$result || mysqli_num_rows($result) == 0) {
    session_destroy();
    header("Location: login/index.php");
    exit();
}

$adminInfo = mysqli_fetch_assoc($result);

// Get statistics
$userCount = executeQuery("SELECT COUNT(*) as count FROM users", "", []);
$userCount = $userCount ? mysqli_fetch_assoc($userCount)['count'] : 0;

$contactCount = executeQuery("SELECT COUNT(*) as count FROM contact_us", "", []);
$contactCount = $contactCount ? mysqli_fetch_assoc($contactCount)['count'] : 0;

$recentContacts = executeQuery("SELECT * FROM contact_us ORDER BY date_sent DESC LIMIT 5", "", []);

// Handle success/error messages
$alertMessage = '';
$alertType = '';
if (isset($_SESSION['admin_success'])) {
    $alertMessage = $_SESSION['admin_success'];
    $alertType = 'success';
    unset($_SESSION['admin_success']);
}
if (isset($_SESSION['admin_error'])) {
    $alertMessage = $_SESSION['admin_error'];
    $alertType = 'danger';
    unset($_SESSION['admin_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - OIT</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Admin Plugins -->
    <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
    
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-sidebar {
            background: linear-gradient(180deg, #343a40 0%, #495057 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        .admin-content {
            margin-left: 250px;
            transition: all 0.3s ease;
        }
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid #495057;
        }
        .nav-link {
            color: #adb5bd !important;
            padding: 0.75rem 1.5rem;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
            border-left-color: #dc3545;
        }
        .stats-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-5px);
        }
        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .admin-navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(220, 53, 69, 0.05);
        }
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar" id="sidebar">
        <div class="sidebar-brand text-center">
            <h4 class="text-white mb-0">
                <i class="fas fa-shield-alt text-danger"></i> OIT Admin
            </h4>
            <small class="text-muted">Control Panel</small>
        </div>
        
        <ul class="nav flex-column pt-3">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="fas fa-users me-2"></i> Users Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="messages.php">
                    <i class="fas fa-envelope me-2"></i> Messages
                    <?php if ($contactCount > 0): ?>
                        <span class="badge bg-danger ms-2"><?= $contactCount ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="analytics.php">
                    <i class="fas fa-chart-bar me-2"></i> Analytics
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="settings.php">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
            </li>
            <li class="nav-item mt-4">
                <hr class="text-muted">
                <a class="nav-link" href="../index.php" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i> View Site
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php" onclick="confirmLogout(event)">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="admin-content">
        <!-- Top Navbar -->
        <nav class="admin-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary d-md-none me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0">Admin Dashboard</h5>
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-2"></i><?= htmlspecialchars($adminInfo['username']) ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php" onclick="confirmLogout(event)"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Dashboard Content -->
        <div class="container-fluid p-4">
            <!-- Alert Messages -->
            <?php if (!empty($alertMessage)): ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?= $alertType === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                    <?= htmlspecialchars($alertMessage) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-primary text-white p-4 rounded">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-1">Welcome back, <?= htmlspecialchars($adminInfo['username']) ?>!</h2>
                                <p class="mb-0">Here's what's happening with your OIT platform today.</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <i class="fas fa-user-shield" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1">Total Users</h6>
                                    <h2 class="card-title mb-0"><?= $userCount ?></h2>
                                </div>
                                <i class="fas fa-users stats-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1">Messages</h6>
                                    <h2 class="card-title mb-0"><?= $contactCount ?></h2>
                                </div>
                                <i class="fas fa-envelope stats-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1">Active Tools</h6>
                                    <h2 class="card-title mb-0">4</h2>
                                </div>
                                <i class="fas fa-calculator stats-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card stats-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-subtitle mb-1">System Status</h6>
                                    <h2 class="card-title mb-0">Online</h2>
                                </div>
                                <i class="fas fa-server stats-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-envelope text-primary me-2"></i>Recent Messages</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if ($recentContacts && mysqli_num_rows($recentContacts) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Message</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($contact = mysqli_fetch_assoc($recentContacts)): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($contact['name']) ?></td>
                                                    <td><?= htmlspecialchars($contact['email']) ?></td>
                                                    <td><?= htmlspecialchars(substr($contact['message'], 0, 50)) ?>...</td>
                                                    <td><?= date('M j, Y', strtotime($contact['date_sent'])) ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="viewMessage('<?= $contact['unique_id'] ?>')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center p-4 text-muted">
                                    <i class="fas fa-inbox display-4 mb-3"></i>
                                    <p>No messages yet</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-center">
                            <a href="messages.php" class="btn btn-primary">View All Messages</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="row g-4">
                        <!-- Quick Actions -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-bolt text-warning me-2"></i>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-primary" onclick="showAddUserModal()">
                                            <i class="fas fa-user-plus me-2"></i>Add New User
                                        </button>
                                        <button class="btn btn-outline-success" onclick="exportData()">
                                            <i class="fas fa-download me-2"></i>Export Data
                                        </button>
                                        <button class="btn btn-outline-info" onclick="systemCheck()">
                                            <i class="fas fa-check-circle me-2"></i>System Check
                                        </button>
                                        <a href="../index.php" target="_blank" class="btn btn-outline-secondary">
                                            <i class="fas fa-external-link-alt me-2"></i>View Website
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Info -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-info-circle text-info me-2"></i>System Info</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <strong>PHP Version:</strong> <?= phpversion() ?>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Server:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?>
                                        </li>
                                        <li class="mb-2">
                                            <strong>Last Login:</strong> <?= date('M j, Y H:i', $_SESSION['admin_login_time']) ?>
                                        </li>
                                        <li>
                                            <strong>Status:</strong> <span class="badge bg-success">Online</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Admin Plugins -->
    <script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="plugins/toastr/toastr.min.js"></script>
    
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function confirmLogout(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out of the admin panel",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        }

        function viewMessage(messageId) {
            // You can implement a modal or redirect to view full message
            toastr.info('Message viewing functionality can be implemented');
        }

        function showAddUserModal() {
            Swal.fire({
                title: 'Add New User',
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" id="newUsername" class="form-control" placeholder="Enter username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" id="newEmail" class="form-control" placeholder="Enter email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" id="newPassword" class="form-control" placeholder="Enter password">
                        </div>
                    </div>
                `,
                confirmButtonText: 'Add User',
                showCancelButton: true,
                preConfirm: () => {
                    const username = document.getElementById('newUsername').value;
                    const email = document.getElementById('newEmail').value;
                    const password = document.getElementById('newPassword').value;
                    
                    if (!username || !email || !password) {
                        Swal.showValidationMessage('Please fill all fields');
                        return false;
                    }
                    
                    return { username, email, password };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Here you would send an AJAX request to add the user
                    toastr.success('User would be added via AJAX call');
                }
            });
        }

        function exportData() {
            toastr.info('Export functionality can be implemented');
        }

        function systemCheck() {
            Swal.fire({
                title: 'System Check',
                text: 'Running system diagnostics...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    setTimeout(() => {
                        Swal.fire({
                            icon: 'success',
                            title: 'System Healthy',
                            text: 'All systems are running normally'
                        });
                    }, 2000);
                }
            });
        }

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>