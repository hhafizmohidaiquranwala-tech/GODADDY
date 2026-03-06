<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logging out...</title>
</head>
<body>
    <script>
        // Redirect using JS as requested
        window.location.href = 'index.php';
    </script>
</body>
</html>
