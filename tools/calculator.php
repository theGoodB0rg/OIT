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
    <title>Calculator - OIT</title>
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .tool-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px 0;
        }
        .calculator-container {
            max-width: 400px;
            margin: 0 auto;
        }
        .calculator {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .display {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            font-size: 2rem;
            text-align: right;
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-family: 'Courier New', monospace;
        }
        .calc-button {
            border: none;
            padding: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            transition: all 0.2s ease;
            min-height: 70px;
        }
        .calc-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .btn-number {
            background: #ecf0f1;
            color: #2c3e50;
            border: 1px solid #bdc3c7;
        }
        .btn-number:hover {
            background: #d5dbdb;
            color: #2c3e50;
        }
        .btn-operator {
            background: #3498db;
            color: white;
        }
        .btn-operator:hover {
            background: #2980b9;
            color: white;
        }
        .btn-equals {
            background: #e74c3c;
            color: white;
        }
        .btn-equals:hover {
            background: #c0392b;
            color: white;
        }
        .btn-clear {
            background: #f39c12;
            color: white;
        }
        .btn-clear:hover {
            background: #e67e22;
            color: white;
        }
        .history-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
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
                        <a class="nav-link active" href="calculator.php">Calculator</a>
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
                    <h1 class="display-6 fw-bold mb-3"><i class="fas fa-calculator"></i> Scientific Calculator</h1>
                    <p class="lead">Perform basic and advanced mathematical calculations with ease</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-calculator display-1"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Calculator -->
                <div class="col-lg-8">
                    <div class="calculator-container">
                        <div class="calculator">
                            <!-- Display -->
                            <div class="display" id="display">0</div>
                            
                            <!-- Buttons -->
                            <div class="row g-0">
                                <div class="col-3">
                                    <button class="calc-button btn-clear w-100" onclick="clearAll()">AC</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-clear w-100" onclick="clearEntry()">CE</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputOperator('√')">√</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputOperator('/')">÷</button>
                                </div>
                            </div>
                            
                            <div class="row g-0">
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('7')">7</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('8')">8</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('9')">9</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputOperator('*')">×</button>
                                </div>
                            </div>
                            
                            <div class="row g-0">
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('4')">4</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('5')">5</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('6')">6</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputOperator('-')">−</button>
                                </div>
                            </div>
                            
                            <div class="row g-0">
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('1')">1</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('2')">2</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('3')">3</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputOperator('+')">+</button>
                                </div>
                            </div>
                            
                            <div class="row g-0">
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputOperator('^')">x²</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('0')">0</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-number w-100" onclick="inputNumber('.')">.</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-equals w-100" onclick="calculate()">=</button>
                                </div>
                            </div>
                            
                            <!-- Advanced Functions -->
                            <div class="row g-0">
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputFunction('sin')">sin</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputFunction('cos')">cos</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputFunction('tan')">tan</button>
                                </div>
                                <div class="col-3">
                                    <button class="calc-button btn-operator w-100" onclick="inputFunction('log')">log</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History and Help -->
                <div class="col-lg-4">
                    <div class="row g-4">
                        <!-- History -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-history"></i> Calculation History</h6>
                                </div>
                                <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                                    <div id="history">
                                        <div class="text-center text-muted p-3">
                                            <i class="fas fa-calculator-alt mb-2"></i>
                                            <p class="mb-0">Your calculations will appear here</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-sm btn-outline-danger w-100" onclick="clearHistory()">
                                        <i class="fas fa-trash"></i> Clear History
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Functions -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-bolt"></i> Quick Functions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <button class="btn btn-outline-primary btn-sm w-100" onclick="inputFunction('π')">π</button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-outline-primary btn-sm w-100" onclick="inputFunction('e')">e</button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-outline-primary btn-sm w-100" onclick="inputOperator('(')">(</button>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-outline-primary btn-sm w-100" onclick="inputOperator(')')">)</button>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-outline-success btn-sm w-100" onclick="inputFunction('abs')">|x|</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Help -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-question-circle"></i> Help</h6>
                                </div>
                                <div class="card-body">
                                    <small>
                                        <strong>Keyboard Shortcuts:</strong><br>
                                        • Numbers: 0-9<br>
                                        • Operators: +, -, *, /<br>
                                        • Enter: Calculate<br>
                                        • Escape: Clear<br>
                                        • Backspace: Delete last
                                    </small>
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
        let currentInput = '0';
        let previousInput = '';
        let operator = '';
        let waitingForNumber = false;
        let history = [];

        const display = document.getElementById('display');

        function updateDisplay() {
            display.textContent = currentInput;
        }

        function inputNumber(num) {
            if (waitingForNumber) {
                currentInput = num;
                waitingForNumber = false;
            } else {
                currentInput = currentInput === '0' ? num : currentInput + num;
            }
            updateDisplay();
        }

        function inputOperator(op) {
            if (op === '√') {
                const num = parseFloat(currentInput);
                if (num >= 0) {
                    addToHistory(`√${currentInput} = ${Math.sqrt(num)}`);
                    currentInput = Math.sqrt(num).toString();
                } else {
                    currentInput = 'Error';
                }
                updateDisplay();
                return;
            }

            if (op === '^') {
                const num = parseFloat(currentInput);
                addToHistory(`${currentInput}² = ${Math.pow(num, 2)}`);
                currentInput = Math.pow(num, 2).toString();
                updateDisplay();
                return;
            }

            if (previousInput !== '' && operator !== '' && !waitingForNumber) {
                calculate();
            }

            previousInput = currentInput;
            operator = op;
            waitingForNumber = true;
        }

        function inputFunction(func) {
            const num = parseFloat(currentInput);
            let result;

            switch(func) {
                case 'sin':
                    result = Math.sin(num * Math.PI / 180);
                    addToHistory(`sin(${currentInput}°) = ${result}`);
                    break;
                case 'cos':
                    result = Math.cos(num * Math.PI / 180);
                    addToHistory(`cos(${currentInput}°) = ${result}`);
                    break;
                case 'tan':
                    result = Math.tan(num * Math.PI / 180);
                    addToHistory(`tan(${currentInput}°) = ${result}`);
                    break;
                case 'log':
                    result = Math.log10(num);
                    addToHistory(`log(${currentInput}) = ${result}`);
                    break;
                case 'abs':
                    result = Math.abs(num);
                    addToHistory(`|${currentInput}| = ${result}`);
                    break;
                case 'π':
                    currentInput = Math.PI.toString();
                    updateDisplay();
                    return;
                case 'e':
                    currentInput = Math.E.toString();
                    updateDisplay();
                    return;
                default:
                    return;
            }

            currentInput = result.toString();
            updateDisplay();
        }

        function calculate() {
            if (previousInput === '' || operator === '') return;

            const prev = parseFloat(previousInput);
            const current = parseFloat(currentInput);
            let result;

            switch(operator) {
                case '+':
                    result = prev + current;
                    break;
                case '-':
                    result = prev - current;
                    break;
                case '*':
                    result = prev * current;
                    break;
                case '/':
                    if (current === 0) {
                        currentInput = 'Error';
                        updateDisplay();
                        return;
                    }
                    result = prev / current;
                    break;
                default:
                    return;
            }

            addToHistory(`${previousInput} ${operator} ${currentInput} = ${result}`);
            currentInput = result.toString();
            previousInput = '';
            operator = '';
            waitingForNumber = true;
            updateDisplay();
        }

        function clearAll() {
            currentInput = '0';
            previousInput = '';
            operator = '';
            waitingForNumber = false;
            updateDisplay();
        }

        function clearEntry() {
            currentInput = '0';
            updateDisplay();
        }

        function addToHistory(calculation) {
            history.unshift(calculation);
            if (history.length > 10) {
                history.pop();
            }
            updateHistoryDisplay();
        }

        function updateHistoryDisplay() {
            const historyDiv = document.getElementById('history');
            if (history.length === 0) {
                historyDiv.innerHTML = `
                    <div class="text-center text-muted p-3">
                        <i class="fas fa-calculator-alt mb-2"></i>
                        <p class="mb-0">Your calculations will appear here</p>
                    </div>
                `;
            } else {
                historyDiv.innerHTML = history.map(item => 
                    `<div class="history-item">${item}</div>`
                ).join('');
            }
        }

        function clearHistory() {
            history = [];
            updateHistoryDisplay();
        }

        // Keyboard support
        document.addEventListener('keydown', function(event) {
            const key = event.key;
            
            if (key >= '0' && key <= '9') {
                inputNumber(key);
            } else if (key === '.') {
                inputNumber('.');
            } else if (key === '+') {
                inputOperator('+');
            } else if (key === '-') {
                inputOperator('-');
            } else if (key === '*') {
                inputOperator('*');
            } else if (key === '/') {
                event.preventDefault();
                inputOperator('/');
            } else if (key === 'Enter' || key === '=') {
                calculate();
            } else if (key === 'Escape') {
                clearAll();
            } else if (key === 'Backspace') {
                if (currentInput.length > 1) {
                    currentInput = currentInput.slice(0, -1);
                } else {
                    currentInput = '0';
                }
                updateDisplay();
            }
        });

        // Initialize
        updateDisplay();
    </script>
</body>
</html>