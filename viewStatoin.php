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

// Check if the user is logged in
$is_logged_in = isset($_SESSION['contact_Info']);

// Handle delete operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id']) && $is_logged_in) {
    $delete_id = $_POST['delete_id'];

    $delete_sql = "DELETE FROM Bus_Station WHERE Station_Id = ?";
    $delete_stmt = $con->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);

    if ($delete_stmt->execute()) {
        echo "<script>alert('Station deleted successfully.'); window.location.href ='viewStatoin.php';</script>";
    } else {
        echo "<script>alert('Failed to delete station. Please try again.');</script>";
    }

    $delete_stmt->close();
}

// Fetch all records from Bus_Station table
$sql = "SELECT * FROM Bus_Station";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bus Stations</title>
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
            margin: 0 5px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }

        .edit-btn {
            background-color: #007BFF;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #e74c3c;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<h2>Bus Stations</h2>

<?php if ($is_logged_in): ?>
    <a href="station.php" class="add-route-btn">Add Station</a>
<?php endif; ?>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Station ID</th>
                <th>Station Name</th>
                <th>Location</th>
                <th>Contact Info</th>
                <th>Operating Hours</th>
                <?php if ($is_logged_in): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Station_Id']) ?></td>
                        <td><?= htmlspecialchars($row['Station_name']) ?></td>
                        <td><?= htmlspecialchars($row['Location']) ?></td>
                        <td><?= htmlspecialchars($row['Contact_Info']) ?></td>
                        <td><?= htmlspecialchars($row['Operating_Hours']) ?></td>
                        <?php if ($is_logged_in): ?>
                            <td>
                                <a href="editStation.php?id=<?= $row['Station_Id'] ?>" class="action-btn edit-btn">Edit</a>
                                <form method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this station?');">
                                    <input type="hidden" name="delete_id" value="<?= $row['Station_Id'] ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $is_logged_in ? 6 : 5 ?>">No bus station records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
$con->close();
?>
<a href="home.php" class="add-route-btn">Back</a>
</body>
</html>
