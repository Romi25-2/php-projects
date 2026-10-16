<?php
session_start();

// creating session arrays if session doesnt exist yet
if (!isset($_SESSION['parking'])) {
    $_SESSION['parking'] = [];
    $_SESSION['parking']['permits'] = [];
    $_SESSION['parking']['totalRevenue'] = 0;
    $_SESSION['parking']['totalPermits'] = 0;

    $_SESSION['parking']['totalStudent'] = 0;
    $_SESSION['parking']['totalStaff'] = 0;
    $_SESSION['parking']['totalVisitor'] = 0;
}

if (!isset($_SESSION['library'])) {
    $_SESSION['library'] = array(
        'users' => array(
            'U001' => array(
                'name' => 'Thandi Demo',
                'outstanding_fine' => 0,
                'transactions' => array()
            )
        ),
        'nextTransactionId' => 1
    );
}

if (!isset($_SESSION['performance'])) {
    $_SESSION['performance'] = array(
        'students' => array(),
        'studentIdCounter' => 1
    );
}

require_once 'functions.php';

$currentModule = 'home';

if(isset($_GET['module'])){
    $currentModule = $_GET['module'];
}
$section = isset($_GET['section']) ? $_GET['section'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        header {
            background-color: #333;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        
        header p {
            font-size: 14px;
            color: #ccc;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #999;
            font-size: 14px;
        }
        
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .main-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .module-card {
            background-color: white;
            padding: 15px;
            border: 1px solid #ddd;
        }
        
       .module-card h2{
         margin-bottom:10px;
        color:#333;
        }
        
        .module-card p {
            color: #666;
            font-size: 13px;
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .btn {
            padding: 8px 12px;
            border: 1px solid #999;
            background-color: #ddd;
            cursor: pointer;
            text-decoration: none;
            color: #333;
            text-align: center;
            display: block;
            font-size: 13px;
        }
        
        .btn:hover {
            background-color: #bbb;
        }
        
        .btn-primary {
            background-color: #0066cc;
            color: white;
            border-color: #004499;
        }
        
        .btn-primary:hover {
            background-color: #0052a3;
        }
        
        .content {
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 15px;
            color: #0066cc;
            text-decoration: none;
            font-size: 13px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .form-group {
            margin-bottom: 12px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 4px;
            color: #333;
            font-size: 13px;
            font-weight: bold;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 6px;
            border: 1px solid #999;
            font-family: Arial, sans-serif;
            font-size: 13px;
            box-sizing: border-box;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        
        .form-row-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .table th {
            background-color: #333;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
        }
        
        .table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        
        .table tr:hover {
            background-color: #f5f5f5;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 15px;
        }
        
        .stat-card {
            background-color: #f9f9f9;
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        
        .stat-card h3 {
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
            font-weight: normal;
        }
        
        .stat-card .value {
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }
        
        .section-links {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid #999;
            padding-bottom: 10px;
        }
        
        .section-links a {
            padding: 8px 12px;
            background-color: #e0e0e0;
            border: none;
            text-decoration: none;
            color: #333;
            font-size: 13px;
            border: 1px solid #999;
        }
        
        .section-links a:hover {
            background-color: #bbb;
        }
        
        .section-links a.active {
            background-color: #0066cc;
            color: white;
            border-color: #004499;
        }
        
        .badge {
            padding: 3px 6px;
            border: 1px solid #999;
            font-size: 11px;
            background-color: #e0e0e0;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .card {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }
        
        h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 15px;
            margin-top: 15px;
        }
        
        @media (max-width: 768px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
            .form-row, .form-row-3 {
                grid-template-columns: 1fr;
            }
            .stats {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Campus Management System</h1>
            <p>Parking | Library | Performance</p>
        </header>

        <?php
        if ($currentModule === 'parking') {
            include 'parking.php';
        } 
        elseif ($currentModule === 'library') {
            include 'library.php';
        } 
        elseif ($currentModule === 'performance') {
            include 'performance.php';
        }
         else {
            include 'home.php';
        }
        ?>
    </div>
</body>
</html>
