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

// Ensure user is logged in
if (!isset($_SESSION['contact_Info'])) {
    echo "<script type='text/javascript'>
            alert('You must log in first to view this page.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

// Fetch logged-in user details
$contact_Info = $_SESSION['contact_Info'];
$sql = "SELECT * FROM Employee WHERE Contact_Info = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $contact_Info);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script type='text/javascript'>
            alert('Profile not found.');
            window.location.href = 'login.php';
          </script>";
    exit();
}

// Close the database connection
$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('7.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-container {
            display: flex;
            align-items: flex-start;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 700px;
        }

        .profile-image {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-image img {
            border-radius: 10px;
            border: 2px solid #4CAF50;
            width: 250px;
            height: 250px;
            object-fit: cover;
        }

        .profile-details {
            flex: 2;
            padding-left: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .profile-details h2 {
            margin-bottom: 20px;
            text-align: left;
            color: #333;
        }

        .profile-details div {
            display: flex;
            justify-content: space-between;
        }

        .profile-details div span {
            font-weight: bold;
            color: #4CAF50;
        }

        .logout-btn {
            margin-top: 20px;
            display: block;
            text-align: center;
            padding: 10px 0;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .logout-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <!-- Profile Image Section -->
    <div class="profile-image">
        <?php if (!empty($user['ppic'])): ?>
            <img src="<?= htmlspecialchars($user['ppic']) ?>" alt="Profile Picture">
        <?php else: ?>
            <img src="default-profile.png" alt="Default Profile Picture">
        <?php endif; ?>
    </div>

    <!-- Profile Details Section -->
    <div class="profile-details">
        <h2>User Profile</h2>
        <div>
            <span>Name:</span>
            <span><?= htmlspecialchars($user['Name']) ?></span>
        </div>
        <div>
            <span>Role:</span>
            <span><?= htmlspecialchars($user['Role']) ?></span>
        </div>
        <div>
            <span>Contact Info:</span>
            <span><?= htmlspecialchars($user['Contact_Info']) ?></span>
        </div>
        <div>
            <span>Shift Schedule:</span>
            <span><?= htmlspecialchars($user['Shift_Schedule']) ?></span>
        </div>
        <div>
            <span>Station ID:</span>
            <span><?= htmlspecialchars($user['Station_Id']) ?></span>
        </div>
        <a href="logout.php" class="logout-btn">Logout</a>
        <a href="home.php" class="logout-btn">Back</a>
    </div>
    
</div>

</body>
</html>
