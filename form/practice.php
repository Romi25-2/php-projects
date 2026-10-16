<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice</title>
</head>
<body>
    <h1>Registration</h1>
    <form action="validation.php" method="POST">
        <label>Name:</label>
        <input type="text" name="name"><br>

        <label>Email: </label>
        <input type="email" name="email"><br>

        <label>Password: </label>
        <input type="password" name="password"><br>

        <label>comment</label>
        <textarea name="comment" rows="5" cols="49"></textarea><br>

        <label>Gender:</label><br>
        
        Male<input type="radio" name="grender" value="male">
        <input type="radio" name="grender" value="female">Female
        <input type="radio" name="grender" value="other">Other<br>

        <input type="submit" name="submit" value="submit">


    </form>
</body>
</html>