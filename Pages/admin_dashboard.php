<?php
session_start();
include('../config/config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Add user
if (isset($_POST['add_user'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $new_role = mysqli_real_escape_string($conn, $_POST['role']);

    $insert_user = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$new_password', '$new_role')";
    mysqli_query($conn, $insert_user);
    header("Location: admin_dashboard.php?tab=users");
    exit();
}

// Update user
if (isset($_POST['update_user'])) {
    $edit_id = (int)$_POST['edit_id'];
    $edit_username = mysqli_real_escape_string($conn, $_POST['edit_username']);
    $edit_role = mysqli_real_escape_string($conn, $_POST['edit_role']);

    $update_user = "UPDATE users SET username='$edit_username', role='$edit_role' WHERE id=$edit_id";
    mysqli_query($conn, $update_user);
    header("Location: admin_dashboard.php?tab=users");
    exit();
}

// Delete user
if (isset($_GET['delete_user'])) {
    $delete_id = (int)$_GET['delete_user'];
    mysqli_query($conn, "DELETE FROM users WHERE id=$delete_id");
    header("Location: admin_dashboard.php?tab=users");
    exit();
}

// Add inventory
if (isset($_POST['add_inventory'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $stock = (int)$_POST['stock'];
    $price = (float)$_POST['price'];

    $insert_inventory = "INSERT INTO inventory (product_name, stock, price) VALUES ('$product_name', $stock, $price)";
    mysqli_query($conn, $insert_inventory);
    header("Location: admin_dashboard.php?tab=inventory");
    exit();
}

// Update inventory
if (isset($_POST['update_inventory'])) {
    $edit_id = (int)$_POST['edit_id'];
    $edit_product_name = mysqli_real_escape_string($conn, $_POST['edit_product_name']);
    $edit_stock = (int)$_POST['edit_stock'];
    $edit_price = (float)$_POST['edit_price'];

    $update_inventory = "UPDATE inventory SET product_name='$edit_product_name', stock=$edit_stock, price=$edit_price WHERE id=$edit_id";
    mysqli_query($conn, $update_inventory);
    header("Location: admin_dashboard.php?tab=inventory");
    exit();
}

// Delete inventory
if (isset($_GET['delete_inventory'])) {
    $delete_id = (int)$_GET['delete_inventory'];
    mysqli_query($conn, "DELETE FROM inventory WHERE id=$delete_id");
    header("Location: admin_dashboard.php?tab=inventory");
    exit();
}

// Delete reports
if (isset($_GET['delete_reports'])) {
    $delete_id = (int)$_GET['delete_reports'];
    mysqli_query($conn, "DELETE FROM reports WHERE id=$delete_id");
    header("Location: admin_dashboard.php?tab=reports");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - James Pascual Salon Inventory</title>
    <link rel="stylesheet" href="../css/dashboard.css">
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
        <a href="javascript:void(0);" class="tablinks active" onclick="openTab(event, 'users')">Manage Users</a>
        <a href="javascript:void(0);" class="tablinks" onclick="openTab(event, 'inventory')">Manage Inventory</a>
        <a href="javascript:void(0);" class="tablinks" onclick="openTab(event, 'reports')">View Reports</a>
    </div>

    <div class="dashboard-container">
        <section id="users" class="tabcontent" style="display: block;">
            <h2>Manage Users</h2>
            <button class="add-btn" onclick="toggleForm('addUserForm')">Add User</button>
            <div id="addUserForm" style="display: none;" class="form-add">
                <h3>Add New User</h3>
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="role" required>
                        <option value="">Select Role</option>
                        <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                        <option value="Superadmin">Superadmin</option>
                    </select>
                    <button type="submit" name="add_user">Add User</button>
                </form>
            </div>
            <input type="text" id="userSearch" onkeyup="searchTable('userTable', 'userSearch', 1)" placeholder="Search users..." class="search-input">
            <table class="styled-table" id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php
$user_result = mysqli_query($conn, "SELECT * FROM users");
while ($user = mysqli_fetch_assoc($user_result)) {
    echo "<tr>
        <td>".htmlspecialchars($user['id'])."</td>
        <td>".htmlspecialchars($user['username'])."</td>
        <td>".htmlspecialchars($user['role'])."</td>
        <td>
            <button class='action-btn edit-btn' onclick='editUser(".$user['id'].", \"".$user['username']."\", \"".$user['role']."\")'>Edit</button>
            <a href='admin_dashboard.php?delete_user=".$user['id']."' class='action-btn delete-btn' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
        </td>
    </tr>";
}
?>
                </tbody>
            </table>
            <div id="editUserForm" style="display: none;" class="form-add">
                <h3>Edit User</h3>
                <form method="post">
                    <input type="hidden" name="edit_id" id="editUserId">
                    <input type="text" name="edit_username" id="editUsername" placeholder="Username" required>
                    <select name="edit_role" id="editRole" required>
                        <option value="Admin">Admin</option>
                        <option value="Staff">Staff</option>
                        <option value="Superadmin">Superadmin</option>
                    </select>
                    <button type="submit" name="update_user">Save Changes</button>
                </form>
            </div>
        </section>

        <section id="inventory" class="tabcontent">
            <h2>Manage Inventory</h2>
            <button class="add-btn" onclick="toggleForm('addInventoryForm')">Add Inventory</button>
            <div id="addInventoryForm" style="display: none;" class="form-add">
                <h3>Add New Inventory</h3>
                <form method="post">
                    <input type="text" name="product_name" placeholder="Product Name" required>
                    <input type="number" name="stock" placeholder="Stock" required>
                    <input type="number" step="0.01" name="price" placeholder="Price" required>
                    <button type="submit" name="add_inventory">Add Inventory</button>
                </form>
            </div>
            <input type="text" id="inventorySearch" onkeyup="searchTable('inventoryTable', 'inventorySearch', 1)" placeholder="Search inventory..." class="search-input">
            <table class="styled-table" id="inventoryTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
<?php
$inventory_result = mysqli_query($conn, "SELECT * FROM inventory");
while ($item = mysqli_fetch_assoc($inventory_result)) {
    echo "<tr>
        <td>".htmlspecialchars($item['id'])."</td>
        <td>".htmlspecialchars($item['product_name'])."</td>
        <td>".htmlspecialchars($item['stock'])."</td>
        <td>₱".htmlspecialchars(number_format($item['price'], 2))."</td>
        <td>
            <button class='action-btn edit-btn' onclick='editInventory(".$item['id'].", \"".$item['product_name']."\", \"".$item['stock']."\", \"".$item['price']."\")'>Edit</button>
            <a href='admin_dashboard.php?delete_inventory=".$item['id']."' class='action-btn delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
        </td>
    </tr>";
}
?>
                </tbody>
            </table>
            <div id="editInventoryForm" style="display: none;" class="form-add">
                <h3>Edit Inventory</h3>
                <form method="post">
                    <input type="hidden" name="edit_id" id="editInventoryId">
                    <input type="text" name="edit_product_name" id="editProductname" placeholder="Product Name" required>
                    <input type="text" name="edit_stock" id="editStock" placeholder="Stock" required>
                    <input type="text" name="edit_price" id="editPrice" placeholder="Price" required>
                    <button type="submit" name="update_inventory">Save Changes</button>
                </form>
            </div>
        </section>

        <section id="reports" class="tabcontent">
            <h2>View Reports</h2>
            <input type="text" id="reportsSearch" onkeyup="searchTable('reportsTable', 'reportsSearch', 2)" placeholder="Search reports..." class="search-input">
        <table class="styled-table" id="reportsTable">
            <thead>
                <tr><th>ID</th><th>Content</th><th>Submitted By</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php
                $reports = mysqli_query($conn, "SELECT * FROM reports");
                while ($r = mysqli_fetch_assoc($reports)) {
                    echo "<tr>
                        <td>{$r['id']}</td>
                        <td>{$r['report_content']}</td>
                        <td>{$r['submitted_by']}</td>
                        <td>{$r['created_at']}</td>
                            <td>
                                <a href='admin_dashboard.php?delete_reports=".$r['id']."' class='action-btn delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<footer>
    <p>&copy; 2025 James Pascual Salon Inventory. All rights reserved.</p>
</footer>

<script>
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
}

function searchTable(tableId, searchId, columnIndex) {
    var input = document.getElementById(searchId);
    var filter = input.value.toUpperCase();
    var table = document.getElementById(tableId);
    var tr = table.getElementsByTagName("tr");
    for (var i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        var td = tr[i].getElementsByTagName("td")[columnIndex];
        if (td) {
            if (td.textContent.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            }
        }
    }
}

function toggleForm(formId) {
    var form = document.getElementById(formId);
    form.style.display = (form.style.display === "none") ? "block" : "none";
}

function editUser(id, username, role) {
    document.getElementById('editUserForm').style.display = 'block';
    document.getElementById('editUserId').value = id;
    document.getElementById('editUsername').value = username;
    document.getElementById('editRole').value = role;
}

function editInventory(id, product_name, stock, price) {
    document.getElementById('editInventoryId').value = id;
    document.getElementById('editProductname').value = product_name;
    document.getElementById('editStock').value = stock;
    document.getElementById('editPrice').value = price;
    document.getElementById('editInventoryForm').style.display = 'block';
}


document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab) {
        const tabLink = Array.from(document.getElementsByClassName('tablinks')).find(link => link.textContent.trim().toLowerCase().includes(tab));
        if (tabLink) {
            tabLink.click();
        }
    }
});
</script>

</body>
</html>