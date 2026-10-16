<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php
    const RESTUARANT_NAME = "Food Palace";
    $customerName = "Lerato ";
    $FoodItem = "Burger ";
    $Price_perItem = 85.50;
    $Qauntity_ordered = 3;
    $DiscountRate = 0.10;
    $Order_type ="delivery" ;

    
   $subTotal = $Price_perItem * $Qauntity_ordered;
   echo"Your subtotal is :  $subTotal <br>";
    

    $discountAmount = $subTotal / $DiscountRate; //discount ammount

    //final total 
    $FinalTotal = $subTotal - $discountAmount;

    if($subTotal >=100 && $subTotal <= 300){
        echo"Standard Discount <br>";
    }
    elseif($subTotal > 300){
        echo"VIP Discount<br>";
    }
    else{
        echo"No Discount <br> ";
    }

switch($Order_type){
    case "sit-in":
        printf("Customer will eat at the restaurant. <br>");
        break;
    case "takeaway":
        printf("Order will be packed for takeway.<br>");
        break;
    case "delivery":
        printf("Order will be delivered to the customer. <br>");
        break;
    default:
    printf("Invalid order type selected. <br>");
}

for ($FoodItem = 1; $FoodItem <3 ; $FoodItem++){
        echo $FoodItem;
}
    ?>
</body>
</html>