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

// Query to fetch all route records
$sql = "SELECT * FROM Route";
$result = $con->query($sql);

// Check if the user is logged in
$is_logged_in = isset($_SESSION['contact_Info']);

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_root_id'])) {
    $schedule_id = intval($_POST['delete_root_id']);
    $delete_sql = "DELETE FROM route WHERE Route_Id = ?";
    $stmt = $con->prepare($delete_sql);
    $stmt->bind_param("i", $schedule_id);
    if ($stmt->execute()) {
        echo "<script>alert('Schedule deleted successfully.'); window.location.viewRoute.php;</script>";
    } else {
        echo "<script>alert('Failed to delete schedule.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Route Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('1.jpg') no-repeat center center fixed;
            background-size: cover;
            
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin: 0;
        }

        table {
            width: 80%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        td {
            background-color: #FFFFFFFF;
        }

        h2 {
            color: #FFFFFFFF;
        }

        .add-route-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .add-route-btn:hover {
            background-color: #45a049;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #007bff;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    
    <h2>List of Bus Routes</h2>
   
    <!-- Show "Add Route" button only if logged in -->
    <?php if ($is_logged_in): ?>
        <a href="route.php" class="add-route-btn">Add Route</a>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Route Name</th>
                    <th>Total Distance (km)</th>
                    <?php if ($is_logged_in): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['Route_Id']; ?></td>
                        <td><?php echo htmlspecialchars($row['Route_Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Total_Distance']); ?></td>
                        <?php if ($is_logged_in): ?>
                            <td>
                                <!-- Edit Button -->
                                <a href="edit_route.php?id=<?php echo $row['Route_Id']; ?>" class="action-btn edit-btn">Edit</a>

                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="delete_root_id" value="<?php echo $row['Route_Id']; ?>">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this route?')">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No routes found in the database.</p>
    <?php endif; ?>
    <a href="home.php" class="add-route-btn">Back</a>
</body>
</html>
