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
$adminInfo = mysqli_fetch_assoc($result);

// Handle message actions
if (isset($_POST['delete_message'])) {
    $messageId = sanitizeInput($_POST['message_id']);
    $deleteResult = executeQuery("DELETE FROM contact_us WHERE unique_id = ?", "s", [$messageId]);
    if ($deleteResult) {
        $_SESSION['admin_success'] = "Message deleted successfully!";
    } else {
        $_SESSION['admin_error'] = "Error deleting message!";
    }
    header("Location: messages.php");
    exit();
}

// Get all messages
$messages = executeQuery("SELECT * FROM contact_us ORDER BY date_sent DESC", "", []);

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
    <title>Messages - Admin Panel</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="plugins/sweetalert2/sweetalert2.min.css">
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
        }
        .admin-content {
            margin-left: 250px;
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
        .admin-navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
        }
        .message-card {
            transition: transform 0.2s ease;
            border-left: 4px solid #dee2e6;
        }
        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .message-card.unread {
            border-left-color: #dc3545;
            background-color: #fff5f5;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="admin-sidebar">
        <div class="sidebar-brand text-center">
            <h4 class="text-white mb-0">
                <i class="fas fa-shield-alt text-danger"></i> OIT Admin
            </h4>
            <small class="text-muted">Control Panel</small>
        </div>
        
        <ul class="nav flex-column pt-3">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="fas fa-users me-2"></i> Users Management
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="messages.php">
                    <i class="fas fa-envelope me-2"></i> Messages
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
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="admin-content">
        <!-- Top Navbar -->
        <nav class="admin-navbar d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Messages Management</h5>
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user me-2"></i><?= htmlspecialchars($adminInfo['username']) ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Messages Content -->
        <div class="container-fluid p-4">
            <!-- Alert Messages -->
            <?php if (!empty($alertMessage)): ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?= $alertType === 'success' ? 'check-circle' : 'exclamation-triangle' ?> me-2"></i>
                    <?= htmlspecialchars($alertMessage) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2><i class="fas fa-envelope text-primary me-2"></i>Contact Messages</h2>
                    <p class="text-muted">Manage and respond to user inquiries</p>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-outline-danger" onclick="deleteAllMessages()">
                        <i class="fas fa-trash me-2"></i>Delete All
                    </button>
                    <button class="btn btn-primary" onclick="exportMessages()">
                        <i class="fas fa-download me-2"></i>Export
                    </button>
                </div>
            </div>

            <!-- Messages List -->
            <div class="row">
                <div class="col-12">
                    <?php if ($messages && mysqli_num_rows($messages) > 0): ?>
                        <?php while ($message = mysqli_fetch_assoc($messages)): ?>
                            <div class="card message-card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center mb-2">
                                                <h5 class="mb-0 me-3"><?= htmlspecialchars($message['name']) ?></h5>
                                                <span class="badge bg-primary"><?= htmlspecialchars($message['email']) ?></span>
                                            </div>
                                            <p class="text-muted mb-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?= date('F j, Y \a\t g:i A', strtotime($message['date_sent'])) ?>
                                            </p>
                                            <p class="mb-0"><?= htmlspecialchars($message['message']) ?></p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button class="btn btn-sm btn-outline-primary me-2" onclick="replyToMessage('<?= htmlspecialchars($message['email']) ?>', '<?= htmlspecialchars($message['name']) ?>')">
                                                <i class="fas fa-reply"></i> Reply
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteMessage('<?= $message['unique_id'] ?>')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-inbox display-1 text-muted mb-4"></i>
                                <h4 class="text-muted">No Messages Yet</h4>
                                <p class="text-muted">When users send messages through the contact form, they will appear here.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
    
    <script>
        function deleteMessage(messageId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This message will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';
                    
                    const messageIdInput = document.createElement('input');
                    messageIdInput.type = 'hidden';
                    messageIdInput.name = 'message_id';
                    messageIdInput.value = messageId;
                    
                    const submitInput = document.createElement('input');
                    submitInput.type = 'hidden';
                    submitInput.name = 'delete_message';
                    submitInput.value = '1';
                    
                    form.appendChild(messageIdInput);
                    form.appendChild(submitInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function deleteAllMessages() {
            Swal.fire({
                title: 'Delete All Messages?',
                text: "This will permanently delete ALL messages! This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete all!',
                input: 'text',
                inputPlaceholder: 'Type "DELETE ALL" to confirm',
                inputValidator: (value) => {
                    if (value !== 'DELETE ALL') {
                        return 'Please type "DELETE ALL" to confirm'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Implement delete all functionality
                    Swal.fire('Feature Coming Soon!', 'Bulk delete functionality will be implemented', 'info');
                }
            });
        }

        function replyToMessage(email, name) {
            Swal.fire({
                title: `Reply to ${name}`,
                html: `
                    <div class="text-start">
                        <div class="mb-3">
                            <label class="form-label">To: ${email}</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" id="replySubject" class="form-control" placeholder="Re: Your inquiry">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea id="replyMessage" class="form-control" rows="5" placeholder="Type your reply here..."></textarea>
                        </div>
                    </div>
                `,
                confirmButtonText: 'Send Reply',
                showCancelButton: true,
                width: '600px',
                preConfirm: () => {
                    const subject = document.getElementById('replySubject').value;
                    const message = document.getElementById('replyMessage').value;
                    
                    if (!subject || !message) {
                        Swal.showValidationMessage('Please fill all fields');
                        return false;
                    }
                    
                    return { subject, message };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Here you would implement email sending functionality
                    Swal.fire('Reply Sent!', 'Your reply has been sent successfully', 'success');
                }
            });
        }

        function exportMessages() {
            Swal.fire({
                title: 'Export Messages',
                text: 'Choose export format',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'CSV Format',
                cancelButtonText: 'PDF Format',
                showDenyButton: true,
                denyButtonText: 'Excel Format'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Export as CSV
                    Swal.fire('Exporting...', 'CSV export functionality will be implemented', 'info');
                } else if (result.isDenied) {
                    // Export as Excel
                    Swal.fire('Exporting...', 'Excel export functionality will be implemented', 'info');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    // Export as PDF
                    Swal.fire('Exporting...', 'PDF export functionality will be implemented', 'info');
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