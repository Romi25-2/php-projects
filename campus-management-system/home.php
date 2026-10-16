<?php
// Home Page - Dashboard
$parking = [];
$library = [];
$performance = [];

if(isset($_SESSION['parking'])){
    $parking = $_SESSION['parking'] ;
 } 
if(isset($_SESSION['library'])) {
    $library =  $_SESSION['library'];
}
if(isset($_SESSION['performance'])){
    $performance = $_SESSION['performance'];
}

$totalPermits = 0;
$totalRevenue = 0;
$libraryUsers = 0;
$students = 0;

if(isset($parking['totalPermits'])){
    $totalPermits = $parking['totalPermits'];
}
if(isset($parking['totalRevenue'])){
    $totalRevenue = $parking['totalRevenue'];
}
if(isset($library['users'])){
    $libraryUsers = count($library['users']);
}
if(isset($performance['students'])){
    $students = count($performance['students']);
}
?>

<div class="main-grid">
    <div class="module-card">
        <h2>Parking</h2>
        <p>Manage parking permits for students, staff and visitors.</p>
        
        <div class="btn-group">
            <a href="?module=parking&section=issue" class="btn btn-primary">Issue Permit</a>
        <a href="?module=parking&section=view" class="btn btn-primary">View Permits</a>
       
        </div>
    </div>
    
    <div class="module-card">
        <h2>Library</h2>
        <p>Manage book borrowing and fines.</p>
        
        <div class="btn-group">
            <a href="?module=library&section=add_user" class="btn btn-primary">Add User</a>
           <a href="?module=library&section=borrow" class="btn btn-primary">Borrow Book</a>
        </div>
    </div>
    
    <div class="module-card">
        <h2>Performance</h2>
        <p>Track student marks and grades.</p>
        <div class="btn-group">
            <a href="?module=performance&section=add" class="btn btn-primary">Add Student</a>
            <a href="?module=performance&section=view" class="btn btn-primary">View Students</a>
        </div>
    </div>
</div>

<div class="content">
    <h2>Campus System Statistics</h2>
    
    <div class="stats">
        <div class="stat-card">

            <h3>Total Permits</h3>
            <div class="value">
                <?php echo $totalPermits;?>
            </div>
        </div>
        
        <div class="stat-card">
            <h3>Library Users</h3>
            <div class="value">
                <?php echo $libraryUsers; ?>
            </div>
        </div>
        
        <div class="stat-card">
            <h3>Students</h3>
            <div class="value">
                <?php echo $students; ?>
            </div>
        </div>
        
        <div class="stat-card">
            <h3>Revenue</h3>
            <div class="value">R<?php echo $totalRevenue; ?></div>
        </div>
    </div>
</div>
