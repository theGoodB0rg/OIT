<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['u_log'])) {
    header("Location: ../login/index.php");
    exit();
}

// Get user information
$userId = $_SESSION['u_log'];
$result = executeQuery("SELECT * FROM users WHERE user_id = ?", "s", [$userId]);
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: ../logout.php");
    exit();
}

$userInfo = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quadratic Equation Solver - OIT</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .tool-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
        }
        .equation-input {
            font-size: 1.1rem;
            text-align: center;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .equation-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .solution-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .step-box {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .step-box:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .method-tab {
            border: none;
            background: #f8f9fa;
            color: #6c757d;
            border-radius: 8px 8px 0 0;
            transition: all 0.3s ease;
        }
        .method-tab.active {
            background: #667eea;
            color: white;
        }
        .input-card {
            position: sticky;
            top: 20px;
        }
        .guide-section {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .formula-display {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            text-align: center;
            margin: 10px 0;
        }
        .explanation-card {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 10px 0;
            border-radius: 0 8px 8px 0;
        }
        @media (max-width: 768px) {
            .input-card {
                position: relative;
                top: auto;
            }
            .tool-header {
                padding: 20px 0;
            }
            .tool-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="../dashboard.php"><i class="fas fa-graduation-cap"></i> OIT</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="quadratic-solver.php">Quadratic Solver</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= htmlspecialchars($userInfo['uname']) ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../profile.php"><i class="fas fa-user-edit"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Tool Header -->
    <section class="tool-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-6 fw-bold mb-3"><i class="fas fa-square-root-alt"></i> Quadratic Equation Solver</h1>
                    <p class="lead">Solve quadratic equations of the form ax² + bx + c = 0 with step-by-step solutions</p>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="text-white" style="font-size: 2rem;">
                        ax² + bx + c = 0
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Educational Guide Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="guide-section">
                <h3 class="text-center mb-4"><i class="fas fa-book-open"></i> Understanding Quadratic Equations</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="explanation-card">
                            <h5><i class="fas fa-info-circle"></i> What is a Quadratic?</h5>
                            <p>A quadratic equation is a polynomial equation of degree 2, with the general form:</p>
                            <div class="formula-display">ax² + bx + c = 0</div>
                            <p>Where <strong>a ≠ 0</strong> and a, b, c are constants.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="explanation-card">
                            <h5><i class="fas fa-calculator"></i> Solution Methods</h5>
                            <ul>
                                <li><strong>Quadratic Formula:</strong> Universal method</li>
                                <li><strong>Factoring:</strong> When equation factors nicely</li>
                                <li><strong>Completing Square:</strong> Converting to perfect square</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="explanation-card">
                            <h5><i class="fas fa-chart-line"></i> Types of Solutions</h5>
                            <ul>
                                <li><strong>Two Real:</strong> Discriminant > 0</li>
                                <li><strong>One Real:</strong> Discriminant = 0</li>
                                <li><strong>Complex:</strong> Discriminant < 0</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Input Section -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm input-card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-edit"></i> Enter Coefficients</h5>
                        </div>
                        <div class="card-body">
                            <form id="quadraticForm">
                                <div class="mb-3">
                                    <label for="a" class="form-label fw-bold">Coefficient a (x²)</label>
                                    <input type="number" class="form-control equation-input" id="a" step="any" placeholder="1" required>
                                    <div class="form-text"><i class="fas fa-exclamation-triangle text-warning"></i> Must not be zero</div>
                                </div>
                                <div class="mb-3">
                                    <label for="b" class="form-label fw-bold">Coefficient b (x)</label>
                                    <input type="number" class="form-control equation-input" id="b" step="any" placeholder="0">
                                </div>
                                <div class="mb-3">
                                    <label for="c" class="form-label fw-bold">Coefficient c (constant)</label>
                                    <input type="number" class="form-control equation-input" id="c" step="any" placeholder="0">
                                </div>
                                
                                <div class="equation-preview p-3 bg-light rounded mb-3 border">
                                    <div class="text-center">
                                        <strong>Current Equation:</strong><br>
                                        <span id="equationDisplay" class="h5 text-primary">1x² + 0x + 0 = 0</span>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary btn-lg" onclick="solveQuadratic()">
                                        <i class="fas fa-calculator"></i> Solve Equation
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearForm()">
                                        <i class="fas fa-eraser"></i> Clear All
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Examples -->
                    <div class="card shadow-sm mt-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Try These Examples</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-success btn-sm" onclick="setExample(1, -5, 6)">
                                    <strong>Easy:</strong> x² - 5x + 6 = 0
                                </button>
                                <button class="btn btn-outline-warning btn-sm" onclick="setExample(1, 0, -4)">
                                    <strong>No Linear:</strong> x² - 4 = 0
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="setExample(2, 4, 2)">
                                    <strong>Perfect Square:</strong> 2x² + 4x + 2 = 0
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="setExample(1, 2, 5)">
                                    <strong>Complex:</strong> x² + 2x + 5 = 0
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solution Section -->
                <div class="col-lg-8 col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-brain"></i> Step-by-Step Solutions</h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3 justify-content-center" id="solutionTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active method-tab" id="formula-tab" data-bs-toggle="tab" data-bs-target="#formula" type="button" role="tab">
                                        <i class="fas fa-square-root-alt"></i> Quadratic Formula
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link method-tab" id="completing-tab" data-bs-toggle="tab" data-bs-target="#completing" type="button" role="tab">
                                        <i class="fas fa-puzzle-piece"></i> Completing Square
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link method-tab" id="factoring-tab" data-bs-toggle="tab" data-bs-target="#factoring" type="button" role="tab">
                                        <i class="fas fa-divide"></i> Factoring
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="solutionTabContent">
                                <!-- Quadratic Formula Method -->
                                <div class="tab-pane fade show active" id="formula" role="tabpanel">
                                    <div id="formulaOutput">
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-square-root-alt display-4 mb-3 text-primary"></i>
                                            <h5>Quadratic Formula Method</h5>
                                            <p>The most universal method for solving any quadratic equation:</p>
                                            <div class="formula-display mb-3">
                                                x = (-b ± √(b² - 4ac)) / 2a
                                            </div>
                                            <div class="explanation-card">
                                                <h6><i class="fas fa-info-circle"></i> How it works:</h6>
                                                <ol class="text-start">
                                                    <li>Calculate the discriminant: Δ = b² - 4ac</li>
                                                    <li>If Δ > 0: Two real solutions</li>
                                                    <li>If Δ = 0: One real solution</li>
                                                    <li>If Δ < 0: Two complex solutions</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Completing the Square Method -->
                                <div class="tab-pane fade" id="completing" role="tabpanel">
                                    <div id="completingOutput">
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-puzzle-piece display-4 mb-3 text-success"></i>
                                            <h5>Completing the Square Method</h5>
                                            <p>Transform the equation into a perfect square form:</p>
                                            <div class="formula-display mb-3">
                                                a(x + h)² + k = 0
                                            </div>
                                            <div class="explanation-card">
                                                <h6><i class="fas fa-list-ol"></i> Steps:</h6>
                                                <ol class="text-start">
                                                    <li>Make coefficient of x² equal to 1</li>
                                                    <li>Move constant to right side</li>
                                                    <li>Add (b/2)² to both sides</li>
                                                    <li>Factor left side as perfect square</li>
                                                    <li>Solve for x</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Factoring Method -->
                                <div class="tab-pane fade" id="factoring" role="tabpanel">
                                    <div id="factoringOutput">
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-divide display-4 mb-3 text-warning"></i>
                                            <h5>Factoring Method</h5>
                                            <p>Express the equation as a product of factors:</p>
                                            <div class="formula-display mb-3">
                                                (x - r₁)(x - r₂) = 0
                                            </div>
                                            <div class="explanation-card">
                                                <h6><i class="fas fa-search"></i> When to use:</h6>
                                                <ul class="text-start">
                                                    <li>When the equation factors nicely</li>
                                                    <li>Look for two numbers that multiply to 'ac' and add to 'b'</li>
                                                    <li>Best for simple integer coefficients</li>
                                                    <li>Not always possible with real numbers</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update equation display as user types
        function updateEquationDisplay() {
            const a = document.getElementById('a').value || '1';
            const b = document.getElementById('b').value || '0';
            const c = document.getElementById('c').value || '0';
            
            let equation = '';
            
            // Handle coefficient a
            if (a === '1') equation += 'x²';
            else if (a === '-1') equation += '-x²';
            else equation += `${a}x²`;
            
            // Handle coefficient b
            if (b !== '0') {
                const bNum = parseFloat(b);
                if (bNum > 0 && equation !== '') equation += ' + ';
                else if (bNum < 0) {
                    equation += ' - ';
                    equation += Math.abs(bNum) === 1 ? 'x' : `${Math.abs(bNum)}x`;
                } else {
                    equation += Math.abs(bNum) === 1 ? 'x' : `${Math.abs(bNum)}x`;
                }
                if (bNum > 0) {
                    equation += Math.abs(bNum) === 1 ? 'x' : `${Math.abs(bNum)}x`;
                }
            }
            
            // Handle coefficient c
            if (c !== '0') {
                const cNum = parseFloat(c);
                if (cNum > 0 && equation !== '') equation += ' + ';
                else if (cNum < 0) equation += ' - ';
                
                equation += Math.abs(cNum);
            }
            
            equation += ' = 0';
            document.getElementById('equationDisplay').innerHTML = equation;
        }

        // Set example values
        function setExample(a, b, c) {
            document.getElementById('a').value = a;
            document.getElementById('b').value = b;
            document.getElementById('c').value = c;
            updateEquationDisplay();
        }

        // Clear form
        function clearForm() {
            document.getElementById('quadraticForm').reset();
            updateEquationDisplay();
            clearOutputs();
        }

        // Clear all outputs
        function clearOutputs() {
            const defaultMessages = {
                formula: `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-square-root-alt display-4 mb-3 text-primary"></i>
                        <h5>Quadratic Formula Method</h5>
                        <p>The most universal method for solving any quadratic equation:</p>
                        <div class="formula-display mb-3">x = (-b ± √(b² - 4ac)) / 2a</div>
                        <div class="explanation-card">
                            <h6><i class="fas fa-info-circle"></i> How it works:</h6>
                            <ol class="text-start">
                                <li>Calculate the discriminant: Δ = b² - 4ac</li>
                                <li>If Δ > 0: Two real solutions</li>
                                <li>If Δ = 0: One real solution</li>
                                <li>If Δ < 0: Two complex solutions</li>
                            </ol>
                        </div>
                    </div>
                `,
                completing: `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-puzzle-piece display-4 mb-3 text-success"></i>
                        <h5>Completing the Square Method</h5>
                        <p>Transform the equation into a perfect square form:</p>
                        <div class="formula-display mb-3">a(x + h)² + k = 0</div>
                        <div class="explanation-card">
                            <h6><i class="fas fa-list-ol"></i> Steps:</h6>
                            <ol class="text-start">
                                <li>Make coefficient of x² equal to 1</li>
                                <li>Move constant to right side</li>
                                <li>Add (b/2)² to both sides</li>
                                <li>Factor left side as perfect square</li>
                                <li>Solve for x</li>
                            </ol>
                        </div>
                    </div>
                `,
                factoring: `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-divide display-4 mb-3 text-warning"></i>
                        <h5>Factoring Method</h5>
                        <p>Express the equation as a product of factors:</p>
                        <div class="formula-display mb-3">(x - r₁)(x - r₂) = 0</div>
                        <div class="explanation-card">
                            <h6><i class="fas fa-search"></i> When to use:</h6>
                            <ul class="text-start">
                                <li>When the equation factors nicely</li>
                                <li>Look for two numbers that multiply to 'ac' and add to 'b'</li>
                                <li>Best for simple integer coefficients</li>
                                <li>Not always possible with real numbers</li>
                            </ul>
                        </div>
                    </div>
                `
            };
            
            document.getElementById('formulaOutput').innerHTML = defaultMessages.formula;
            document.getElementById('completingOutput').innerHTML = defaultMessages.completing;
            document.getElementById('factoringOutput').innerHTML = defaultMessages.factoring;
        }

        // Format equation nicely
        function formatEquation(a, b, c) {
            let equation = '';
            
            // Handle coefficient a
            if (a === 1) equation += 'x²';
            else if (a === -1) equation += '-x²';
            else equation += `${a}x²`;
            
            // Handle coefficient b
            if (b !== 0) {
                if (b > 0) equation += ` + ${b === 1 ? '' : b}x`;
                else equation += ` - ${Math.abs(b) === 1 ? '' : Math.abs(b)}x`;
            }
            
            // Handle coefficient c
            if (c !== 0) {
                if (c > 0) equation += ` + ${c}`;
                else equation += ` - ${Math.abs(c)}`;
            }
            
            return equation + ' = 0';
        }

        // Main solving function
        function solveQuadratic() {
            const a = parseFloat(document.getElementById('a').value);
            const b = parseFloat(document.getElementById('b').value) || 0;
            const c = parseFloat(document.getElementById('c').value) || 0;
            
            // Validation
            if (isNaN(a) || a === 0) {
                Swal.fire({
                    title: 'Invalid Input',
                    text: 'Coefficient "a" must be a non-zero number!',
                    icon: 'error',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            // Calculate discriminant
            const discriminant = b * b - 4 * a * c;
            
            // Solve using all three methods
            solveWithFormula(a, b, c, discriminant);
            solveByCompletingSquare(a, b, c);
            solveByFactoring(a, b, c);
        }

        // Solve using quadratic formula with detailed explanation
        function solveWithFormula(a, b, c, discriminant) {
            let output = `
                <div class="solution-box">
                    <h5><i class="fas fa-square-root-alt text-primary"></i> Quadratic Formula Solution</h5>
                    <p><strong>Given equation:</strong> ${formatEquation(a, b, c)}</p>
                    <div class="formula-display">x = (-b ± √(b² - 4ac)) / 2a</div>
                </div>
            `;
            
            output += `
                <div class="step-box">
                    <h6><i class="fas fa-list-ol"></i> Step 1: Identify coefficients</h6>
                    <div class="row">
                        <div class="col-4"><strong>a = ${a}</strong></div>
                        <div class="col-4"><strong>b = ${b}</strong></div>
                        <div class="col-4"><strong>c = ${c}</strong></div>
                    </div>
                </div>
            `;
            
            output += `
                <div class="step-box">
                    <h6><i class="fas fa-calculator"></i> Step 2: Calculate discriminant (Δ)</h6>
                    <p><strong>Δ = b² - 4ac</strong></p>
                    <p>Δ = (${b})² - 4(${a})(${c})</p>
                    <p>Δ = ${b*b} - ${4*a*c} = <strong>${discriminant}</strong></p>
                </div>
            `;
            
            if (discriminant > 0) {
                const sqrt_discriminant = Math.sqrt(discriminant);
                const x1 = (-b + sqrt_discriminant) / (2 * a);
                const x2 = (-b - sqrt_discriminant) / (2 * a);
                
                output += `
                    <div class="step-box">
                        <h6><i class="fas fa-check-circle text-success"></i> Step 3: Two real solutions (Δ > 0)</h6>
                        <p><strong>x₁ = (-b + √Δ) / 2a</strong></p>
                        <p>x₁ = (-${b} + √${discriminant}) / ${2*a}</p>
                        <p>x₁ = (-${b} + ${sqrt_discriminant.toFixed(4)}) / ${2*a}</p>
                        <p><strong>x₁ = ${x1.toFixed(6)}</strong></p>
                        
                        <p class="mt-3"><strong>x₂ = (-b - √Δ) / 2a</strong></p>
                        <p>x₂ = (-${b} - √${discriminant}) / ${2*a}</p>
                        <p>x₂ = (-${b} - ${sqrt_discriminant.toFixed(4)}) / ${2*a}</p>
                        <p><strong>x₂ = ${x2.toFixed(6)}</strong></p>
                    </div>
                `;
                
                output += `
                    <div class="alert alert-success">
                        <h6><i class="fas fa-trophy"></i> Final Answer</h6>
                        <div class="row">
                            <div class="col-6 text-center">
                                <h5 class="text-success">x₁ = ${x1.toFixed(4)}</h5>
                            </div>
                            <div class="col-6 text-center">
                                <h5 class="text-success">x₂ = ${x2.toFixed(4)}</h5>
                            </div>
                        </div>
                    </div>
                `;
            } else if (discriminant === 0) {
                const x = -b / (2 * a);
                
                output += `
                    <div class="step-box">
                        <h6><i class="fas fa-equals text-info"></i> Step 3: One real solution (Δ = 0)</h6>
                        <p><strong>x = -b / 2a</strong></p>
                        <p>x = -${b} / ${2*a}</p>
                        <p><strong>x = ${x.toFixed(6)}</strong></p>
                        <p class="mt-2"><em>This is a repeated root (double root).</em></p>
                    </div>
                `;
                
                output += `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Final Answer</h6>
                        <h5 class="text-center text-info">x = ${x.toFixed(4)} (repeated root)</h5>
                    </div>
                `;
            } else {
                const realPart = -b / (2 * a);
                const imaginaryPart = Math.sqrt(-discriminant) / (2 * a);
                
                output += `
                    <div class="step-box">
                        <h6><i class="fas fa-times-circle text-warning"></i> Step 3: Complex solutions (Δ < 0)</h6>
                        <p>Since the discriminant is negative, there are no real solutions.</p>
                        <p>The solutions are complex numbers:</p>
                        <p><strong>x₁ = ${realPart.toFixed(4)} + ${imaginaryPart.toFixed(4)}i</strong></p>
                        <p><strong>x₂ = ${realPart.toFixed(4)} - ${imaginaryPart.toFixed(4)}i</strong></p>
                    </div>
                `;
                
                output += `
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Result</h6>
                        <p class="mb-0"><strong>No real solutions</strong> - the parabola does not intersect the x-axis.</p>
                    </div>
                `;
            }
            
            document.getElementById('formulaOutput').innerHTML = output;
        }

        // Solve by completing the square with detailed steps
        function solveByCompletingSquare(a, b, c) {
            let output = `
                <div class="solution-box">
                    <h5><i class="fas fa-puzzle-piece text-success"></i> Completing the Square Solution</h5>
                    <p><strong>Given equation:</strong> ${formatEquation(a, b, c)}</p>
                    <div class="formula-display">Transform to: a(x + h)² + k = 0</div>
                </div>
            `;
            
            if (a !== 1) {
                output += `
                    <div class="step-box">
                        <h6><i class="fas fa-divide"></i> Step 1: Divide by coefficient of x²</h6>
                        <p>Divide entire equation by ${a}:</p>
                        <p>x² + ${(b/a).toFixed(4)}x + ${(c/a).toFixed(4)} = 0</p>
                    </div>
                `;
            }
            
            const bDivA = b / a;
            const cDivA = c / a;
            
            output += `
                <div class="step-box">
                    <h6><i class="fas fa-arrow-right"></i> Step 2: Move constant to right side</h6>
                    <p>x² + ${bDivA.toFixed(4)}x = ${(-cDivA).toFixed(4)}</p>
                </div>
            `;
            
            const halfB = bDivA / 2;
            const halfBSquared = halfB * halfB;
            const rightSide = -cDivA + halfBSquared;
            
            output += `
                <div class="step-box">
                    <h6><i class="fas fa-plus-square"></i> Step 3: Complete the square</h6>
                    <p>Take half of the coefficient of x: ${bDivA.toFixed(4)} ÷ 2 = ${halfB.toFixed(4)}</p>
                    <p>Square it: (${halfB.toFixed(4)})² = ${halfBSquared.toFixed(4)}</p>
                    <p>Add to both sides:</p>
                    <p>x² + ${bDivA.toFixed(4)}x + ${halfBSquared.toFixed(4)} = ${(-cDivA).toFixed(4)} + ${halfBSquared.toFixed(4)}</p>
                    <p><strong>(x + ${halfB.toFixed(4)})² = ${rightSide.toFixed(4)}</strong></p>
                </div>
            `;
            
            if (rightSide >= 0) {
                const sqrtRightSide = Math.sqrt(rightSide);
                const x1 = -halfB + sqrtRightSide;
                const x2 = -halfB - sqrtRightSide;
                
                output += `
                    <div class="step-box">
                        <h6><i class="fas fa-square-root-alt"></i> Step 4: Take square root</h6>
                        <p>x + ${halfB.toFixed(4)} = ±√${rightSide.toFixed(4)}</p>
                        <p>x + ${halfB.toFixed(4)} = ±${sqrtRightSide.toFixed(4)}</p>
                        <p><strong>x = -${halfB.toFixed(4)} ± ${sqrtRightSide.toFixed(4)}</strong></p>
                    </div>
                `;
                
                output += `
                    <div class="alert alert-success">
                        <h6><i class="fas fa-trophy"></i> Final Answer</h6>
                        <div class="row">
                            <div class="col-6 text-center">
                                <h5 class="text-success">x₁ = ${x1.toFixed(4)}</h5>
                            </div>
                            <div class="col-6 text-center">
                                <h5 class="text-success">x₂ = ${x2.toFixed(4)}</h5>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                output += `
                    <div class="step-box">
                        <h6><i class="fas fa-times-circle text-warning"></i> Step 4: Cannot take square root</h6>
                        <p>Since ${rightSide.toFixed(4)} < 0, we cannot take the square root of a negative number in real numbers.</p>
                        <p><strong>No real solutions exist.</strong></p>
                    </div>
                `;
                
                output += `
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Result</h6>
                        <p class="mb-0"><strong>No real solutions</strong> - the equation has complex solutions only.</p>
                    </div>
                `;
            }
            
            document.getElementById('completingOutput').innerHTML = output;
        }

        // Try factoring method with educational approach
        function solveByFactoring(a, b, c) {
            let output = `
                <div class="solution-box">
                    <h5><i class="fas fa-divide text-warning"></i> Factoring Method</h5>
                    <p><strong>Given equation:</strong> ${formatEquation(a, b, c)}</p>
                    <div class="formula-display">Goal: (px + q)(rx + s) = 0</div>
                </div>
            `;
            
            // Check if it's a simple case or can be factored
            let factored = false;
            
            // Case 1: Perfect square trinomial
            const discriminant = b * b - 4 * a * c;
            
            if (discriminant === 0) {
                const root = -b / (2 * a);
                output += `
                    <div class="step-box">
                        <h6><i class="fas fa-star text-success"></i> Perfect Square Trinomial</h6>
                        <p>This is a perfect square trinomial!</p>
                        <p>${formatEquation(a, b, c)} = ${a}(x ${root >= 0 ? '-' : '+'} ${Math.abs(root).toFixed(4)})²</p>
                        <p><strong>Solution: x = ${root.toFixed(4)} (double root)</strong></p>
                    </div>
                `;
                factored = true;
            }
            // Case 2: Simple factoring for integer coefficients
            else if (Number.isInteger(a) && Number.isInteger(b) && Number.isInteger(c) && a === 1) {
                // Try to find two numbers that multiply to c and add to b
                let foundFactors = false;
                for (let i = -Math.abs(c); i <= Math.abs(c); i++) {
                    if (i !== 0 && c % i === 0) {
                        const j = c / i;
                        if (i + j === b) {
                            output += `
                                <div class="step-box">
                                    <h6><i class="fas fa-search text-success"></i> Finding Factors</h6>
                                    <p>We need two numbers that:</p>
                                    <ul>
                                        <li>Multiply to give c = ${c}</li>
                                        <li>Add to give b = ${b}</li>
                                    </ul>
                                    <p><strong>Found: ${i} and ${j}</strong></p>
                                    <p>Check: ${i} × ${j} = ${i * j} ✓</p>
                                    <p>Check: ${i} + ${j} = ${i + j} ✓</p>
                                </div>
                            `;
                            
                            output += `
                                <div class="step-box">
                                    <h6><i class="fas fa-puzzle-piece"></i> Factored Form</h6>
                                    <p>${formatEquation(a, b, c)} = (x ${i >= 0 ? '-' : '+'} ${Math.abs(i)})(x ${j >= 0 ? '-' : '+'} ${Math.abs(j)}) = 0</p>
                                    <p><strong>Solutions:</strong></p>
                                    <p>x - ${i} = 0  →  x = ${i}</p>
                                    <p>x - ${j} = 0  →  x = ${j}</p>
                                </div>
                            `;
                            
                            output += `
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-trophy"></i> Final Answer</h6>
                                    <div class="row">
                                        <div class="col-6 text-center">
                                            <h5 class="text-success">x₁ = ${i}</h5>
                                        </div>
                                        <div class="col-6 text-center">
                                            <h5 class="text-success">x₂ = ${j}</h5>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            foundFactors = true;
                            factored = true;
                            break;
                        }
                    }
                }
            }
            
            if (!factored) {
                output += `
                    <div class="step-box">
                        <h6><i class="fas fa-search text-info"></i> Factoring Analysis</h6>
                        <p>Let's check if this equation can be easily factored:</p>
                        <ul>
                            <li><strong>Discriminant:</strong> Δ = ${discriminant}</li>
                            <li><strong>Perfect square:</strong> ${discriminant === 0 ? 'Yes' : 'No'}</li>
                            <li><strong>Integer coefficients:</strong> ${Number.isInteger(a) && Number.isInteger(b) && Number.isInteger(c) ? 'Yes' : 'No'}</li>
                        </ul>
                    </div>
                `;
                
                if (discriminant > 0) {
                    const sqrtDisc = Math.sqrt(discriminant);
                    if (Number.isInteger(sqrtDisc)) {
                        output += `
                            <div class="step-box">
                                <h6><i class="fas fa-lightbulb text-success"></i> Factoring is Possible</h6>
                                <p>Since √${discriminant} = ${sqrtDisc} is an integer, this equation can be factored!</p>
                                <p>The factored form would involve finding the right combination of factors.</p>
                            </div>
                        `;
                    } else {
                        output += `
                            <div class="step-box">
                                <h6><i class="fas fa-times text-warning"></i> Difficult to Factor</h6>
                                <p>Since √${discriminant} = ${sqrtDisc.toFixed(4)} is not an integer, this equation doesn't factor nicely with rational numbers.</p>
                                <p><strong>Recommendation:</strong> Use the quadratic formula or completing the square instead.</p>
                            </div>
                        `;
                    }
                } else {
                    output += `
                        <div class="step-box">
                            <h6><i class="fas fa-times text-danger"></i> Cannot Factor (Real Numbers)</h6>
                            <p>Since the discriminant is negative, this equation has no real roots and cannot be factored over the real numbers.</p>
                        </div>
                    `;
                }
                
                output += `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Learning Note</h6>
                        <p class="mb-0">Factoring works best when:</p>
                        <ul class="mb-0 mt-2">
                            <li>Coefficients are small integers</li>
                            <li>The discriminant is a perfect square</li>
                            <li>The equation was derived from a factored form</li>
                        </ul>
                    </div>
                `;
            }
            
            document.getElementById('factoringOutput').innerHTML = output;
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Update equation display when inputs change
            ['a', 'b', 'c'].forEach(id => {
                document.getElementById(id).addEventListener('input', updateEquationDisplay);
            });
            
            // Initialize equation display
            updateEquationDisplay();
            
            // Initialize with default messages
            clearOutputs();
        });
    </script>

    <!-- Include SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>