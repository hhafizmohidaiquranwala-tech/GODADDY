<?php
require 'db.php';
session_start();

// Common CSS for all pages to ensure "Kamal" (Amazing) consistency
$neon_css = "
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
    
    body {
        background-color: #050510;
        color: #fff;
        overflow-x: hidden;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Animated Background */
    body::before {
        content: '';
        position: fixed;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(188, 19, 254, 0.1) 0%, transparent 60%),
                    radial-gradient(circle, rgba(0, 243, 255, 0.1) 100%, transparent 60%);
        animation: rotateBg 20s linear infinite;
        z-index: -1;
    }

    @keyframes rotateBg { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* Navbar */
    nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 50px;
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 1000;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
        color: #fff;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 0 10px #00f3ff, 0 0 20px #00f3ff;
    }

    .nav-links a {
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        margin-left: 30px;
        font-weight: 500;
        transition: 0.3s;
        position: relative;
    }

    .nav-links a:hover {
        color: #fff;
        text-shadow: 0 0 8px #bc13fe;
    }

    /* Buttons */
    .btn-neon {
        padding: 10px 25px;
        background: transparent;
        border: 2px solid #00f3ff;
        color: #00f3ff;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 5px #00f3ff; 
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
    }

    .btn-neon:hover {
        background: #00f3ff;
        color: #000;
        box-shadow: 0 0 20px #00f3ff, 0 0 40px #00f3ff;
    }

    .btn-neon-purple {
        border-color: #bc13fe;
        color: #bc13fe;
        box-shadow: 0 0 5px #bc13fe;
    }

    .btn-neon-purple:hover {
        background: #bc13fe;
        color: #fff;
        box-shadow: 0 0 20px #bc13fe, 0 0 40px #bc13fe;
    }

    /* Forms & Cards */
    .container {
        max-width: 1200px;
        margin: 50px auto;
        padding: 20px;
        text-align: center;
    }

    .card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 40px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
        transition: transform 0.3s;
    }
    
    .card:hover {
        transform: translateY(-5px);
        border-color: rgba(0, 243, 255, 0.5);
        box-shadow: 0 0 20px rgba(0, 243, 255, 0.2);
    }

    input[type='text'], input[type='email'], input[type='password'] {
        width: 100%;
        padding: 15px;
        margin: 10px 0;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid #333;
        color: #fff;
        border-radius: 5px;
        outline: none;
        transition: 0.3s;
    }

    input:focus {
        border-color: #bc13fe;
        box-shadow: 0 0 10px rgba(188, 19, 254, 0.5);
    }

    /* Hero Section */
    .hero {
        padding: 100px 20px;
    }

    .hero h1 {
        font-size: 4rem;
        margin-bottom: 20px;
        background: linear-gradient(90deg, #fff, #00f3ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 0 30px rgba(0, 243, 255, 0.3);
    }

    .search-box {
        display: flex;
        justify-content: center;
        gap: 10px;
        max-width: 700px;
        margin: 0 auto;
    }

    .search-box input {
        border-radius: 30px;
        border: 1px solid #00f3ff;
        padding-left: 25px;
    }

    .search-box button {
        border-radius: 30px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero h1 { font-size: 2.5rem; }
        .search-box { flex-direction: column; }
        nav { flex-direction: column; gap: 20px; }
        .nav-links { display: flex; flex-direction: column; gap: 10px; }
        .nav-links a { margin: 0; }
    }
</style>
";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeonDomains - The Future of Web</title>
    <?php echo $neon_css; ?>
</head>
<body>

<nav>
    <a href="index.php" class="logo">NeonDomains</a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="#" onclick="logout()">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php" class="btn-neon">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>

<div class="hero container">
    <h1>Own Your Digital <br> Identity Today.</h1>
    <p style="color: grey; margin-bottom: 40px; font-size: 1.2rem;">Search for your perfect domain name starting at just $9.99/yr</p>
    
    <form action="results.php" method="GET" class="search-box">
        <input type="text" name="domain" placeholder="Find your perfect domain (e.g. awesome-site.com)" required>
        <button type="submit" class="btn-neon">Search Domain</button>
    </form>
</div>

<div class="container">
    <h2 style="margin-bottom: 30px; text-shadow: 0 0 10px #fff;">Popular Extensions</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
        <div class="card" style="flex: 1; min-width: 200px;">
            <h3 style="color: #00f3ff;">.COM</h3>
            <p>$12.99/yr</p>
            <p style="font-size: 0.8rem; color: #888; margin-top: 10px;">The king of domains.</p>
        </div>
        <div class="card" style="flex: 1; min-width: 200px;">
            <h3 style="color: #bc13fe;">.IO</h3>
            <p>$35.99/yr</p>
            <p style="font-size: 0.8rem; color: #888; margin-top: 10px;">Perfect for tech startups.</p>
        </div>
        <div class="card" style="flex: 1; min-width: 200px;">
            <h3 style="color: #ffff00;">.XYZ</h3>
            <p>$1.99/yr</p>
            <p style="font-size: 0.8rem; color: #888; margin-top: 10px;">Affordable and catchy.</p>
        </div>
    </div>
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
