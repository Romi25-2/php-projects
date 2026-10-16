<?php
// functions for parking, library and performans  files 

// PARKING FUNCTIONS
// parking prices constant
define('STUDENT_PERMIT', 450);
define('STAFF_PERMIT', 750);
define('VISITOR_PERMIT', 100);
define('MAX_PARKING', 500);

// function for parking.php
function getPermitPrice($type) {

    $type = strtolower($type);
    if ($type == "student") {
        return STUDENT_PERMIT;
    }
    if ($type == "staff") {
        return STAFF_PERMIT;
    }
    if ($type == "visitor") {
        return VISITOR_PERMIT;
    }
    return 0;
}

// function for library
function calculateFine($daysLate, $dailyRate) {

    $fine = $daysLate * $dailyRate;
    return $fine;
}

function getBookDailyRate($category) {
    $category = strtolower($category);

    if ($category == "textbook") {
        return 5;
    }
    if ($category == "journal") {
        return 3;
    }
    if ($category == "reference book") {
        return 10;
    }
    return 0;
}

function getBookCategory($input) {
    $input = strtolower($input);

    if ($input == "1" || $input == 'textbook') {
        return "Textbook";
    }
    if ($input == "2" || $input == 'journal') {
        return "Journal";
    }
    if ($input == "3" || $input == 'reference' || $input == 'reference book') {
        return "Reference Book";
    }
    return "";
}
    
// function for performance 
function isValidMark($mark) {
    if (!is_numeric($mark)) {
        return false;
    }

    if ($mark < 0 || $mark > 100) {
        return false;
    }
    return true;
}

function calculateAverage($marks) {
    if (empty($marks)) {
        return 0;
    } 
    $total = array_sum($marks);
    $average = $total/ count($marks);

    return $average;
}

function assignGrade($average) {
    if ($average >= 70)  {
        return "Distinction";
    }elseif($average >= 50) {
        return "Pass";
    }else {
        return "Fail";
    }
}

function getStudentPerformanceStats($students) {
    if (empty($students)){
         return null;
    }
    $averages = array();
    $topStudent = "";
    $topAverage = 0;
    
    foreach ($students as $id => $student) {
        $avg = $student['average'];
        $averages[] = $avg;

        if ($avg > $topAverage) {

            $topAverage = $avg;

            $topStudent = array(
                'id' => $id,
                'name' => $student['name'],
                'average' => $avg
            );
        }
    }
    
    return array(
        'top_student' => $topStudent,
        'highest_average' => max($averages),
        'lowest_average' => min($averages),
        'class_average' => calculateAverage($averages)
    );
}
?>
