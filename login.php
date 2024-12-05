<?php
// Start the session
session_start();

// Database connection
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'bus_system';

$con = new mysqli($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contact_Info = $_POST['contact_Info'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to fetch the user
    $sql = "SELECT * FROM Employee WHERE contact_Info = ? AND password = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $contact_Info, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a matching record was found
    if ($result->num_rows == 1) {
        // Set session variable for logged-in user
        $_SESSION['contact_Info'] = $contact_Info;
        echo "<script>window.location.href = 'home.php';</script>";
    } else {
        echo "<script>alert('Invalid credentials. Please try again.');</script>";
    }

    // Close statement and connection
    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Styles for login form */
        body {
            font-family: Arial, sans-serif;
            background: url('54.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            
        }

        .form-container {
            background-color:whitesmoke;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
           
        }

        h2 {
            text-align: center;
            color: #333333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #333333;
        }

        input[type="text"],
        input[type="password"] {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <label for="name">Contact Info:</label>
            <input type="text" id="contact_Info" name="contact_Info" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
        <!-- <li><a href="Register.php" class="admin-signin">Create new Account!</a></li> -->
    </div>
</body>
</html>
