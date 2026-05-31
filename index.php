<?php 
session_start(); 
// Secure Guard: If a user isn't logged in, kick them back to sign-in
if(!isset($_SESSION['user_name'])) { 
    header("Location: signin.php"); 
    exit(); 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transaction Form</title>
  <style>
    body { background: #f4f7fc; font-family: 'Segoe UI', Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
    .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 450px; }
    h2 { color: #1e3a8a; text-align: center; margin-bottom: 20px; }
    .form-group { margin-bottom: 15px; }
    label { display: block; margin-bottom: 6px; font-weight: bold; color: #333; }
    input { width: 100%; padding: 10px; margin-top: 5px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px; box-sizing: border-box; }
    input[readonly] { background-color: #e2e8f0; color: #64748b; cursor: not-allowed; }
    .btn { background: #1e3a8a; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 10px; }
    .btn:hover { background: #1d4ed8; }
    .note { text-align: center; color: #64748b; font-size: 12px; margin-top: 15px; }
  </style>
</head>
<body>
  <div class="container">
    <a href="logout.php" style="float: right; color: #ef4444; text-decoration: none; font-weight: bold; font-size: 14px;">Logout 🚪</a>
    
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
    
    <form action="process.php" method="POST">
      
      <div class="form-group">
        <label>Account Number</label>
        <input name="account_no" type="text" value="<?php echo htmlspecialchars($_SESSION['account_no']); ?>" readonly>
      </div>

      <div class="form-group">
        <label for="amount">Transaction Amount (₹)</label>
        <input id="amount" name="amount" type="number" min="1" placeholder="Enter Amount" required>
      </div>

      <div class="form-group">
        <label for="location">Current City (Location)</label>
        <input id="location" name="location" type="text" placeholder="e.g. Bengaluru, Mumbai" required>
      </div>

      <button type="submit" class="btn">Check for Fraud</button>
      <p class="note">🛡️ Smart Fraud Detection System</p>
    </form>
  </div>
</body>
</html>