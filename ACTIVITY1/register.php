<?php
include 'connection.php';

$error = "";
$message = "";

if (isset($_POST['register'])) {

$StudentId = $_POST['StudentId'];
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $YearLevel = $_POST['YearLevel'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {

        $error = "Passwords do not match!";

    } else {

        $check = "SELECT id FROM users
                  WHERE StudentId = ? OR email = ?";

        $stmt = mysqli_prepare($connection, $check);

        mysqli_stmt_bind_param($stmt, "ss", $StudentId, $email);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {

            $error = "Student ID or Email already exists!";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users
                    (StudentId, FirstName, LastName, email,
                     course, YearLevel, password)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($connection, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $StudentId,
                $FirstName,
                $LastName,
                $email,
                $course,
                $YearLevel,
                $hashed_password
            );

            if (mysqli_stmt_execute($stmt)) {

                $message = "Registration successful! You can now sign in.";

            } else {

                $error = "Registration failed. Please try again.";

            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Portal - Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .login-container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 90%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 97%;
            padding: 10px;
            background-color: #45a049;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

    </style>
    <center>

    <div class="login-container">

        <h2>Student Registration</h2>
        <p>Create your student account</p>

        <?php if ($error != "") { ?>
            <p style="color: red;">
                <?php echo $error; ?>
            </p>
        <?php } ?>

        <?php if ($message != "") { ?>
            <p style="color: green;">
                <?php echo $message; ?>
            </p>
        <?php } ?>

        <form method="POST">

            <label><b>Student ID</b></label>
            <br>
            <input type="text" name="StudentId" placeholder = "Enter Student ID" required>
            <br>
            <label><b>First Name</b></label>
            <br>
            <input type="text" name="FirstName" placeholder = "Enter First Name" required>
            <br>
            <label><b>Last Name</b></label>
            <br>
            <input type="text" name="LastName" placeholder = "Enter Last Name" required>
            <br>
            <label><b>Email Address</b></label>
            <br>
            <input type="email" name="email" placeholder = "Enter Email" required>
            <br>
            <label><b>Course</b></label>
            <br>
            <select name="course" required>
                <option value="">Select Course</option>
                <option value="BSIS">BS Information Systems</option>
                <option value="BSOM">BS Office Management</option>
                <option value="BSAIS">BS Accounting Information System</option>
                <option value="BTVTED">Bachelor of Technical Vocational Teacher Education</option>
            </select>
            <br>
            <label><b>Year Level</b></label>
            <br>
            <select name="YearLevel" required>
                <option value="">Select Year Level</option>
                <option value="1st Year">1st Year</option>
                <option value="2nd Year">2nd Year</option>
                <option value="3rd Year">3rd Year</option>
                <option value="4th Year">4th Year</option>
            </select>
            <br>
            <label><b>Password</b></label>
            <br>
            <input type="password" name="password" placeholder = "Enter Password" required>
            <br>
            <label><b>Confirm Password</b></label>
            <br>
            <input type="password" name="confirm_password" required>
            <br><br>
            <button type="submit" name="register">Register</button>

        </form>

        <p>
            Already have an account?
            <a href="index.php">Sign In</a>
        </p>

    </div>

</center>
</body>
</html>