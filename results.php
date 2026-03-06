<?php
require 'db.php';
session_start();

$domain = isset($_GET['domain']) ? trim(htmlspecialchars($_GET['domain'])) : '';
$available = false;
$price = 0;

if($domain) {
    // Mock Availability Logic
    // If length of string is even -> Available. If odd -> Taken.
    // Just a dummy logic to simulate real api
    $seed = strlen($domain);
    if ($seed % 2 == 0) {
        $available = true;
        // Generate random price
        $price = $seed == 4 ? 999.00 : 12.99;
    } else {
        $available = false;
    }
}

// Common CSS
$neon_css = "
<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
    body {
        background-color: #050510;
        color: #fff;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 50px;
    }
    .container {
        width: 100%; max-width: 800px;
        padding: 20px;
        text-align: center;
    }
    .card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 40px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        box-shadow: 0 0 30px rgba(0,0,0,0.5);
    }
    h1 { margin-bottom: 20px; color: #fff; }
    .status {
        font-size: 2rem;
        font-weight: bold;
        margin: 20px 0;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .available {
        color: #00f3ff;
        text-shadow: 0 0 20px #00f3ff;
    }
    .taken {
        color: #ff0055;
        text-shadow: 0 0 20px #ff0055;
    }
    .btn-neon {
        padding: 15px 40px;
        background: #00f3ff;
        border: none;
        color: #000;
        font-weight: bold;
        font-size: 1.2rem;
        cursor: pointer;
        border-radius: 50px;
        box-shadow: 0 0 20px #00f3ff;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }
    .btn-neon:hover {
        transform: scale(1.05);
        box-shadow: 0 0 40px #00f3ff;
    }
    .price {
        font-size: 1.5rem;
        color: #ccc;
        margin: 10px 0;
    }
    .search-again {
        margin-top: 30px;
        display: inline-block;
        color: #aaa;
        text-decoration: none;
        border-bottom: 1px solid #aaa;
    }
</style>
";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Search Results</title>
    <?php echo $neon_css; ?>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>Domain Availability</h1>
        
        <?php if($domain): ?>
            <p>You searched for: <strong><?php echo htmlspecialchars($domain); ?></strong></p>
            
            <?php if($available): ?>
                <div class="status available">✔ Available</div>
                <div class="price">Price: $<?php echo number_format($price, 2); ?>/year</div>
                
                <form action="cart.php" method="POST">
                    <input type="hidden" name="domain" value="<?php echo htmlspecialchars($domain); ?>">
                    <input type="hidden" name="price" value="<?php echo $price; ?>">
                    <button type="submit" class="btn-neon">Add to Cart & Checkout</button>
                </form>
            <?php else: ?>
                <div class="status taken">✘ Taken</div>
                <p>Sorry, this domain is already registered.</p>
            <?php endif; ?>

        <?php else: ?>
            <p>Please enter a domain name.</p>
        <?php endif; ?>

        <a href="index.php" class="search-again">Search Another Domain</a>
    </div>
</div>

</body>
</html>
