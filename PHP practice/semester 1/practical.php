<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<?php
$students = array(
    "Chloe"=>65 , "Amy"=>78 ,
    "Jimmy"=>45 , "Solomon"=>93 ,
    "Milo"=>32 , "Jamie"=>88 ,
    "Tim"=>58 , "Prudence"=>57 ,
    "Micheal"=>23 , "Angie"=>67
);

foreach($students as $name => $mark)
{
    // Pass/Fail
    if($mark >= 50){
        echo "$name - $mark : You have passed <br>";
    } else {
        echo "$name - $mark : You have failed <br>";
    }
    
    // Grade classification
    if($mark >= 75 && $mark <= 100) {
        echo "$name - $mark : Distinction <br>";
    } elseif($mark >= 60 && $mark <= 74) {
        echo "$name - $mark : Merit <br>";
    } elseif($mark >= 50 && $mark <= 59) {
        echo "$name - $mark : Pass <br>";
    } else {
        echo "$name - $mark : Fail <br>"; 
    }
   
    // Switch statement
    switch(true) {
        case ($mark >= 75 && $mark <= 100):
            echo "Excellent work<br>";
            break;
        case ($mark >= 60 && $mark <= 74):
            echo "Good job<br>";
            break;
        case ($mark >= 50 && $mark <= 59):
            echo "You passed<br>";                        
            break;
        default:
            echo "Try again<br>";
    }

}    
 echo"<br>";

$total_students = count($students);
echo "$total_students Total students <br>";

 $passed = count(array_filter($students, function($mark){
   return $mark >= 50; 
 }));
echo $passed . " Students passed <br>";

$failed = count($students) - $passed;
echo $failed . " Students failed <br>";


echo"<br>";

$highest = max($students);
echo"$highest is the highest mark <br>";

$lowest =  min($students);
echo"$lowest is the lowest mark ";
echo"<br>";

echo"<br>";

$total_students = count($students);

$i = 1;

while ($i <= $total_students){
    echo $i . "<br>";
    $i++;
}

echo"<br>";
$total_marks = array_sum($students);
$average = $total_marks / $total_students;
echo"Average Mark: $average";

if($average >= 70){
    echo" Overall Performance: Excellent";
}
elseif($average >=50 && $average <=59 ){
    echo" Overall Performance:Satisfactory";
}
else{
    echo" Overall Performance: Poor";
}
?>             
</body>
</html>
