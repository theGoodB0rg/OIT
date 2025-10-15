<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['u_log'])) {
    header("Location: login/index.php");
    exit();
}

// Get user information
$userId = $_SESSION['u_log'];
$result = executeQuery("SELECT * FROM users WHERE user_id = ?", "s", [$userId]);
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: logout.php");
    exit();
}

$userInfo = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - OIT</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .tool-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
        .quick-access {
            margin-top: -30px;
            position: relative;
            z-index: 10;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-graduation-cap"></i> OIT Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="toolsDropdown" role="button" data-bs-toggle="dropdown">
                            Tools
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="tools/quadratic-solver.php">Quadratic Solver</a></li>
                            <li><a class="dropdown-item" href="tools/calculator.php">Calculator</a></li>
                            <li><a class="dropdown-item" href="tools/percentage-calculator.php">Percentage Calculator</a></li>
                            <li><a class="dropdown-item" href="tools/geometry-helper.php">Geometry Helper</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($userInfo['uname']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Welcome back, <?= htmlspecialchars($userInfo['uname']) ?>!</h1>
                    <p class="lead">Ready to explore and learn? Choose from our educational tools below.</p>
                    <p class="mb-0"><i class="fas fa-calendar-alt"></i> Member since: <?= date('F j, Y', strtotime($userInfo['reg_date'])) ?></p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-user-graduate display-1"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Access Tools -->
    <section class="quick-access">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card dashboard-card h-100 text-center p-3 bg-white">
                        <div class="card-body">
                            <i class="fas fa-square-root-alt tool-icon text-primary"></i>
                            <h6 class="card-title">Quadratic Solver</h6>
                            <a href="tools/quadratic-solver.php" class="btn btn-sm btn-primary">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card dashboard-card h-100 text-center p-3 bg-white">
                        <div class="card-body">
                            <i class="fas fa-calculator tool-icon text-success"></i>
                            <h6 class="card-title">Calculator</h6>
                            <a href="tools/calculator.php" class="btn btn-sm btn-success">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card dashboard-card h-100 text-center p-3 bg-white">
                        <div class="card-body">
                            <i class="fas fa-percentage tool-icon text-warning"></i>
                            <h6 class="card-title">Percentage Calc</h6>
                            <a href="tools/percentage-calculator.php" class="btn btn-sm btn-warning">Open</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card dashboard-card h-100 text-center p-3 bg-white">
                        <div class="card-body">
                            <i class="fas fa-shapes tool-icon text-info"></i>
                            <h6 class="card-title">Geometry</h6>
                            <a href="tools/geometry-helper.php" class="btn btn-sm btn-info">Open</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Dashboard Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Mathematical Tools -->
                <div class="col-lg-8">
                    <h3 class="mb-4"><i class="fas fa-calculator"></i> Mathematical Tools</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card dashboard-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-square-root-alt text-primary me-3" style="font-size: 2rem;"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Quadratic Equation Solver</h5>
                                            <small class="text-muted">Solve ax² + bx + c = 0</small>
                                        </div>
                                    </div>
                                    <p class="card-text">Solve quadratic equations using multiple methods including factoring, completing the square, and quadratic formula.</p>
                                    <a href="tools/quadratic-solver.php" class="btn btn-primary">Launch Tool</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card dashboard-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-chart-line text-success me-3" style="font-size: 2rem;"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Function Grapher</h5>
                                            <small class="text-muted">Visualize functions</small>
                                        </div>
                                    </div>
                                    <p class="card-text">Plot mathematical functions and analyze their behavior with our interactive graphing tool.</p>
                                    <a href="tools/function-grapher.php" class="btn btn-success">Launch Tool</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card dashboard-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-percentage text-warning me-3" style="font-size: 2rem;"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Percentage Calculator</h5>
                                            <small class="text-muted">Calculate percentages</small>
                                        </div>
                                    </div>
                                    <p class="card-text">Calculate percentages, percentage changes, discounts, and percentage of numbers easily.</p>
                                    <a href="tools/percentage-calculator.php" class="btn btn-warning">Launch Tool</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card dashboard-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-shapes text-info me-3" style="font-size: 2rem;"></i>
                                        <div>
                                            <h5 class="card-title mb-1">Geometry Helper</h5>
                                            <small class="text-muted">Areas, perimeters, volumes</small>
                                        </div>
                                    </div>
                                    <p class="card-text">Calculate areas, perimeters, and volumes of various geometric shapes with step-by-step solutions.</p>
                                    <a href="tools/geometry-helper.php" class="btn btn-info">Launch Tool</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="row g-4">
                        <!-- Quick Stats -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Your Activity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Tools Used</span>
                                        <strong class="text-primary">0</strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span>Problems Solved</span>
                                        <strong class="text-success">0</strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Study Sessions</span>
                                        <strong class="text-info">0</strong>
                                    </div>
                                    <hr>
                                    <small class="text-muted">Start using tools to see your progress!</small>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-history"></i> Recent Activity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-clock display-6 mb-3"></i>
                                        <p>No recent activity</p>
                                        <small>Your recent tool usage will appear here</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Study Resources -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-book"></i> Study Resources</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <a href="resources/formulas.php" class="text-decoration-none">
                                                <i class="fas fa-formula text-primary me-2"></i>Mathematical Formulas
                                            </a>
                                        </li>
                                        <li class="mb-2">
                                            <a href="resources/tutorials.php" class="text-decoration-none">
                                                <i class="fas fa-play-circle text-success me-2"></i>Video Tutorials
                                            </a>
                                        </li>
                                        <li>
                                            <a href="resources/practice.php" class="text-decoration-none">
                                                <i class="fas fa-pencil-alt text-warning me-2"></i>Practice Problems
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-graduation-cap"></i> OIT Dashboard</h6>
                    <p class="mb-0">Your educational companion for mathematical learning.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?= date('Y') ?> OIT. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add some interactivity
        document.querySelectorAll('.dashboard-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.borderLeft = '4px solid #667eea';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.borderLeft = 'none';
            });
        });
    </script>
</body>
</html>