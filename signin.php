<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acc = trim($_POST['account_no']);
    $pass = trim($_POST['password']);

    // 1. HARDCODED ADMIN BYPASS (FIXED: Now sets the user_role session)
    if ($acc === '999' && $pass === 'admin123') {
        $_SESSION['user_id'] = 0;
        $_SESSION['user_name'] = 'System Admin';
        $_SESSION['account_no'] = '999';
        $_SESSION['user_role'] = 'admin'; // ✨ This matches the protection check on admin.php!
        
        echo "<script>window.location.href='admin.php';</script>";
        exit();
    }

    // 2. STANDARD USER LOOKUP
    $sql = "SELECT * FROM users WHERE account_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $acc);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['account_no'] = $user['account_no'];
            $_SESSION['user_role'] = $user['role']; // Captures 'customer' from database schema
            
            // Redirect based on role type
            if ($_SESSION['user_role'] === 'admin') {
                echo "<script>window.location.href='admin.php';</script>";
            } else {
                echo "<script>window.location.href='index.php';</script>";
            }
            exit();
        } else { 
            echo "<script>alert('Invalid Password!'); window.location.href='signin.php';</script>"; 
            exit();
        }
    } else { 
        echo "<script>alert('Account not found!'); window.location.href='signin.php';</script>"; 
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign In - Fraud System</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        button { width: 100%; padding: 12px; background: #059669; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 10px; }
        button:hover { background: #047857; }
        p { margin-top: 15px; font-size: 14px; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="color: #1e3a8a; margin-top: 0;">🏛️ Secure Sign In</h2>
        <form action="signin.php" method="POST">
            <input type="text" name="account_no" placeholder="Account Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="signup.php">New user? Register</a></p>
    </div>
</body>
</html>