<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['u_log']);
$userInfo = null;

if ($isLoggedIn) {
    // Get user information
    $userId = $_SESSION['u_log'];
    $result = executeQuery("SELECT * FROM users WHERE user_id = ?", "s", [$userId]);
    if ($result && mysqli_num_rows($result) > 0) {
        $userInfo = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OIT - Online Educational Tool</title>
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }
        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .tool-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        body {
            padding-top: 76px; /* Account for fixed navbar */
        }
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
                text-align: center;
            }
            .hero-section h1 {
                font-size: 2.5rem;
            }
            .tool-icon {
                font-size: 2.5rem;
            }
            body {
                padding-top: 60px;
            }
        }
        @media (max-width: 576px) {
            .hero-section h1 {
                font-size: 2rem;
            }
            .hero-section {
                padding: 40px 0;
            }
        }
        .card-header {
            border-bottom: none;
        }
        .guide-card {
            transition: all 0.3s ease;
        }
        .guide-card:hover {
            transform: translateY(-3px);
        }
    </style>
</head>
<body class="landing-page">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap"></i> OIT</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tools">Tools</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#guides">Learning Guides</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($userInfo['uname']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-edit"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login/index.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login/register.php"><i class="fas fa-user-plus"></i> Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Online Educational Tool</h1>
                    <p class="lead mb-4">Master mathematics and explore educational content with our comprehensive online learning platform. Solve equations, learn concepts, and enhance your academic journey.</p>
                    <?php if (!$isLoggedIn): ?>
                        <div class="d-flex gap-3">
                            <a href="login/register.php" class="btn btn-light btn-lg">Get Started</a>
                            <a href="#tools" class="btn btn-outline-light btn-lg">Explore Tools</a>
                        </div>
                    <?php else: ?>
                        <div class="d-flex gap-3">
                            <a href="dashboard.php" class="btn btn-light btn-lg">Go to Dashboard</a>
                            <a href="#tools" class="btn btn-outline-light btn-lg">Browse Tools</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-calculator display-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Educational Tools Section -->
    <section id="tools" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Educational Tools</h2>
                <p class="lead">Explore our comprehensive suite of learning tools</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-square-root-alt tool-icon"></i>
                            <h5 class="card-title">Quadratic Equation Solver</h5>
                            <p class="card-text">Solve quadratic equations step by step with detailed explanations and multiple methods.</p>
                            <a href="<?= $isLoggedIn ? 'tools/quadratic-solver.php' : 'login/index.php' ?>" class="btn btn-primary">Try Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-chart-line tool-icon"></i>
                            <h5 class="card-title">Function Grapher</h5>
                            <p class="card-text">Visualize mathematical functions with our interactive graphing tool.</p>
                            <a href="<?= $isLoggedIn ? 'tools/function-grapher.php' : 'login/index.php' ?>" class="btn btn-primary">Try Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-calculator tool-icon"></i>
                            <h5 class="card-title">Basic Calculator</h5>
                            <p class="card-text">Perform basic arithmetic operations with our user-friendly calculator.</p>
                            <a href="<?= $isLoggedIn ? 'tools/calculator.php' : 'login/index.php' ?>" class="btn btn-primary">Try Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-percentage tool-icon"></i>
                            <h5 class="card-title">Percentage Calculator</h5>
                            <p class="card-text">Calculate percentages, percentage changes, and percentage of numbers easily.</p>
                            <a href="<?= $isLoggedIn ? 'tools/percentage-calculator.php' : 'login/index.php' ?>" class="btn btn-primary">Try Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-shapes tool-icon"></i>
                            <h5 class="card-title">Geometry Helper</h5>
                            <p class="card-text">Calculate areas, perimeters, and volumes of various geometric shapes.</p>
                            <a href="<?= $isLoggedIn ? 'tools/geometry-helper.php' : 'login/index.php' ?>" class="btn btn-primary">Try Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card h-100 text-center p-4">
                        <div class="card-body">
                            <i class="fas fa-book tool-icon"></i>
                            <h5 class="card-title">Study Materials</h5>
                            <p class="card-text">Access curated study materials, formulas, and reference guides.</p>
                            <a href="<?= $isLoggedIn ? 'study-materials.php' : 'login/index.php' ?>" class="btn btn-primary">Explore</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Guides Section -->
    <section id="guides" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Step-by-Step Learning Guides</h2>
                <p class="lead">Master mathematical concepts with our detailed explanations and examples</p>
            </div>
            
            <div class="row g-4">
                <!-- Quadratic Equations Guide -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-square-root-alt"></i> Quadratic Equations</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="text-primary">What you'll learn:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Understanding the standard form ax² + bx + c = 0</li>
                                <li><i class="fas fa-check text-success"></i> Using the quadratic formula effectively</li>
                                <li><i class="fas fa-check text-success"></i> Completing the square method</li>
                                <li><i class="fas fa-check text-success"></i> Factoring quadratic expressions</li>
                                <li><i class="fas fa-check text-success"></i> Interpreting discriminant values</li>
                            </ul>
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <h6><i class="fas fa-lightbulb"></i> Quick Example:</h6>
                                <p class="mb-2"><strong>Problem:</strong> Solve x² - 5x + 6 = 0</p>
                                <p class="mb-2"><strong>Step 1:</strong> Identify a=1, b=-5, c=6</p>
                                <p class="mb-2"><strong>Step 2:</strong> Apply formula: x = (5 ± √(25-24))/2</p>
                                <p class="mb-0"><strong>Answer:</strong> x = 2 or x = 3</p>
                            </div>
                            
                            <a href="<?= $isLoggedIn ? 'tools/quadratic-solver.php' : 'login/index.php' ?>" class="btn btn-primary">
                                <i class="fas fa-play"></i> Try Interactive Solver
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Percentage Calculations Guide -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-percentage"></i> Percentage Calculations</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="text-success">What you'll learn:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Finding percentages of numbers</li>
                                <li><i class="fas fa-check text-success"></i> Calculating percentage increases/decreases</li>
                                <li><i class="fas fa-check text-success"></i> Finding what percent one number is of another</li>
                                <li><i class="fas fa-check text-success"></i> Real-world applications (discounts, tips, taxes)</li>
                                <li><i class="fas fa-check text-success"></i> Converting between fractions, decimals, and percentages</li>
                            </ul>
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <h6><i class="fas fa-lightbulb"></i> Quick Example:</h6>
                                <p class="mb-2"><strong>Problem:</strong> What is 15% of 80?</p>
                                <p class="mb-2"><strong>Step 1:</strong> Convert 15% to decimal: 15% = 0.15</p>
                                <p class="mb-2"><strong>Step 2:</strong> Multiply: 0.15 × 80</p>
                                <p class="mb-0"><strong>Answer:</strong> 12</p>
                            </div>
                            
                            <a href="<?= $isLoggedIn ? 'tools/percentage-calculator.php' : 'login/index.php' ?>" class="btn btn-success">
                                <i class="fas fa-play"></i> Try Interactive Calculator
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Geometry Guide -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-shapes"></i> Geometry Fundamentals</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="text-warning">What you'll learn:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Area formulas for basic shapes</li>
                                <li><i class="fas fa-check text-success"></i> Perimeter and circumference calculations</li>
                                <li><i class="fas fa-check text-success"></i> Volume formulas for 3D shapes</li>
                                <li><i class="fas fa-check text-success"></i> Pythagorean theorem applications</li>
                                <li><i class="fas fa-check text-success"></i> Surface area calculations</li>
                            </ul>
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <h6><i class="fas fa-lightbulb"></i> Quick Example:</h6>
                                <p class="mb-2"><strong>Problem:</strong> Find area of circle with radius 5</p>
                                <p class="mb-2"><strong>Step 1:</strong> Use formula A = πr²</p>
                                <p class="mb-2"><strong>Step 2:</strong> A = π × 5² = 25π</p>
                                <p class="mb-0"><strong>Answer:</strong> ≈ 78.54 square units</p>
                            </div>
                            
                            <a href="<?= $isLoggedIn ? 'tools/geometry-helper.php' : 'login/index.php' ?>" class="btn btn-warning">
                                <i class="fas fa-play"></i> Try Interactive Helper
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Study Tips Guide -->
                <div class="col-lg-6">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-brain"></i> Effective Study Techniques</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="text-info">Study strategies:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Practice problems regularly</li>
                                <li><i class="fas fa-check text-success"></i> Break complex problems into steps</li>
                                <li><i class="fas fa-check text-success"></i> Understand concepts, don't just memorize</li>
                                <li><i class="fas fa-check text-success"></i> Use visual aids and diagrams</li>
                                <li><i class="fas fa-check text-success"></i> Apply math to real-world scenarios</li>
                            </ul>
                            
                            <div class="bg-light p-3 rounded mb-3">
                                <h6><i class="fas fa-trophy"></i> Pro Tip:</h6>
                                <p class="mb-0">Always check your answers by substituting back into the original equation. This helps catch calculation errors and builds confidence!</p>
                            </div>
                            
                            <a href="<?= $isLoggedIn ? 'study-tips.php' : 'login/index.php' ?>" class="btn btn-info">
                                <i class="fas fa-book-open"></i> More Study Resources
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Interactive Features Highlight -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h3 class="fw-bold mb-3">
                                        <i class="fas fa-magic text-primary"></i> 
                                        Interactive Learning Experience
                                    </h3>
                                    <p class="lead mb-3">
                                        Our platform provides step-by-step solutions with detailed explanations for every mathematical concept. 
                                        Each tool includes interactive examples, multiple solution methods, and real-time feedback.
                                    </p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check-circle text-success"></i> Instant step-by-step solutions</li>
                                                <li><i class="fas fa-check-circle text-success"></i> Multiple solving methods</li>
                                                <li><i class="fas fa-check-circle text-success"></i> Visual explanations</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check-circle text-success"></i> Practice examples included</li>
                                                <li><i class="fas fa-check-circle text-success"></i> Error checking and validation</li>
                                                <li><i class="fas fa-check-circle text-success"></i> Mobile-responsive design</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-center">
                                    <i class="fas fa-chalkboard-teacher display-1 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-4">About OIT</h2>
                    <p class="lead">OIT (Online Educational Tool) is designed to make learning mathematics and other educational subjects more accessible and engaging.</p>
                    <p>Our platform provides:</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i> Interactive mathematical calculators</li>
                        <li><i class="fas fa-check text-success me-2"></i> Step-by-step problem solving</li>
                        <li><i class="fas fa-check text-success me-2"></i> Educational resources and materials</li>
                        <li><i class="fas fa-check text-success me-2"></i> User-friendly interface</li>
                        <li><i class="fas fa-check text-success me-2"></i> Free access to all tools</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-graduation-cap display-1 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold">Get in Touch</h2>
                <p class="lead">Have questions or suggestions? We'd love to hear from you!</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if (isset($_SESSION['contact_success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['contact_success'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['contact_success']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['contact_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['contact_error'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['contact_error']); ?>
                    <?php endif; ?>
                    
                    <form action="contact_handler.php" method="POST" class="bg-white p-4 rounded shadow">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg" name="send_message">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-graduation-cap"></i> OIT - Online Educational Tool</h5>
                    <p class="mb-0">Making education accessible and engaging for everyone.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">&copy; <?= date('Y') ?> OIT. All rights reserved.</p>
                    <div class="mt-2">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add active class to current navigation item
        window.addEventListener('scroll', function() {
            let current = '';
            const sections = document.querySelectorAll('section[id]');
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            document.querySelectorAll('.navbar-nav a').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>