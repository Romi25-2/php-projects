<?php
require_once 'functions.php';

$message = "";  // messages can be error or completetion of form
$section = isset($_GET['section']) ? $_GET['section'] : 'issue';

if ($_POST) {  // default page 
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    // check form submission
    if ($action === 'issue_permit') {

    //input values
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $age = isset($_POST['age']) ? intval($_POST['age']) : 0;
        $type = isset($_POST['type']) ? trim($_POST['type']) : '';
        
        //checks the input 
        if ($name && $age >= 18 && ($type === 'student' || $type === 'staff' || $type === 'visitor')) {
            
//checking  the limit for parking 
            if (count($_SESSION['parking']['permits']) >= MAX_PARKING) {
                $message = "<div class='message error'>Parking lot is full. Cannot issue permit.</div>";
            } 
            else {  //creating a permit array
                $permit  = array(
                    'id' => 'P' . rand(1000, 9999),
                    'name' => $name,
                    'age' => $age,
                    'type' => ucfirst($type),
                    'price' => getPermitPrice($type)
                );
                
                $_SESSION['parking']['permits'][] = $permit;
                $_SESSION['parking']['totalPermits']++;
                $_SESSION['parking']['totalRevenue'] += $permit['price'];
                $_SESSION['parking']['total' . ucfirst($type)]++;
                
                $message = "<div class='message success'>Permit issued. ID: " . $permit['id'] . "</div>";
                $section = 'view';
            }
        } else {
            $message = "<div class='message error'>Invalid input. Age must be 18 or older.</div>";
        }
    }
}

$permits = isset($_SESSION['parking']['permits']) ? $_SESSION['parking']['permits'] : array();
$stats = $_SESSION['parking'];
?>

<div class="content">
    <a href="?module=home" class="back-link">Back </a>
    
    <?php echo $message; ?>
    
    <h2>Parking Management</h2>
    
    <div class="section-links">
        <a href="?module=parking&section=issue" <?php echo $section === 'issue' ? 'class="active"' : ''; ?>>Issue Permit</a>
        <a href="?module=parking&section=view" <?php echo $section === 'view' ? 'class="active"' : ''; ?>>View Permits</a>
    </div>
    
    <?php if ($section === 'issue'): ?>
        <h2>Issue New Permit</h2>
        
        <form method="POST" style="max-width: 400px;">
            <input type="hidden" name="action" value="issue_permit">
            
            <div class="form-group">
                <label>Name: </label>
                <input type="text" name="name" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Age: </label>
                    <input type="number" name="age" min="18" required>
                </div>
                
                <div class="form-group">
                    <label>Permit Type: </label>
                    <select name="type" required>
                        <option value="">Select</option>

                        <option value="student">Student  R
                            <?php echo STUDENT_PERMIT; ?>
                        </option>
                        <option value="staff">Staff  R
                            <?php echo STAFF_PERMIT; ?>
                        </option>
                        <option value="visitor">Visitor  R
                            <?php echo VISITOR_PERMIT; ?>
                        </option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Issue Permit
            </button>
        </form>
    <?php endif; ?>
    
    <?php if ($section === 'view'): ?>
        <h2>View Permits</h2>
    
        <!-- give me the overview or all transactions -->
        <div class="stats">
            <div class="stat-card">
                <h3>Total Permits</h3>
                <div class="value">
                    <?php echo $stats['totalPermits']; ?>
            </div>
            </div>
            
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <div class="value">R
                    <?php echo number_format($stats['totalRevenue']); ?>
                </div>
            </div>
            
            <div class="stat-card">
                <h3>Students</h3>
                <div class="value">
                    <?php echo $stats['totalStudent']; ?>
                </div>
            </div>
            
            <div class="stat-card">
                <h3>Staff</h3>
                <div class="value">
                    <?php echo $stats['totalStaff']; ?>
                </div>
            </div>
        </div>
        
        <?php if (!empty($permits)): ?>
            <table class="table">
                <tr>
                    <th>Permit ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Type</th>
                    <th>Price</th>
                </tr>
                
                <?php 
                // putting lattest permits first 
                $reversedPermits = array_reverse($permits);
                foreach ($reversedPermits as $permit): 
                ?>
                    <tr>
                        <td>
                            <?php echo $permit['id']; ?>
                        </td>
                        <td>
                            <?php echo $permit['name']; ?>
                        </td>
                        <td>
                            <?php echo $permit['age']; ?>
                        </td>
                        <td>
                            <span class="badge badge-success">
                                <?php echo $permit['type']; ?></span>
                            </td>
                        <td>R
                            <?php echo $permit['price']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            
            <p>No permits issued yet.</p>
        
            <?php endif; ?>

    <?php endif; ?>
</div>

