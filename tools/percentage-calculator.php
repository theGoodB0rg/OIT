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
    <title>Percentage Calculator - OIT</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .tool-header {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 40px 0;
        }
        .calc-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .calc-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .result-box {
            background: #f8f9fa;
            border-left: 4px solid #f39c12;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .percentage-input {
            font-size: 1.1rem;
            border: 2px solid #dee2e6;
            border-radius: 8px;
        }
        .percentage-input:focus {
            border-color: #f39c12;
            box-shadow: 0 0 0 0.2rem rgba(243, 156, 18, 0.25);
        }
        .calc-icon {
            font-size: 2.5rem;
            color: #f39c12;
            margin-bottom: 1rem;
        }
        .guide-section {
            background: #fff3cd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .formula-display {
            background: #e9ecef;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            text-align: center;
            margin: 10px 0;
        }
        .example-card {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 10px 0;
            border-radius: 0 8px 8px 0;
        }
        @media (max-width: 768px) {
            .tool-header {
                padding: 20px 0;
            }
            .tool-header h1 {
                font-size: 1.8rem;
            }
            .calc-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #f39c12;">
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
                        <a class="nav-link active" href="percentage-calculator.php">Percentage Calculator</a>
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
                    <h1 class="display-6 fw-bold mb-3"><i class="fas fa-percentage"></i> Percentage Calculator</h1>
                    <p class="lead">Calculate percentages, percentage changes, discounts, and more with step-by-step solutions</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-percentage display-1"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Educational Guide Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="guide-section">
                <h3 class="text-center mb-4"><i class="fas fa-book-open"></i> Understanding Percentages</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="example-card">
                            <h5><i class="fas fa-info-circle"></i> What is a Percentage?</h5>
                            <p>A percentage is a fraction out of 100. The symbol % means "per cent" or "out of 100".</p>
                            <div class="formula-display">25% = 25/100 = 0.25</div>
                            <p><strong>Example:</strong> 25% of 200 = 0.25 × 200 = 50</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="example-card">
                            <h5><i class="fas fa-calculator"></i> Basic Formula</h5>
                            <p>To find what percent X is of Y:</p>
                            <div class="formula-display">Percentage = (X ÷ Y) × 100</div>
                            <p><strong>Example:</strong> What % is 30 of 120?<br>
                            (30 ÷ 120) × 100 = 25%</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="example-card">
                            <h5><i class="fas fa-chart-line"></i> Percentage Change</h5>
                            <p>To find percentage increase or decrease:</p>
                            <div class="formula-display">Change% = ((New - Old) ÷ Old) × 100</div>
                            <p><strong>Example:</strong> From 80 to 100:<br>
                            ((100 - 80) ÷ 80) × 100 = 25% increase</p>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="example-card">
                            <h5><i class="fas fa-shopping-cart"></i> Real-World Applications</h5>
                            <ul>
                                <li><strong>Shopping:</strong> 30% discount on $50 = $15 off</li>
                                <li><strong>Tips:</strong> 15% tip on $40 meal = $6</li>
                                <li><strong>Taxes:</strong> 8% tax on $100 = $8</li>
                                <li><strong>Interest:</strong> 5% annual interest on $1000 = $50</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="example-card">
                            <h5><i class="fas fa-lightbulb"></i> Quick Tips</h5>
                            <ul>
                                <li>To convert percentage to decimal: divide by 100</li>
                                <li>To convert decimal to percentage: multiply by 100</li>
                                <li>50% = 1/2, 25% = 1/4, 75% = 3/4</li>
                                <li>100% = the whole amount</li>
                                <li>More than 100% means more than the original</li>
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
            <div class="row g-4">
                <!-- Basic Percentage -->
                <div class="col-lg-6">
                    <div class="card calc-card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-percent"></i> Basic Percentage</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">What is X% of Y?</p>
                            <form id="basicPercentageForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="basic_percent" class="form-label">Percentage (%)</label>
                                        <input type="number" class="form-control percentage-input" id="basic_percent" step="any" placeholder="25">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="basic_number" class="form-label">Of Number</label>
                                        <input type="number" class="form-control percentage-input" id="basic_number" step="any" placeholder="200">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-warning w-100" onclick="calculateBasicPercentage()">
                                    <i class="fas fa-calculator"></i> Calculate
                                </button>
                            </form>
                            <div id="basicResult"></div>
                        </div>
                    </div>
                </div>

                <!-- Percentage Change -->
                <div class="col-lg-6">
                    <div class="card calc-card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Percentage Change</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Percentage change from X to Y</p>
                            <form id="percentageChangeForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="old_value" class="form-label">Original Value</label>
                                        <input type="number" class="form-control percentage-input" id="old_value" step="any" placeholder="100">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="new_value" class="form-label">New Value</label>
                                        <input type="number" class="form-control percentage-input" id="new_value" step="any" placeholder="150">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-warning w-100" onclick="calculatePercentageChange()">
                                    <i class="fas fa-calculator"></i> Calculate
                                </button>
                            </form>
                            <div id="changeResult"></div>
                        </div>
                    </div>
                </div>

                <!-- Find the Percentage -->
                <div class="col-lg-6">
                    <div class="card calc-card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-question"></i> Find the Percentage</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">X is what percent of Y?</p>
                            <form id="findPercentageForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="part_number" class="form-label">Part (X)</label>
                                        <input type="number" class="form-control percentage-input" id="part_number" step="any" placeholder="50">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="whole_number" class="form-label">Whole (Y)</label>
                                        <input type="number" class="form-control percentage-input" id="whole_number" step="any" placeholder="200">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-warning w-100" onclick="findPercentage()">
                                    <i class="fas fa-calculator"></i> Calculate
                                </button>
                            </form>
                            <div id="findResult"></div>
                        </div>
                    </div>
                </div>

                <!-- Find the Whole -->
                <div class="col-lg-6">
                    <div class="card calc-card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-search"></i> Find the Whole</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">X is Y% of what number?</p>
                            <form id="findWholeForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="part_value" class="form-label">Part Value</label>
                                        <input type="number" class="form-control percentage-input" id="part_value" step="any" placeholder="50">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="percent_value" class="form-label">Percentage (%)</label>
                                        <input type="number" class="form-control percentage-input" id="percent_value" step="any" placeholder="25">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-warning w-100" onclick="findWhole()">
                                    <i class="fas fa-calculator"></i> Calculate
                                </button>
                            </form>
                            <div id="wholeResult"></div>
                        </div>
                    </div>
                </div>

                <!-- Discount Calculator -->
                <div class="col-lg-6">
                    <div class="card calc-card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-tag"></i> Discount Calculator</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Calculate final price after discount</p>
                            <form id="discountForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="original_price" class="form-label">Original Price</label>
                                        <input type="number" class="form-control percentage-input" id="original_price" step="any" placeholder="100">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="discount_percent" class="form-label">Discount (%)</label>
                                        <input type="number" class="form-control percentage-input" id="discount_percent" step="any" placeholder="20">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-warning w-100" onclick="calculateDiscount()">
                                    <i class="fas fa-calculator"></i> Calculate
                                </button>
                            </form>
                            <div id="discountResult"></div>
                        </div>
                    </div>
                </div>

                <!-- Grade Calculator -->
                <div class="col-lg-6">
                    <div class="card calc-card h-100">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Grade Calculator</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Calculate grade percentage</p>
                            <form id="gradeForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="points_earned" class="form-label">Points Earned</label>
                                        <input type="number" class="form-control percentage-input" id="points_earned" step="any" placeholder="85">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="total_points" class="form-label">Total Points</label>
                                        <input type="number" class="form-control percentage-input" id="total_points" step="any" placeholder="100">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-warning w-100" onclick="calculateGrade()">
                                    <i class="fas fa-calculator"></i> Calculate
                                </button>
                            </form>
                            <div id="gradeResult"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Examples Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-lightbulb text-warning"></i> Common Examples</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <h6><i class="fas fa-shopping-cart text-primary"></i> Shopping</h6>
                                    <button class="btn btn-sm btn-outline-primary w-100 mb-2" onclick="setExample('discount', 50, 20)">
                                        20% off $50 item
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary w-100" onclick="setExample('basic', 15, 80)">
                                        15% tip on $80 bill
                                    </button>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6><i class="fas fa-chart-line text-success"></i> Finance</h6>
                                    <button class="btn btn-sm btn-outline-success w-100 mb-2" onclick="setExample('change', 1000, 1200)">
                                        Stock price: $1000→$1200
                                    </button>
                                    <button class="btn btn-sm btn-outline-success w-100" onclick="setExample('basic', 5, 10000)">
                                        5% interest on $10,000
                                    </button>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6><i class="fas fa-graduation-cap text-info"></i> Education</h6>
                                    <button class="btn btn-sm btn-outline-info w-100 mb-2" onclick="setExample('grade', 42, 50)">
                                        42 out of 50 points
                                    </button>
                                    <button class="btn btn-sm btn-outline-info w-100" onclick="setExample('find', 18, 24)">
                                        18 correct out of 24
                                    </button>
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
        function calculateBasicPercentage() {
            const percent = parseFloat(document.getElementById('basic_percent').value);
            const number = parseFloat(document.getElementById('basic_number').value);
            
            if (isNaN(percent) || isNaN(number)) {
                document.getElementById('basicResult').innerHTML = `
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle"></i> Please enter valid numbers!
                    </div>
                `;
                return;
            }
            
            const result = (percent / 100) * number;
            
            document.getElementById('basicResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Result</h6>
                    <p><strong>${percent}% of ${number} = ${result.toFixed(2)}</strong></p>
                    <small class="text-muted">
                        Calculation: ${percent} ÷ 100 × ${number} = ${result.toFixed(2)}
                    </small>
                </div>
            `;
        }

        function calculatePercentageChange() {
            const oldValue = parseFloat(document.getElementById('old_value').value);
            const newValue = parseFloat(document.getElementById('new_value').value);
            
            if (isNaN(oldValue) || isNaN(newValue) || oldValue === 0) {
                document.getElementById('changeResult').innerHTML = `
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle"></i> Please enter valid numbers (original value cannot be zero)!
                    </div>
                `;
                return;
            }
            
            const change = newValue - oldValue;
            const percentChange = (change / oldValue) * 100;
            const isIncrease = change > 0;
            
            document.getElementById('changeResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Result</h6>
                    <p><strong>${isIncrease ? 'Increase' : 'Decrease'}: ${Math.abs(percentChange).toFixed(2)}%</strong></p>
                    <small class="text-muted">
                        Change: ${newValue} - ${oldValue} = ${change}<br>
                        Percentage: (${change} ÷ ${oldValue}) × 100 = ${percentChange.toFixed(2)}%
                    </small>
                </div>
            `;
        }

        function findPercentage() {
            const part = parseFloat(document.getElementById('part_number').value);
            const whole = parseFloat(document.getElementById('whole_number').value);
            
            if (isNaN(part) || isNaN(whole) || whole === 0) {
                document.getElementById('findResult').innerHTML = `
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle"></i> Please enter valid numbers (whole cannot be zero)!
                    </div>
                `;
                return;
            }
            
            const percentage = (part / whole) * 100;
            
            document.getElementById('findResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Result</h6>
                    <p><strong>${part} is ${percentage.toFixed(2)}% of ${whole}</strong></p>
                    <small class="text-muted">
                        Calculation: (${part} ÷ ${whole}) × 100 = ${percentage.toFixed(2)}%
                    </small>
                </div>
            `;
        }

        function findWhole() {
            const part = parseFloat(document.getElementById('part_value').value);
            const percent = parseFloat(document.getElementById('percent_value').value);
            
            if (isNaN(part) || isNaN(percent) || percent === 0) {
                document.getElementById('wholeResult').innerHTML = `
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle"></i> Please enter valid numbers (percentage cannot be zero)!
                    </div>
                `;
                return;
            }
            
            const whole = (part / percent) * 100;
            
            document.getElementById('wholeResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Result</h6>
                    <p><strong>${part} is ${percent}% of ${whole.toFixed(2)}</strong></p>
                    <small class="text-muted">
                        Calculation: ${part} ÷ (${percent} ÷ 100) = ${whole.toFixed(2)}
                    </small>
                </div>
            `;
        }

        function calculateDiscount() {
            const originalPrice = parseFloat(document.getElementById('original_price').value);
            const discount = parseFloat(document.getElementById('discount_percent').value);
            
            if (isNaN(originalPrice) || isNaN(discount)) {
                document.getElementById('discountResult').innerHTML = `
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle"></i> Please enter valid numbers!
                    </div>
                `;
                return;
            }
            
            const discountAmount = (discount / 100) * originalPrice;
            const finalPrice = originalPrice - discountAmount;
            const savings = discountAmount;
            
            document.getElementById('discountResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Result</h6>
                    <p><strong>Final Price: $${finalPrice.toFixed(2)}</strong></p>
                    <p>You save: $${savings.toFixed(2)}</p>
                    <small class="text-muted">
                        Discount: ${discount}% of $${originalPrice} = $${discountAmount.toFixed(2)}<br>
                        Final: $${originalPrice} - $${discountAmount.toFixed(2)} = $${finalPrice.toFixed(2)}
                    </small>
                </div>
            `;
        }

        function calculateGrade() {
            const earned = parseFloat(document.getElementById('points_earned').value);
            const total = parseFloat(document.getElementById('total_points').value);
            
            if (isNaN(earned) || isNaN(total) || total === 0) {
                document.getElementById('gradeResult').innerHTML = `
                    <div class="alert alert-danger mt-3">
                        <i class="fas fa-exclamation-triangle"></i> Please enter valid numbers (total points cannot be zero)!
                    </div>
                `;
                return;
            }
            
            const percentage = (earned / total) * 100;
            let letterGrade = '';
            let gradeColor = '';
            
            if (percentage >= 90) {
                letterGrade = 'A';
                gradeColor = 'text-success';
            } else if (percentage >= 80) {
                letterGrade = 'B';
                gradeColor = 'text-primary';
            } else if (percentage >= 70) {
                letterGrade = 'C';
                gradeColor = 'text-warning';
            } else if (percentage >= 60) {
                letterGrade = 'D';
                gradeColor = 'text-danger';
            } else {
                letterGrade = 'F';
                gradeColor = 'text-danger';
            }
            
            document.getElementById('gradeResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Result</h6>
                    <p><strong>Grade: ${percentage.toFixed(1)}% - <span class="${gradeColor}">${letterGrade}</span></strong></p>
                    <small class="text-muted">
                        Calculation: (${earned} ÷ ${total}) × 100 = ${percentage.toFixed(1)}%
                    </small>
                </div>
            `;
        }

        function setExample(type, value1, value2) {
            switch(type) {
                case 'basic':
                    document.getElementById('basic_percent').value = value1;
                    document.getElementById('basic_number').value = value2;
                    calculateBasicPercentage();
                    break;
                case 'change':
                    document.getElementById('old_value').value = value1;
                    document.getElementById('new_value').value = value2;
                    calculatePercentageChange();
                    break;
                case 'find':
                    document.getElementById('part_number').value = value1;
                    document.getElementById('whole_number').value = value2;
                    findPercentage();
                    break;
                case 'discount':
                    document.getElementById('original_price').value = value1;
                    document.getElementById('discount_percent').value = value2;
                    calculateDiscount();
                    break;
                case 'grade':
                    document.getElementById('points_earned').value = value1;
                    document.getElementById('total_points').value = value2;
                    calculateGrade();
                    break;
            }
        }
    </script>
</body>
</html>