<?php
require_once 'functions.php';

$message = "";// for user input

$section = isset($_GET['section']) ? $_GET['section'] : 'add';

if ($_POST) {  // is my form submitted validation
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    //adding new student
    if ($action === 'add_student') {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $marks = array();
        $valid = true;
        
        // let me set the limit to 6 subjects 
        for ($i = 1; $i <= 6; $i++) {  
            //a/input  of mark values 
            $mark = isset($_POST["mark$i"]) ? intval($_POST["mark$i"]) : 0;
            
            // validate my marks
           if (!isValidMark($mark)) {
                $valid = false;
                break;
            }
            $marks[] = $mark;
        }
        
        ///proceed of the name is fulled and marks are filled 
        if ($name !=""  && $valid == true ) {
            //giving ids automatically 
            $studentId = 'S' . str_pad(
                $_SESSION['performance']['studentIdCounter']++,
                 3, '0', STR_PAD_LEFT
                 );
            $average = calculateAverage($marks);  //calulating student marks 
            $grade = assignGrade($average);
            
            // saving all students into sessions
            $_SESSION['performance']['students'][$studentId] = array(
                'name' => $name,
                'marks' => $marks,
                'average' => $average,
                'grade' => $grade,
                'added_date' => date('Y-m-d')
            );
            
            // leave a message when everything is complete to let
            //them know that student has been addded 
            $message = "<div class='message success'>
            Student added successfully. ID: $studentId
            </div>";
            $section = 'add'; // back to add section
        } else {
            // send message when improper input is given
            $message = "<div class='message error'>Invalid marks. Must be between 0 and 100.</div>";
        }
    }
}

//fetch students if avaliable 
        $students = isset($_SESSION['performance']['students'])
            ? $_SESSION['performance']['students'] : array();

            // get student stats
        $stats = getStudentPerformanceStats($students);
    ?>

<div class="content">
    <a href="?module=home" class="back-link">Back to Home</a>
    
    <?php echo $message; ?>
    
    <h2>Performance Management</h2>
    
    <div class="section-links">  <!-- navigation -->
        <a href="?module=performance&section=add" 
        <?php echo $section === 'add' ? 'class="active"' : ''; ?>
        >Add Student</a>

        <a href="?module=performance&section=view"
         <?php echo $section === 'view' ? 'class="active"' : ''; ?>
         >View Students</a>
    </div>
    
    <?php if ($section === 'add'): ?>
        <h2>Add New Student</h2>
        
        <form method="POST" style="max-width: 500px;">
            <input type="hidden" name="action" value="add_student">
            
            <div class="form-group">
                <label>Student Name *</label>
                <input type="text" name="name" required>
            </div>
            
            <p style="font-weight: bold; margin-bottom: 10px;">Enter 6 Marks (0-100) *</p>
            
            <div class="form-row-3">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <div class="form-group">
                        <label>Mark <?php echo $i; ?></label>
                        <input type="number" name="mark<?php echo $i; ?>" min="0" max="100" required>
                    </div>
                <?php endfor; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">
                Add Student</button>
        </form>
    <?php endif; ?>
    
    <?php if ($section === 'view'): ?>
        <h2>Student Performance</h2>
        
        <?php if ($stats): ?>
            <div class="stats">
                <div class="stat-card">
                    <h3>Class Average</h3>
                    <div class="value">
                        <?php echo number_format($stats['class_average'], 1); ?>%</div>
                </div>
                
                <div class="stat-card">
                    <h3>Highest Average</h3>
                    <div class="value">
                        <?php echo number_format($stats['highest_average'], 1); ?>%</div>
                </div>
                
                <div class="stat-card">
                    <h3>Lowest Average</h3>
                    <div class="value">
                        <?php echo number_format($stats['lowest_average'], 1); ?>%</div>
                </div>
                
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <div class="value">
                        <?php echo count($students); ?></div>
                </div>
            </div>
            
            <?php if ($stats['top_student']): ?>
                <div style="background-color: #f0f0f0; padding: 12px; margin: 15px 0; border: 1px solid #ddd;">
                    <h3>Top Student</h3>
                    <p><?php echo $stats['top_student']['name'] . " (" . $stats['top_student']['id'] . ")"; ?></p>
                    <p>Average: 
                        <?php echo number_format($stats['top_student']['average'], 1); ?>%</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if (!empty($students)): ?>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Average</th>
                    <th>Grade</th>
                    <th>Marks</th>
                </tr>
                
                <?php foreach ($students as $id => $student): ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><?php echo number_format($student['average'], 1); ?>%</td>
                        <td><span class="badge"><?php echo $student['grade']; ?></span></td>
                        <td><?php echo implode(', ', $student['marks']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No students added yet.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>


