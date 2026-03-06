<?php
require 'db.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<!DOCTYPE html><html><body><script>alert('Please login to purchase a domain.'); window.location.href = 'login.php';</script></body></html>";
    exit;
}

$start_checkout = false;
$domain = "";
$price = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['domain'])) {
    $domain = htmlspecialchars($_POST['domain']);
    $price = $_POST['price'];
    $start_checkout = true;
}

// Processing the "Payment"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_payment'])) {
    $user_id = $_SESSION['user_id'];
    $domain_to_add = $_POST['final_domain'];
    
    // Calculate dates
    $reg_date = date("Y-m-d");
    $exp_date = date("Y-m-d", strtotime("+1 year"));

    $stmt = $conn->prepare("INSERT INTO domains (user_id, domain_name, registration_date, expiry_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $domain_to_add, $reg_date, $exp_date);
    
    if ($stmt->execute()) {
        echo "<!DOCTYPE html><html><body><script>alert('Purchase Successful! Domain added to your dashboard.'); window.location.href = 'dashboard.php';</script></body></html>";
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
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
        justify-content: center;
        align-items: center;
    }
    .checkout-container {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 50px;
        border-radius: 20px;
        text-align: center;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 0 40px rgba(0, 243, 255, 0.1);
    }
    h2 { color: #00f3ff; margin-bottom: 20px; text-transform: uppercase; }
    .details {
        font-size: 1.2rem;
        margin: 20px 0;
        padding: 20px;
        border: 1px dashed #555;
        border-radius: 10px;
    }
    .total {
        font-size: 2rem;
        font-weight: bold;
        color: #bc13fe;
        text-shadow: 0 0 10px #bc13fe;
        margin-bottom: 30px;
    }
    .btn-pay {
        padding: 15px 30px;
        width: 100%;
        background: linear-gradient(45deg, #00f3ff, #bc13fe);
        border: none;
        color: #fff;
        font-weight: bold;
        font-size: 1.1rem;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-pay:hover {
        opacity: 0.9;
        box-shadow: 0 0 30px rgba(188, 19, 254, 0.5);
    }
</style>
";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <?php echo $neon_css; ?>
</head>
<body>

<div class="checkout-container">
    <h2>Secure Checkout</h2>
    
    <?php if ($start_checkout): ?>
        <div class="details">
            <p>Domain: <strong><?php echo htmlspecialchars($domain); ?></strong></p>
            <p>Term: 1 Year</p>
        </div>
        
        <div class="total">
            Total: $<?php echo number_format($price, 2); ?>
        </div>

        <form action="" method="POST">
            <input type="hidden" name="final_domain" value="<?php echo htmlspecialchars($domain); ?>">
            <input type="hidden" name="confirm_payment" value="1">
            <button type="submit" class="btn-pay">CONFIRM PAYMENT</button>
        </form>
        
        <br>
        <a href="results.php?domain=<?php echo urlencode($domain); ?>" style="color: grey; text-decoration: none;">Cancel</a>
    
    <?php else: ?>
        <p>Your cart is empty.</p>
        <a href="index.php" style="color: #00f3ff;">Go back home</a>
    <?php endif; ?>
</div>

</body>
</html>
