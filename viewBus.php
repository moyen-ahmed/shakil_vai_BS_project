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

    $delete_sql = "DELETE FROM Bus WHERE Bus_Id = ?";
    $delete_stmt = $con->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_id);

    if ($delete_stmt->execute()) {
        echo "<script>alert('Bus deleted successfully.'); window.location.href = 'viewBus.php';</script>";
    } else {
        echo "<script>alert('Failed to delete bus. Please try again.');</script>";
    }

    $delete_stmt->close();
}

// Fetch all bus records
$sql = "SELECT * FROM Bus";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bus Stations</title>
    <style>
        /* Existing styles */
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
            margin-top: 10px;
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
            background-color: #F0F0F0FF;
        }

        h2 {
            color: #FFFFFFFF;
        }

        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<h2>Bus Information</h2>

<!-- Add Bus Button -->
<?php if ($is_logged_in): ?>
    <a href="bus.php" class="btn">Add Bus</a>
<?php endif; ?>

<div class="table-container">
    <table>
        <tr>
            <th>Bus Id</th>
            <th>Bus Number</th>
            <th>Capacity</th>
            <?php if ($is_logged_in): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Bus_Id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Bus_number']) . "</td>";
                echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
                if ($is_logged_in) {
                    echo "<td>
                        <a href='edit_bus.php?id=" . $row['Bus_Id'] . "' class='btn'>Edit</a>
                        <form method='POST' style='display:inline-block;' onsubmit=\"return confirm('Are you sure you want to delete this bus?');\">
                            <input type='hidden' name='delete_id' value='" . $row['Bus_Id'] . "'>
                            <button type='submit' class='btn'>Delete</button>
                        </form>
                    </td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No records found</td></tr>";
        }
        ?>
    </table>
</div>

<!-- Feedback Buttons -->
<a href="feedback.php" class="btn">Give Feedback</a>
<button class="btn" id="show-feedback-btn">Show Feedback</button>

<a href="home.php" class="btn">Back</a>

<!-- Modal for showing feedback -->
<div id="feedback-modal" class="modal">
    <div class="modal-content">
        <span class="close" id="close-modal">&times;</span>
        <h3>Feedback</h3>
        <div id="feedback-container">
            <?php
            $feedback_query = "SELECT * FROM feedback ORDER BY Feedback_Id";
            $feedback_result = $con->query($feedback_query);

            if ($feedback_result->num_rows > 0) {
                while ($feedback_row = $feedback_result->fetch_assoc()) {
                    echo "<p><strong>Bus Number " . htmlspecialchars($feedback_row['Bus_Id']) . ":</strong> " . htmlspecialchars($feedback_row['Feedback_Text']) . "</p>";
                    echo "<p><strong>Rating:</strong> " . (isset($feedback_row['Rating']) ? htmlspecialchars($feedback_row['Rating']) : "No Rating Provided") . "</p>";
                    
                }
            } else {
                echo "<p>No feedback available</p>";
            }
            ?>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('feedback-modal');
    const showFeedbackBtn = document.getElementById('show-feedback-btn');
    const closeModal = document.getElementById('close-modal');

    showFeedbackBtn.onclick = () => {
        modal.style.display = 'flex';
    };

    closeModal.onclick = () => {
        modal.style.display = 'none';
    };

    window.onclick = (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
</script>
</body>
</html>

<?php
$con->close();
?>
