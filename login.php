<?php
    echo "Hello world!";
    echo "How are you today?";
    echo "my name is mehdi";
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login Page</title>
    </head>
    <body>
        <h2>Login Page</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>
    </body>
    </html>