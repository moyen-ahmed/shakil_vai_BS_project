<?php


// Database connection
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'bus_system';

$con = new mysqli($server, $username, $password, $database);



// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    
    $Station_name = $_POST['Station_name'];
    $Location = $_POST['Location'];
    $Contact_Info = $_POST['Contact_Info'];
    $Operating_Hours = $_POST['Operating_Hours'];

    // Insert query
    $sql = "INSERT INTO Bus_Station (Station_name, Location, Contact_Info, Operating_Hours)
            VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $Station_name, $Location, $Contact_Info, $Operating_Hours);

    if ($stmt->execute()) {
        echo "<script>alert('Station updated successfully.'); window.location.href ='viewStatoin.php';</script>";
    } else {
        $message = "Error: Could not insert record - " . $stmt->error;
    }

    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Bus Station Record</title>
    <style>
        /* Basic styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            max-width: 90%; /* For responsiveness */
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
        textarea {
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

        /* Responsive styles */
        @media (max-width: 768px) {
            .form-container {
                width: 100%;
                padding: 15px;
            }
        }

        /* Message style */
        .message {
            text-align: center;
            color: #4CAF50;
            margin-top: 10px;
        }

        .error {
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Insert Bus Station Record</h2>
        <form method="POST" action="">
            <label for="station_name">Station Name:</label>
            <input type="text" id="station_name" name="Station_name" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="Location" required>

            <label for="contact_info">Contact Info:</label>
            <input type="text" id="contact_info" name="Contact_Info">

            <label for="operating_hours">Operating Hours:</label>
            <input type="text" id="operating_hours" name="Operating_Hours" placeholder="6:00 AM - 10:00 PM">

            <button type="submit">Insert Bus Station</button>
        </form>
       
    </div>
</body>
</html>
