<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch User's Domains
$stmt = $conn->prepare("SELECT domain_name, registration_date, expiry_date, status FROM domains WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Reuse common styles
$neon_css = "
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
    body {
        background-color: #050510;
        color: #fff;
        min-height: 100vh;
        overflow-x: hidden;
    }
    nav {
        display: flex;
        justify-content: space-between;
        padding: 20px 50px;
        background: rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .logo { color: #fff; font-size: 1.5rem; font-weight: bold; text-decoration: none; }
    .btn-logout { 
        color: #ff0055; text-decoration: none; border: 1px solid #ff0055; 
        padding: 5px 15px; border-radius: 5px; transition: 0.3s; 
    }
    .btn-logout:hover { background: #ff0055; color: #fff; box-shadow: 0 0 10px #ff0055; }
    
    .container {
        max-width: 1000px; margin: 50px auto; padding: 20px;
    }
    h2 { margin-bottom: 20px; color: #00f3ff; text-shadow: 0 0 10px #00f3ff; }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    th {
        background: rgba(0, 243, 255, 0.1);
        color: #00f3ff;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    tr:hover { background: rgba(255,255,255,0.08); }
    
    .status-active { color: #00ff00; font-weight: bold; text-shadow: 0 0 5px #00ff00; }
    .btn-manage {
        padding: 5px 10px;
        border: 1px solid #bc13fe;
        color: #bc13fe;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.8rem;
        transition: 0.3s;
    }
    .btn-manage:hover { background: #bc13fe; color: #fff; box-shadow: 0 0 10px #bc13fe; }

    .no-domains {
        text-align: center;
        padding: 50px;
        color: #888;
    }
    .add-new {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: #00f3ff;
        color: #000;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }
</style>
";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard</title>
    <?php echo $neon_css; ?>
</head>
<body>

<nav>
    <a href="index.php" class="logo">NeonDomains</a>
    <div>
        <span style="margin-right: 20px;">Welcome, <?php echo htmlspecialchars($username); ?></span>
        <a href="#" onclick="logout()" class="btn-logout">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>My Domains</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Domain Name</th>
                    <th>Registration Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="font-weight: bold; color: #fff;"><?php echo htmlspecialchars($row['domain_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['registration_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['expiry_date']); ?></td>
                        <td><span class="status-active">● Active</span></td>
                        <td>
                            <a href="#" class="btn-manage">Manage DNS</a>
                            <a href="#" class="btn-manage" style="border-color: #00f3ff; color: #00f3ff;">Renew</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-domains">
            <p>You don't have any domains yet.</p>
            <a href="index.php" class="add-new">Search for a Domain</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function logout() {
        if(confirm('Are you sure you want to logout?')) {
            window.location.href = 'logout.php';
        }
    }
</script>

</body>
</html>
