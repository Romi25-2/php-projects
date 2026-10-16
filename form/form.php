<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form integrated with html </title>
</head>
<body>
    <form action="process.php" method="post">
        <label>Name</label>
        <input type="text" name="name"  /><br>

        <label>Surname </label>
        <input type="text" name="surname"/><br>

        <label>Date of Borth </label>
        <input type="date" name="dateOfBirth"/><br>

        <label>Qaulififcation </label>
        <input type="text" name="qaulif"/><br>

        <label>Email </label>
        <input type="email" name="email" /><br>

        <label>Gender</label><br>

        <input type="radio" name="gender"/>Male
        <input type="radio" name="gender"/>Female
        <input type="radio" name="gender"/>Other <br>

        <input type="submit" name="submit" >


    </form>
    
</body>
</html>