<?php
session_start();
include 'connection.php';

$error = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt === false) {
            $error = "Database error: Failed to prepare statement.";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                if (password_verify($password, $user['password'])) {

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['name'] = $user['FirstName'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] == 'admin') {
                        header("Location: admin_dashboard.php");
                        exit();
                    } else {
                        header("Location: student_dashboard.php");
                        exit();
                    }

                } else {
                    $error = "Incorrect password!";
                }

            } else {
                $error = "Account not found!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Portal - Sign In</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <style>
        
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }

        .login-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            text-align: left;
        }

        input[type="email"],
        input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 97%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        p {
            text-align: center;
        }

    </style>

    <center>

    <div class="login-container">

        <h2>Student Portal</h2>
        <p>Sign in to your account</p>

        <?php if ($error != "") { ?>
            <p style="color: red;">
                <?php echo $error; ?>
            </p>
        <?php } ?>

        <form method="POST">

            <label><b>Email Address</b></label>
            <br>
            <input type="email" name="email" placeholder = "Enter Email" required>
            <br><br>
            <label><b>Password</b></label>
            <br>
            <input type="password" name="password" placeholder = "Enter Password" required>
            <br><br>
            <button type="submit" name="login">
                Sign In
            </button>

        </form>

        <p>
            Don't have an account yet?
            <a href="register.php" style = "font-size: 14px;">Register here</a>
        </p>

    </div>

</center>
</body>
</html>