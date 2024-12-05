<?php
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

// Ensure the user is logged in
if (!isset($_SESSION['contact_Info'])) {
    echo "<script>alert('You must be logged in to access this page.'); window.location.href = 'login.php';</script>";
    exit();
}

// Check if Route_Id is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid route ID.'); window.location.href = 'view_route.php';</script>";
    exit();
}

$route_id = $_GET['id'];

// Fetch the existing route details
$sql = "SELECT * FROM Route WHERE Route_Id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $route_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    echo "<script>alert('Route not found.'); window.location.href = 'view_route.php';</script>";
    exit();
}

$route = $result->fetch_assoc();

// Handle form submission to update the route
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $route_name = $_POST['route_name'];
    $total_distance = $_POST['total_distance'];

    // Update query
    $update_sql = "UPDATE Route SET Route_Name = ?, Total_Distance = ? WHERE Route_Id = ?";
    $update_stmt = $con->prepare($update_sql);
    $update_stmt->bind_param("sdi", $route_name, $total_distance, $route_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Route updated successfully.'); window.location.href = 'view_route.php';</script>";
    } else {
        echo "<script>alert('Failed to update the route. Please try again.');</script>";
    }

    $update_stmt->close();
}

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Route</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
            padding: 20px;
        }

        .edit-form {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .edit-form h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .edit-form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .edit-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .edit-form button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .edit-form button:hover {
            background-color: #45a049;
        }

        .edit-form .cancel-btn {
            background-color: #e74c3c;
        }

        .edit-form .cancel-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <form method="POST" class="edit-form">
        <h2>Edit Route</h2>
        <label for="route_name">Route Name:</label>
        <input type="text" id="route_name" name="route_name" value="<?php echo htmlspecialchars($route['Route_Name']); ?>" required>
        
        <label for="total_distance">Total Distance (km):</label>
        <input type="number" step="0.01" id="total_distance" name="total_distance" value="<?php echo htmlspecialchars($route['Total_Distance']); ?>" required>
        
        <button type="submit">Update Route</button>
        <a href="viewRoute.php" class="cancel-btn" style="text-align:center; display: block; margin-top: 10px;">Cancel</a>
    </form>
</body>
</html>
