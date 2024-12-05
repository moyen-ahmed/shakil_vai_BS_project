<?php
// Start session to track user login state
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

// Initialize search variables
$route_results = [];
$station_results = [];
$search_term_route = $search_term_station = "";

// Determine login state
$is_logged_in = isset($_SESSION['contact_Info']);

// Handle form submission for search
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_term_route = trim($_POST['search_route']);
    $search_term_station = trim($_POST['search_station']);

    // Search in the 'route' table
    if (!empty($search_term_route)) {
        $route_sql = "SELECT Route_Id, Route_Name, Total_Distance FROM route WHERE Route_Name LIKE ?";
        $stmt = $con->prepare($route_sql);
        $like_term = "%" . $search_term_route . "%";
        $stmt->bind_param("s", $like_term);
        $stmt->execute();
        $route_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    // Search in the 'bus_station' table
    if (!empty($search_term_station)) {
        $station_sql = "SELECT Station_Id, Station_name, Location, Contact_Info, Operating_Hours FROM bus_station WHERE Station_name LIKE ?";
        $stmt = $con->prepare($station_sql);
        $like_term = "%" . $search_term_station . "%";
        $stmt->bind_param("s", $like_term);
        $stmt->execute();
        $station_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Booking Platform</title>
    <style>
        /* Reset and Basic Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #ffffff;
        }

        /* Flexbox layout */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.6); /* Transparent background */
            color: white;
        }

        .logo {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #ffffff;
            font-weight: 500;
        }

        .admin-signin {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .admin-signin:hover {
            background-color: #45a049;
        }

        .mobile-menu-icon {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Main Section */
        .main-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            margin-bottom: 50px;
        }

        .main-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .search-form input {
            padding: 10px;
            width: 200px;
            border: none;
            border-radius: 4px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #45a049;
        }

        /* Footer */
        footer {
            padding: 10px 10px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .navbar {
                flex-wrap: wrap;
            }

            .mobile-menu-icon {
                display: block;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
            }

            .nav-links a {
                text-align: center;
                padding: 10px;
                background-color: #333333;
                border-radius: 4px;
            }

            .nav-links.show {
                display: flex;
            }

            .main-section h1 {
                font-size: 2rem;
            }
        }
        </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo">BUS SYSTEM</div>
            <div class="mobile-menu-icon" onclick="toggleMenu()">☰</div>
            <ul class="nav-links">
                <!-- <li><a href="#">Home</a></li> -->
                <li><a href="viewRoute.php">Route</a></li>
                <li><a href="viewBus.php">Bus</a></li>
                <li><a href="viewStatoin.php">Station</a></li>
                <li><a href="viewSchedule.php">Schedules</a></li>
                <?php if ($is_logged_in): ?>
                <li><a href="viewDriver.php">Driver</a></li>
            <?php endif; ?>
            <?php if ($is_logged_in): ?>
                <li><a href="viewFuel.php">Fuel Deatils</a></li>
            <?php endif; ?>
                <li><a href="#">Contact Us</a></li>
                <?php if (isset($_SESSION['contact_Info'])): ?>
                    <li><a href="profile.php" class="admin-signin">Profile</a></li>
                    <li><a href="logout.php" class="admin-signin">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="admin-signin">Admin Sign In</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="content">
        <!-- Main Section -->
        <main class="main-section">
            <h1>Search for Bus Tickets</h1>
            <form class="search-form" method="POST" action="">
                <input type="text" name="search_route" placeholder="Enter Bus Route" value="<?= htmlspecialchars($search_term_route) ?>">
                <input type="text" name="search_station" placeholder="Enter Bus Station" value="<?= htmlspecialchars($search_term_station) ?>">
                <button type="submit">Search</button>
            </form>

            <!-- Display Search Results -->
            <div class="search-results">
                <?php if (!empty($route_results)): ?>
                    <h2>Route Results</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Route ID</th>
                                <th>Route Name</th>
                                <th>Total Distance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($route_results as $route): ?>
                                <tr>
                                    <td><?= htmlspecialchars($route['Route_Id']) ?></td>
                                    <td><?= htmlspecialchars($route['Route_Name']) ?></td>
                                    <td><?= htmlspecialchars($route['Total_Distance']) ?> km</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <?php if (!empty($station_results)): ?>
                    <h2>Station Results</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Station ID</th>
                                <th>Station Name</th>
                                <th>Location</th>
                                <th>Contact Info</th>
                                <th>Operating Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($station_results as $station): ?>
                                <tr>
                                    <td><?= htmlspecialchars($station['Station_Id']) ?></td>
                                    <td><?= htmlspecialchars($station['Station_name']) ?></td>
                                    <td><?= htmlspecialchars($station['Location']) ?></td>
                                    <td><?= htmlspecialchars($station['Contact_Info']) ?></td>
                                    <td><?= htmlspecialchars($station['Operating_Hours']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <?php if (empty($route_results) && empty($station_results) && $_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                    <p>No results found for your search criteria.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Footer -->
 

    <script>
        function toggleMenu() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('show');
        }
    </script>
</body>
<footer>
        <p>&copy; 2024 Bus System. All Rights Reserved.</p>
    </footer>
</html>