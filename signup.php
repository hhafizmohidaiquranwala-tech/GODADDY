<?php
require 'db.php';
session_start();

// Redirect if already logged in
echo "<script>if(" . (isset($_SESSION['user_id']) ? 'true' : 'false') . ") { window.location.href='dashboard.php'; }</script>";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $message = "Registration successful! Redirecting to login...";
        echo "<script>setTimeout(function(){ window.location.href='login.php'; }, 2000);</script>";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Reuse styles
$neon_css = "
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
    body {
        background-color: #050510;
        color: #fff;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    body::before {
        content: ''; position: absolute; width: 100%; height: 100%;
        background: radial-gradient(circle at 20% 20%, rgba(188, 19, 254, 0.2), transparent 40%),
                    radial-gradient(circle at 80% 80%, rgba(0, 243, 255, 0.2), transparent 40%);
        z-index: -1;
    }
    .form-container {
        width: 100%; max-width: 400px;
        padding: 40px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        box-shadow: 0 0 30px rgba(0,0,0,0.5);
        text-align: center;
    }
    h2 { margin-bottom: 20px; background: linear-gradient(90deg, #bc13fe, #00f3ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 2rem; }
    input {
        width: 100%; padding: 12px; margin: 10px 0;
        background: rgba(0, 0, 0, 0.4); border: 1px solid #333; color: #fff; border-radius: 8px; outline: none;
        transition: 0.3s;
    }
    input:focus { border-color: #00f3ff; box-shadow: 0 0 10px rgba(0, 243, 255, 0.3); }
    button {
        width: 100%; padding: 12px; margin-top: 20px;
        background: linear-gradient(90deg, #bc13fe, #00f3ff); border: none; color: #fff; font-weight: bold;
        border-radius: 8px; cursor: pointer; transition: 0.3s;
    }
    button:hover { opacity: 0.9; box-shadow: 0 0 15px rgba(188, 19, 254, 0.5); }
    .footer-link { margin-top: 20px; font-size: 0.9rem; color: #aaa; }
    .footer-link a { color: #00f3ff; text-decoration: none; }
    .alert { color: #00f3ff; margin-bottom: 10px; font-weight: bold; }
</style>
";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - NeonDomains</title>
    <?php echo $neon_css; ?>
</head>
<body>

<div class="form-container">
    <h2>Join the Future</h2>
    <?php if ($message) echo "<div class='alert'>$message</div>"; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create Account</button>
    </form>
    <div class="footer-link">
        Already have an account? <a href="login.php" onclick="window.location.href='login.php'; return false;">Login here</a>
    </div>
</div>

</body>
</html>
