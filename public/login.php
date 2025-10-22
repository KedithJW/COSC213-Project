

<?php
// Right now, there is no php backend, so we simulate login with JavaScript, i just copied the code from a prev project. It can be changed later once we have a backend and i am more familiar with php. 
// The home button is also set to go to Homepage.html, becuase i dont know what page we are making the home page. (very easy change) 
// The temp password is "password" and the temp username is "admin" as shown in line 125, it should just send you back the previous page on success, the code is literally hitting chromes back button.
// I also eyeballed the coulors and stuff, it can be changed later too.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Manager</title>
    <style>
        body {
            background-color: #007BFF;
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
            background-color: #333;
            padding: 10px;
            position: absolute;
            top: 0;
            left: 0;
        }

        .header button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        .login-form {
            background-color: #ffffff;
            padding: 30px;
            width: 400px;
            border: 1px solid #ccc;
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
    <button onclick="location.href='HomePage.php';">HOME</button>
</div>

<div class="login-form">
    <h2>Login</h2>
    <form id="loginForm">
        <input type="text" id="username" placeholder="Username" required>
        <input type="password" id="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p id="message"></p>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            var username = $('#username').val();
            var password = $('#password').val();

            if (username.length < 3 || username.length > 20) {
                $('#message').text('Username must be between 3 and 20 characters.');
                return;
            }
            if (password.length < 6 || password.length > 20) {
                $('#message').text('Password must be between 6 and 20 characters.');
                return;
            }

            setTimeout(function() {
                var WeNoHaveServer = {
                    success: (username === 'admin' && password === 'password')
                };
                if (WeNoHaveServer.success) {
                    localStorage.setItem('loggedInUser', username);
                    $('#message').css('color', 'green').text('Login successful!');
                    window.history.back();
                } else {
                    $('#message').text('Invalid username or password.');
                }
            }, 50);
        });
    });
</script>

</body>
</html>