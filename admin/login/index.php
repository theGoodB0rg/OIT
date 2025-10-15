<?php
session_start();

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: ../dashboard.php");
    exit();
}

// Handle alert messages
$alertMessage = '';
if (isset($_SESSION['admin_error'])) {
    $alertMessage = $_SESSION['admin_error'];
    unset($_SESSION['admin_error']);
}
if (isset($_SESSION['admin_success'])) {
    $alertMessage = $_SESSION['admin_success'];
    unset($_SESSION['admin_success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - OIT</title>
    <link rel="shortcut icon" href="../../images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .admin-login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .login-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border-radius: 20px 20px 0 0;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
        .btn-admin {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        .admin-icon {
            font-size: 4rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card admin-login-card border-0">
                    <div class="card-header login-header text-center py-4">
                        <h3 class="mb-0"><i class="fas fa-shield-alt"></i> Admin Panel</h3>
                        <p class="mb-0 mt-2">OIT Administration</p>
                    </div>
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-user-shield admin-icon"></i>
                            <h4>Administrator Login</h4>
                            <p class="text-muted">Enter your admin credentials to access the control panel</p>
                        </div>

                        <?php if (!empty($alertMessage)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?= htmlspecialchars($alertMessage) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="backend/admin_login.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Username
                                </label>
                                <input type="text" class="form-control" id="username" name="username" required 
                                       placeholder="Enter admin username">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required 
                                           placeholder="Enter admin password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            <button type="submit" class="btn btn-admin btn-danger w-100 mb-3" name="admin_login">
                                <i class="fas fa-sign-in-alt me-2"></i>Login to Admin Panel
                            </button>
                        </form>

                        <div class="text-center">
                            <hr class="my-4">
                            <div class="d-flex justify-content-between">
                                <a href="../../index.php" class="text-decoration-none text-muted">
                                    <i class="fas fa-home me-1"></i>Back to Home
                                </a>
                                <a href="../../login/index.php" class="text-decoration-none text-muted">
                                    <i class="fas fa-user me-1"></i>User Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="text-center mt-3">
                    <small class="text-white">
                        <i class="fas fa-shield-alt me-1"></i>
                        This is a secure admin area. All login attempts are logged.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Add loading state to login button
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = document.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging in...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>