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
    <title>Geometry Helper - OIT</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .tool-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 40px 0;
        }
        .shape-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        .shape-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .shape-icon {
            font-size: 3rem;
            color: #17a2b8;
            margin-bottom: 1rem;
        }
        .geometry-input {
            font-size: 1rem;
            border: 2px solid #dee2e6;
            border-radius: 8px;
        }
        .geometry-input:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
        }
        .result-box {
            background: #f8f9fa;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .formula-display {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
        }
        .nav-pills .nav-link.active {
            background-color: #17a2b8;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
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
                        <a class="nav-link active" href="geometry-helper.php">Geometry Helper</a>
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
                    <h1 class="display-6 fw-bold mb-3"><i class="fas fa-shapes"></i> Geometry Helper</h1>
                    <p class="lead">Calculate areas, perimeters, and volumes of various geometric shapes with detailed formulas</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-shapes display-1"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <!-- Shape Category Tabs -->
            <ul class="nav nav-pills justify-content-center mb-4" id="shapeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="2d-tab" data-bs-toggle="pill" data-bs-target="#shapes2d" type="button" role="tab">
                        <i class="fas fa-square"></i> 2D Shapes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="3d-tab" data-bs-toggle="pill" data-bs-target="#shapes3d" type="button" role="tab">
                        <i class="fas fa-cube"></i> 3D Shapes
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="shapeTabContent">
                <!-- 2D Shapes -->
                <div class="tab-pane fade show active" id="shapes2d" role="tabpanel">
                    <div class="row g-4">
                        <!-- Square -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-square shape-icon"></i>
                                    <h5 class="card-title">Square</h5>
                                    <div class="mb-3">
                                        <label for="square_side" class="form-label">Side Length</label>
                                        <input type="number" class="form-control geometry-input" id="square_side" step="any" placeholder="5">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateSquare()">Calculate</button>
                                    <div id="squareResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Rectangle -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-rectangle-wide shape-icon"></i>
                                    <h5 class="card-title">Rectangle</h5>
                                    <div class="mb-2">
                                        <label for="rect_length" class="form-label">Length</label>
                                        <input type="number" class="form-control geometry-input" id="rect_length" step="any" placeholder="8">
                                    </div>
                                    <div class="mb-3">
                                        <label for="rect_width" class="form-label">Width</label>
                                        <input type="number" class="form-control geometry-input" id="rect_width" step="any" placeholder="5">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateRectangle()">Calculate</button>
                                    <div id="rectangleResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Circle -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-circle shape-icon"></i>
                                    <h5 class="card-title">Circle</h5>
                                    <div class="mb-3">
                                        <label for="circle_radius" class="form-label">Radius</label>
                                        <input type="number" class="form-control geometry-input" id="circle_radius" step="any" placeholder="3">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateCircle()">Calculate</button>
                                    <div id="circleResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Triangle -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-play shape-icon" style="transform: rotate(90deg);"></i>
                                    <h5 class="card-title">Triangle</h5>
                                    <div class="mb-2">
                                        <label for="triangle_base" class="form-label">Base</label>
                                        <input type="number" class="form-control geometry-input" id="triangle_base" step="any" placeholder="6">
                                    </div>
                                    <div class="mb-3">
                                        <label for="triangle_height" class="form-label">Height</label>
                                        <input type="number" class="form-control geometry-input" id="triangle_height" step="any" placeholder="4">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateTriangle()">Calculate</button>
                                    <div id="triangleResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Parallelogram -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-parallelogram shape-icon"></i>
                                    <h5 class="card-title">Parallelogram</h5>
                                    <div class="mb-2">
                                        <label for="para_base" class="form-label">Base</label>
                                        <input type="number" class="form-control geometry-input" id="para_base" step="any" placeholder="7">
                                    </div>
                                    <div class="mb-3">
                                        <label for="para_height" class="form-label">Height</label>
                                        <input type="number" class="form-control geometry-input" id="para_height" step="any" placeholder="4">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateParallelogram()">Calculate</button>
                                    <div id="parallelogramResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Trapezoid -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-draw-polygon shape-icon"></i>
                                    <h5 class="card-title">Trapezoid</h5>
                                    <div class="mb-2">
                                        <label for="trap_base1" class="form-label">Base 1</label>
                                        <input type="number" class="form-control geometry-input" id="trap_base1" step="any" placeholder="8">
                                    </div>
                                    <div class="mb-2">
                                        <label for="trap_base2" class="form-label">Base 2</label>
                                        <input type="number" class="form-control geometry-input" id="trap_base2" step="any" placeholder="5">
                                    </div>
                                    <div class="mb-3">
                                        <label for="trap_height" class="form-label">Height</label>
                                        <input type="number" class="form-control geometry-input" id="trap_height" step="any" placeholder="4">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateTrapezoid()">Calculate</button>
                                    <div id="trapezoidResult"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3D Shapes -->
                <div class="tab-pane fade" id="shapes3d" role="tabpanel">
                    <div class="row g-4">
                        <!-- Cube -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-cube shape-icon"></i>
                                    <h5 class="card-title">Cube</h5>
                                    <div class="mb-3">
                                        <label for="cube_side" class="form-label">Side Length</label>
                                        <input type="number" class="form-control geometry-input" id="cube_side" step="any" placeholder="4">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateCube()">Calculate</button>
                                    <div id="cubeResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Rectangular Prism -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-cube shape-icon"></i>
                                    <h5 class="card-title">Rectangular Prism</h5>
                                    <div class="mb-2">
                                        <label for="prism_length" class="form-label">Length</label>
                                        <input type="number" class="form-control geometry-input" id="prism_length" step="any" placeholder="6">
                                    </div>
                                    <div class="mb-2">
                                        <label for="prism_width" class="form-label">Width</label>
                                        <input type="number" class="form-control geometry-input" id="prism_width" step="any" placeholder="4">
                                    </div>
                                    <div class="mb-3">
                                        <label for="prism_height" class="form-label">Height</label>
                                        <input type="number" class="form-control geometry-input" id="prism_height" step="any" placeholder="3">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculatePrism()">Calculate</button>
                                    <div id="prismResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Sphere -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-baseball-ball shape-icon"></i>
                                    <h5 class="card-title">Sphere</h5>
                                    <div class="mb-3">
                                        <label for="sphere_radius" class="form-label">Radius</label>
                                        <input type="number" class="form-control geometry-input" id="sphere_radius" step="any" placeholder="3">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateSphere()">Calculate</button>
                                    <div id="sphereResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Cylinder -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-database shape-icon"></i>
                                    <h5 class="card-title">Cylinder</h5>
                                    <div class="mb-2">
                                        <label for="cylinder_radius" class="form-label">Radius</label>
                                        <input type="number" class="form-control geometry-input" id="cylinder_radius" step="any" placeholder="3">
                                    </div>
                                    <div class="mb-3">
                                        <label for="cylinder_height" class="form-label">Height</label>
                                        <input type="number" class="form-control geometry-input" id="cylinder_height" step="any" placeholder="8">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateCylinder()">Calculate</button>
                                    <div id="cylinderResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Cone -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-sort-up shape-icon"></i>
                                    <h5 class="card-title">Cone</h5>
                                    <div class="mb-2">
                                        <label for="cone_radius" class="form-label">Radius</label>
                                        <input type="number" class="form-control geometry-input" id="cone_radius" step="any" placeholder="3">
                                    </div>
                                    <div class="mb-3">
                                        <label for="cone_height" class="form-label">Height</label>
                                        <input type="number" class="form-control geometry-input" id="cone_height" step="any" placeholder="6">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculateCone()">Calculate</button>
                                    <div id="coneResult"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Pyramid -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card shape-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-mountain shape-icon"></i>
                                    <h5 class="card-title">Square Pyramid</h5>
                                    <div class="mb-2">
                                        <label for="pyramid_base" class="form-label">Base Side</label>
                                        <input type="number" class="form-control geometry-input" id="pyramid_base" step="any" placeholder="4">
                                    </div>
                                    <div class="mb-3">
                                        <label for="pyramid_height" class="form-label">Height</label>
                                        <input type="number" class="form-control geometry-input" id="pyramid_height" step="any" placeholder="6">
                                    </div>
                                    <button class="btn btn-info w-100" onclick="calculatePyramid()">Calculate</button>
                                    <div id="pyramidResult"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Reference -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-book"></i> Formula Quick Reference</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-square text-info"></i> 2D Shapes</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Square:</strong> Area = s², Perimeter = 4s</li>
                                        <li><strong>Rectangle:</strong> Area = lw, Perimeter = 2(l+w)</li>
                                        <li><strong>Circle:</strong> Area = πr², Circumference = 2πr</li>
                                        <li><strong>Triangle:</strong> Area = ½bh</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-cube text-info"></i> 3D Shapes</h6>
                                    <ul class="list-unstyled">
                                        <li><strong>Cube:</strong> Volume = s³, Surface Area = 6s²</li>
                                        <li><strong>Sphere:</strong> Volume = (4/3)πr³, Surface Area = 4πr²</li>
                                        <li><strong>Cylinder:</strong> Volume = πr²h, Surface Area = 2πr(r+h)</li>
                                        <li><strong>Cone:</strong> Volume = (1/3)πr²h</li>
                                    </ul>
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
        const π = Math.PI;

        // 2D Shape Calculations
        function calculateSquare() {
            const side = parseFloat(document.getElementById('square_side').value);
            if (isNaN(side) || side <= 0) {
                document.getElementById('squareResult').innerHTML = getErrorMessage();
                return;
            }
            
            const area = side * side;
            const perimeter = 4 * side;
            
            document.getElementById('squareResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Area:</strong> ${area.toFixed(2)} units²</p>
                    <p><strong>Perimeter:</strong> ${perimeter.toFixed(2)} units</p>
                    <div class="formula-display">
                        Area = s² = ${side}² = ${area.toFixed(2)}<br>
                        Perimeter = 4s = 4 × ${side} = ${perimeter.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculateRectangle() {
            const length = parseFloat(document.getElementById('rect_length').value);
            const width = parseFloat(document.getElementById('rect_width').value);
            if (isNaN(length) || isNaN(width) || length <= 0 || width <= 0) {
                document.getElementById('rectangleResult').innerHTML = getErrorMessage();
                return;
            }
            
            const area = length * width;
            const perimeter = 2 * (length + width);
            
            document.getElementById('rectangleResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Area:</strong> ${area.toFixed(2)} units²</p>
                    <p><strong>Perimeter:</strong> ${perimeter.toFixed(2)} units</p>
                    <div class="formula-display">
                        Area = lw = ${length} × ${width} = ${area.toFixed(2)}<br>
                        Perimeter = 2(l+w) = 2(${length}+${width}) = ${perimeter.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculateCircle() {
            const radius = parseFloat(document.getElementById('circle_radius').value);
            if (isNaN(radius) || radius <= 0) {
                document.getElementById('circleResult').innerHTML = getErrorMessage();
                return;
            }
            
            const area = π * radius * radius;
            const circumference = 2 * π * radius;
            const diameter = 2 * radius;
            
            document.getElementById('circleResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Area:</strong> ${area.toFixed(2)} units²</p>
                    <p><strong>Circumference:</strong> ${circumference.toFixed(2)} units</p>
                    <p><strong>Diameter:</strong> ${diameter.toFixed(2)} units</p>
                    <div class="formula-display">
                        Area = πr² = π × ${radius}² = ${area.toFixed(2)}<br>
                        Circumference = 2πr = 2 × π × ${radius} = ${circumference.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculateTriangle() {
            const base = parseFloat(document.getElementById('triangle_base').value);
            const height = parseFloat(document.getElementById('triangle_height').value);
            if (isNaN(base) || isNaN(height) || base <= 0 || height <= 0) {
                document.getElementById('triangleResult').innerHTML = getErrorMessage();
                return;
            }
            
            const area = 0.5 * base * height;
            
            document.getElementById('triangleResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Area:</strong> ${area.toFixed(2)} units²</p>
                    <div class="formula-display">
                        Area = ½bh = ½ × ${base} × ${height} = ${area.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculateParallelogram() {
            const base = parseFloat(document.getElementById('para_base').value);
            const height = parseFloat(document.getElementById('para_height').value);
            if (isNaN(base) || isNaN(height) || base <= 0 || height <= 0) {
                document.getElementById('parallelogramResult').innerHTML = getErrorMessage();
                return;
            }
            
            const area = base * height;
            
            document.getElementById('parallelogramResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Area:</strong> ${area.toFixed(2)} units²</p>
                    <div class="formula-display">
                        Area = bh = ${base} × ${height} = ${area.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculateTrapezoid() {
            const base1 = parseFloat(document.getElementById('trap_base1').value);
            const base2 = parseFloat(document.getElementById('trap_base2').value);
            const height = parseFloat(document.getElementById('trap_height').value);
            if (isNaN(base1) || isNaN(base2) || isNaN(height) || base1 <= 0 || base2 <= 0 || height <= 0) {
                document.getElementById('trapezoidResult').innerHTML = getErrorMessage();
                return;
            }
            
            const area = 0.5 * (base1 + base2) * height;
            
            document.getElementById('trapezoidResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Area:</strong> ${area.toFixed(2)} units²</p>
                    <div class="formula-display">
                        Area = ½(b₁+b₂)h = ½(${base1}+${base2}) × ${height} = ${area.toFixed(2)}
                    </div>
                </div>
            `;
        }

        // 3D Shape Calculations
        function calculateCube() {
            const side = parseFloat(document.getElementById('cube_side').value);
            if (isNaN(side) || side <= 0) {
                document.getElementById('cubeResult').innerHTML = getErrorMessage();
                return;
            }
            
            const volume = side * side * side;
            const surfaceArea = 6 * side * side;
            
            document.getElementById('cubeResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Volume:</strong> ${volume.toFixed(2)} units³</p>
                    <p><strong>Surface Area:</strong> ${surfaceArea.toFixed(2)} units²</p>
                    <div class="formula-display">
                        Volume = s³ = ${side}³ = ${volume.toFixed(2)}<br>
                        Surface Area = 6s² = 6 × ${side}² = ${surfaceArea.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculatePrism() {
            const length = parseFloat(document.getElementById('prism_length').value);
            const width = parseFloat(document.getElementById('prism_width').value);
            const height = parseFloat(document.getElementById('prism_height').value);
            if (isNaN(length) || isNaN(width) || isNaN(height) || length <= 0 || width <= 0 || height <= 0) {
                document.getElementById('prismResult').innerHTML = getErrorMessage();
                return;
            }
            
            const volume = length * width * height;
            const surfaceArea = 2 * (length * width + length * height + width * height);
            
            document.getElementById('prismResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Volume:</strong> ${volume.toFixed(2)} units³</p>
                    <p><strong>Surface Area:</strong> ${surfaceArea.toFixed(2)} units²</p>
                    <div class="formula-display">
                        Volume = lwh = ${length} × ${width} × ${height} = ${volume.toFixed(2)}<br>
                        Surface Area = 2(lw+lh+wh) = ${surfaceArea.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculateSphere() {
            const radius = parseFloat(document.getElementById('sphere_radius').value);
            if (isNaN(radius) || radius <= 0) {
                document.getElementById('sphereResult').innerHTML = getErrorMessage();
                return;
            }
            
            const volume = (4/3) * π * radius * radius * radius;
            const surfaceArea = 4 * π * radius * radius;
            
            document.getElementById('sphereResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Volume:</strong> ${volume.toFixed(2)} units³</p>
                    <p><strong>Surface Area:</strong> ${surfaceArea.toFixed(2)} units²</p>
                    <div class="formula-display">
                        Volume = (4/3)πr³ = (4/3) × π × ${radius}³ = ${volume.toFixed(2)}<br>
                        Surface Area = 4πr² = 4 × π × ${radius}² = ${surfaceArea.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculateCylinder() {
            const radius = parseFloat(document.getElementById('cylinder_radius').value);
            const height = parseFloat(document.getElementById('cylinder_height').value);
            if (isNaN(radius) || isNaN(height) || radius <= 0 || height <= 0) {
                document.getElementById('cylinderResult').innerHTML = getErrorMessage();
                return;
            }
            
            const volume = π * radius * radius * height;
            const surfaceArea = 2 * π * radius * (radius + height);
            
            document.getElementById('cylinderResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Volume:</strong> ${volume.toFixed(2)} units³</p>
                    <p><strong>Surface Area:</strong> ${surfaceArea.toFixed(2)} units²</p>
                    <div class="formula-display">
                        Volume = πr²h = π × ${radius}² × ${height} = ${volume.toFixed(2)}<br>
                        Surface Area = 2πr(r+h) = 2π × ${radius} × (${radius}+${height}) = ${surfaceArea.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculateCone() {
            const radius = parseFloat(document.getElementById('cone_radius').value);
            const height = parseFloat(document.getElementById('cone_height').value);
            if (isNaN(radius) || isNaN(height) || radius <= 0 || height <= 0) {
                document.getElementById('coneResult').innerHTML = getErrorMessage();
                return;
            }
            
            const volume = (1/3) * π * radius * radius * height;
            const slantHeight = Math.sqrt(radius * radius + height * height);
            const surfaceArea = π * radius * (radius + slantHeight);
            
            document.getElementById('coneResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Volume:</strong> ${volume.toFixed(2)} units³</p>
                    <p><strong>Surface Area:</strong> ${surfaceArea.toFixed(2)} units²</p>
                    <p><strong>Slant Height:</strong> ${slantHeight.toFixed(2)} units</p>
                    <div class="formula-display">
                        Volume = (1/3)πr²h = (1/3) × π × ${radius}² × ${height} = ${volume.toFixed(2)}<br>
                        Slant Height = √(r²+h²) = √(${radius}²+${height}²) = ${slantHeight.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function calculatePyramid() {
            const base = parseFloat(document.getElementById('pyramid_base').value);
            const height = parseFloat(document.getElementById('pyramid_height').value);
            if (isNaN(base) || isNaN(height) || base <= 0 || height <= 0) {
                document.getElementById('pyramidResult').innerHTML = getErrorMessage();
                return;
            }
            
            const volume = (1/3) * base * base * height;
            const slantHeight = Math.sqrt((base/2) * (base/2) + height * height);
            const baseArea = base * base;
            const lateralArea = 2 * base * slantHeight;
            const surfaceArea = baseArea + lateralArea;
            
            document.getElementById('pyramidResult').innerHTML = `
                <div class="result-box">
                    <h6><i class="fas fa-check-circle text-success"></i> Results</h6>
                    <p><strong>Volume:</strong> ${volume.toFixed(2)} units³</p>
                    <p><strong>Surface Area:</strong> ${surfaceArea.toFixed(2)} units²</p>
                    <p><strong>Slant Height:</strong> ${slantHeight.toFixed(2)} units</p>
                    <div class="formula-display">
                        Volume = (1/3)B×h = (1/3) × ${base}² × ${height} = ${volume.toFixed(2)}<br>
                        Surface Area = B + Lateral Area = ${baseArea.toFixed(2)} + ${lateralArea.toFixed(2)} = ${surfaceArea.toFixed(2)}
                    </div>
                </div>
            `;
        }

        function getErrorMessage() {
            return `
                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-triangle"></i> Please enter valid positive numbers!
                </div>
            `;
        }
    </script>
</body>
</html>