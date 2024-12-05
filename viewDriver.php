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

// Query to fetch all driver records
$sql = "SELECT * FROM Driver";
$result = $con->query($sql);

// Check if the user is logged in
$is_logged_in = isset($_SESSION['contact_Info']);

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM Driver WHERE Driver_Id = ?";
    $stmt = $con->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Driver deleted successfully.'); window.location.href = 'viewDriver.php';</script>";
    } else {
        echo "<script>alert('Failed to delete the driver.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bus Drivers</title>
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
            background-color: #f9f9f9;
        }

        h2 {
            color: #FFFFFFFF;
        }

        .add-driver-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .add-driver-btn:hover {
            background-color: #45a049;
        }

        .action-btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #3498db;
        }

        .edit-btn:hover {
            background-color: #2980b9;
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

    <h2>List of Bus Drivers</h2>
    <?php if ($is_logged_in): ?>
        <a href="Driver.php" class="add-driver-btn">Add Driver</a>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>License Number</th>
                    <th>Experience (years)</th>
                    <?php if ($is_logged_in): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Driver_Id']) ?></td>
                        <td><?= htmlspecialchars($row['Name']) ?></td>
                        <td><?= htmlspecialchars($row['License_Number']) ?></td>
                        <td><?= htmlspecialchars($row['Experience']) ?></td>
                        <?php if ($is_logged_in): ?>
                            <td>
                                <a href="editDriver.php?id=<?= $row['Driver_Id'] ?>" class="action-btn edit-btn">Edit</a>
                                <a href="viewDriver.php?delete_id=<?= $row['Driver_Id'] ?>" onclick="return confirm('Are you sure you want to delete this driver?');" class="action-btn delete-btn">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No drivers found in the database.</p>
    <?php endif; ?>

    <?php
    // Close connection
    $con->close();
    ?>
<a href="home.php" class="add-driver-btn">Back</a>
</body>
</html>
