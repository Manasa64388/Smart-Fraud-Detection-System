<!DOCTYPE html>
<html lang="en">
<head>
    <title>Transaction Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>💳 Process New Transaction</h2>
    <form action="process.php" method="POST">
        <input type="text" name="account_no" placeholder="Account Number" required><br><br>
        <input type="number" name="amount" placeholder="Amount (₹)" required><br><br>
        <input type="text" name="location" placeholder="Current City" required><br><br>
        <button type="submit">Check Transaction</button>
    </form>
</body>
</html>