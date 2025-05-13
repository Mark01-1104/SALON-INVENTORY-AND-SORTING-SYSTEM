<?php
session_start();
include('../config/config.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$submissionMessage = null; // Initialize the success message variable
$submissionError = null;   // Initialize the error message variable

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Handle report submission
if (isset($_POST['report_content'])) {
    $report_content = mysqli_real_escape_string($conn, $_POST['report_content']);
    $submitted_by = $username; // Get the username of the logged-in user

    $query = "INSERT INTO reports (report_content, submitted_by, created_at) VALUES ('$report_content', '$submitted_by', NOW())";

    if (mysqli_query($conn, $query)) {
        $submissionMessage = "Report submitted successfully!";
    } else {
        $submissionError = "Error submitting report: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        .success-message, .error-message {
            opacity: 1;
            transition: opacity 1s ease-in-out;
        }
        .fade-out {
            opacity: 0;
        }
    </style>
</head>
<body>
<header>
    <div class="header-left">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p>Role: <?php echo htmlspecialchars($role); ?></p>
    </div>
    <div class="header-right">
        <form method="post">
            <button type="submit" name="logout" class="logout-btn">Log Out</button>
        </form>
    </div>
</header>
<div class="main-content">
    <div class="sidebar">
        <a href="employee_dashboard.php" class="active">Dashboard</a>
    </div>
    <div class="dashboard-container">
        <h2>Submit Report</h2>
        <div id="submission-status">
            <?php if ($submissionMessage): ?>
                <p class="success-message"><?php echo htmlspecialchars($submissionMessage); ?></p>
            <?php endif; ?>
            <?php if ($submissionError): ?>
                <p class="error-message"><?php echo htmlspecialchars($submissionError); ?></p>
            <?php endif; ?>
        </div>
        <form method="POST" action="" class="form-add">
            <textarea name="report_content" placeholder="Write your report..." required></textarea>
            <button type="submit">Submit Report</button>
        </form>

        <h2>Inventory</h2>
        <input type="text" id="inventorySearch" class="search-input" placeholder="Search Inventory...">
        <table class="styled-table" id="inventoryTable">
            <thead>
                <tr><th>ID</th><th>Product Name</th><th>Stock</th><th>Price</th></tr>
            </thead>
            <tbody>
                <?php
                $inventory = mysqli_query($conn, "SELECT * FROM inventory");
                while ($row = mysqli_fetch_assoc($inventory)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['product_name']}</td>
                            <td>{$row['stock']}</td>
                            <td>₱{$row['price']}</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
document.getElementById('inventorySearch').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#inventoryTable tbody tr');
    rows.forEach(row => {
        row.style.display = [...row.children].some(td =>
            td.textContent.toLowerCase().includes(filter)
        ) ? '' : 'none';
    });
});

// JavaScript to make the submission status message disappear after 10 seconds
const submissionStatus = document.getElementById('submission-status');
if (submissionStatus.children.length > 0) {
    setTimeout(() => {
        submissionStatus.classList.add('fade-out');
        setTimeout(() => {
            submissionStatus.innerHTML = ''; // Clear the content after the fade out
            submissionStatus.classList.remove('fade-out'); // Remove the class for future messages
        }, 1000); // Wait for the transition to complete (1 second)
    }, 3000); 
}
</script>
</body>
</html>