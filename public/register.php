<?php
/*
    Notes/stuff to talk about with team
    Testing registration page, connects to database and adds user info if valid, but i never actually got a chance to test the conencting to a database part. Couldnt figure out how to set one up on my local machine
    -- shouldnt be much of a difference to the code here just changing the connection parameters $host, $db etc right below this paragraph. 
    I also saw on Trello's sign up page then has some terms and conditions, we could add something like that to fill the void, as well a some photos in the bottom and a the Brand name, ex Trello has "Trello" "terms and condition, then a sign up button.
    Colour and stuff can also be changed as I built it off the login.php. this would be a seperate file tho. this is register.php that redirects to login.php on successful registration
    ------ I also made some notes throughout the file, re the dropdown and some of the security stuff, idk if we want to keep that sec stuff or not but its there if we do. Wasnt very hard to implement so idc if we get rid of it.
    Re the Dropdown i also added the alternate code in the html section at the bottom, just a Already have an acount thingy.
    Do we want to add a confirm password field as well, and should be add more checks for a secure passwrd like a !@#%^& requirment.
*/
$host = 'localhost'; // Change these to our db info
$db   = 'database_name';
$user = 'db_user';
$pass = 'db_password';

$message = '';
$success = false;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstName = trim($_POST['firstName']);
        $lastName  = trim($_POST['lastName']);
        $email     = trim($_POST['email']);
        $phone     = trim($_POST['phone']);
        $username  = trim($_POST['username']);
        $password  = $_POST['password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // php's built in email validation
            $message = "Invalid email format.";
        } elseif (strlen($username) < 3 || strlen($username) > 20) {
            $message = "Username must be between 3 and 20 characters.";
        } elseif (strlen($password) < 6 || strlen($password) > 20) {
            $message = "Password must be between 6 and 20 characters.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username"); 
            $stmt->execute(['email' => $email, 'username' => $username]);
            //This checks for uniqueness, we cant have 2 emails/usersnames in the db that are the same 

            if ($stmt->rowCount() > 0) {
                $message = "Email or username already exists.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // requiremnt to hash passwords, usese php's default hashing alogrithm
            // uses PDO prepared statements to prevent SQL injection per project requirments.
                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, username, password) 
                                       VALUES (:first_name, :last_name, :email, :phone, :username, :password)");
                $stmt->execute([
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                    'email'      => $email,
                    'phone'      => $phone,
                    'username'   => $username,
                    'password'   => $hashedPassword
                ]);

                header("Location: login.php"); // Redirect to login page after successful registration
                exit;
            }
        }
    }        
} catch (PDOException $e) {
    $message = "Database error: " . $e->getMessage(); // says when we arnt connected to a db
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Project Manager</title>
    <style>
        body {
            background-color: white;
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .header {
            width: 100%;
            background-color: rgba(51, 51, 51, 0.9);
            padding: 10px 20px;
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .header .left button,
        .dropbtn {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            padding: 10px;
            margin: 0;
        }

        .header .right {
            position: relative;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 120px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 10px 12px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .login-form {
            background-color: #ffffff;
            padding: 30px;
            width: 400px;
            border: 1px solid #ccc;
            margin-top: 80px;
        }

        .login-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #aaa;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
        }

        #message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="left">
        <button onclick="location.href='HomePage.php';">App Name</button>
    </div>
    <div class="right">
        <div class="dropdown">
            <button class="dropbtn">Accounts</button>
            <div class="dropdown-content">
                <a href="login.php">Login</a>
                <a href="justforshow.php"> justforshow</a>
            </div>
        </div>
    </div>
</div>

<div class="login-form">
    <h2>Register Your New Account</h2>
    <form method="post" action="register.php">
        <input type="text" name="firstName" placeholder="First Name" required>
        <input type="text" name="lastName" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
        <!-- <p> Want to login instead? </p> <a href="login.php">Click here</a></p> -->
    </form>

    <?php
    if ($message) {
        echo '<p id="message">' . htmlspecialchars($message) . '</p>';  
        // was suggested in videos i watched about sql injection stuff, this prevents html injection / cross site scripting i dont rlly know to much about it tho, this is done by the htrmlspecialchars function
    }
    ?>
</div>

</body>
</html>