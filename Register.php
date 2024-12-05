<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Insert Employee</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="Driver">Driver</option>
                <option value="Manager">Manager</option>
                <option value="Bus_Supervisor">Bus Supervisor</option>
            </select>

            <label for="contact_info">Contact Info:</label>
            <input type="text" id="contact_info" name="contact_Info">

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="shift_schedule">Shift Schedule:</label>
            <input type="text" id="shift_schedule" name="shift_schedule">

            <label for="ppic">Upload Picture:</label>
            <input type="file" id="ppic" name="ppic" accept="image/*" required>

            <button type="submit">Insert Employee</button>
        </form>
    </div>
</body>
</html>

<?php
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

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $role = $_POST['role'];
    $contact_Info = $_POST['contact_Info'];
    $password = $_POST['password'];
    $shift_schedule = $_POST['shift_schedule'];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["ppic"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $allowed_types)) {
        if (move_uploaded_file($_FILES["ppic"]["tmp_name"], $target_file)) {
            $ppic = $target_file;
        } else {
            die("Error uploading the file.");
        }
    } else {
        die("Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Fetch Station_Id dynamically
    $station_query = "SELECT Station_Id FROM Bus_Station LIMIT 1";
    $result = $con->query($station_query);

    if (!$result) {
        die("Query failed: " . $con->error);
    }

    if ($result->num_rows > 0) {
        $station_row = $result->fetch_assoc();
        $station_Id = $station_row['Station_Id'];

        // Insert query
        $sql = "INSERT INTO Employee (`Name`, `Password`, `Role`, `Contact_Info`, `Shift_Schedule`, `Station_Id`, `Ppic`) 
                VALUES ('$name', '$password', '$role', '$contact_Info', '$shift_schedule', $station_Id, '$ppic')";

        if ($con->query($sql) === TRUE) {
            echo "<script type='text/javascript'>
                    alert('Employee added successfully!');
                    window.location.href = 'home.php';
                  </script>";
        } else {
            die("Error: " . $sql . "<br>" . $con->error);
        }
    } else {
        die("No station found in the database.");
    }
}

// Close the connection
$con->close();
?>
