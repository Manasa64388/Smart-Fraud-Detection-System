<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Fraud Detection System - Home</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; margin: 0; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .hero { text-align: center; background: white; padding: 50px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); max-width: 600px; }
        h1 { color: #1e3a8a; margin-bottom: 10px; font-size: 28px; }
        p { color: #64748b; margin-bottom: 40px; }
        .btn-container { display: flex; gap: 20px; justify-content: center; }
        .btn { padding: 15px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; transition: 0.3s; display: flex; flex-direction: column; align-items: center; width: 180px; }
        
        .btn-user { background: #2563eb; color: white; border: 2px solid #2563eb; }
        .btn-user:hover { background: #1d4ed8; transform: translateY(-5px); }
        
        .btn-admin { background: white; color: #1e293b; border: 2px solid #e2e8f0; }
        .btn-admin:hover { border-color: #1e293b; transform: translateY(-5px); }

        .icon { font-size: 32px; margin-bottom: 10px; }
        .small-text { font-size: 12px; font-weight: normal; margin-top: 5px; opacity: 0.8; }
    </style>
</head>
<body>

<div class="hero">
    <div class="icon">🛡️</div>
    <h1>Smart Fraud Detection System</h1>
    <p>Welcome to the Secure Banking Portal. Please select your access level to continue.</p>

    <div class="btn-container">
        <a href="signin.php" class="btn btn-user">
            <span class="icon">💳</span>
            Customer Portal
            <span class="small-text">Sign In / Register</span>
        </a>

        <a href="signin.php" class="btn btn-admin">
            <span class="icon">🏛️</span>
            Bank Admin
            <span class="small-text">Admin Login</span>
        </a>
    </div>
</div>

</body>
</html>