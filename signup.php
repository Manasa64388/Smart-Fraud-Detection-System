<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $acc = trim($_POST['account_no']);
    $plain_password = $_POST['password'];

    // 🛑 BACKEND VALIDATION REGEX RULES (6 Characters, Upper, Lower, Number, Special Character)
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{6}$/';

    if (!preg_match($pattern, $plain_password)) {
        echo "<script>alert('Validation Failed! Password must be exactly 6 characters and contain uppercase, lowercase, numbers, and special characters.'); window.location.href='signup.php';</script>";
        exit();
    }

    // If it passes validation, we securely hash it for the database storage
    $pass = password_hash($plain_password, PASSWORD_DEFAULT);
    $role = 'customer';  

    // Check if account number already exists
    $check_sql = "SELECT id FROM users WHERE account_no = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $acc);
    $check_stmt->execute();
    if ($check_stmt->get_result()->num_rows > 0) {
        echo "<script>alert('Error: Account number already registered!'); window.location.href='signup.php';</script>";
        exit();
    }

    $sql = "INSERT INTO users (name, account_no, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $acc, $pass, $role);
    
    if ($stmt->execute()) {
        echo "<script>alert('Account Created Successfully! You can now log in.'); window.location='signin.php';</script>";
    } else {
        echo "<script>alert('Database Error: " . $stmt->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Fraud System</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f4f8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 380px; text-align: left; }
        h2 { text-align: center; color: #1e3a8a; margin-top: 0; }
        label { font-weight: 600; color: #475569; font-size: 14px; display: block; margin-top: 10px; }
        input { width: 100%; padding: 12px; margin: 6px 0 12px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px; }
        button { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 10px; }
        button:hover { background: #1d4ed8; }
        p { text-align: center; margin-top: 15px; font-size: 14px; }
        a { color: #2563eb; text-decoration: none; }
        
        /* Live Strength Indicator Styles */
        .meter-container { margin-bottom: 15px; }
        .meter-bar { height: 6px; width: 100%; background: #e2e8f0; border-radius: 3px; overflow: hidden; margin-top: 4px; }
        .meter-fill { height: 100%; width: 0%; transition: all 0.3s ease; }
        .strength-text { font-size: 12px; font-weight: bold; color: #64748b; }
        .requirements { font-size: 11px; color: #64748b; list-style-type: none; padding-left: 0; margin: 5px 0; }
        .req-item.valid { color: #16a34a; }
        .req-item.invalid { color: #dc2626; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🛡️ User Registration</h2>
        <form method="POST" action="signup.php" onsubmit="return validateForm()">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter Full Name" required>

            <label>Account Number</label>
            <input type="text" name="account_no" placeholder="Enter Account Number" required>

            <label>Password (Exactly 6 Characters)</label>
            <input type="password" id="password" name="password" placeholder="e.g., Abc@12" maxlength="6" required oninput="checkStrength()">
            
            <div class="meter-container">
                <div style="display: flex; justify-content: space-between;">
                    <span class="strength-text" id="strength-label">Strength: None</span>
                    <span class="strength-text" id="length-label">0/6 chars</span>
                </div>
                <div class="meter-bar"><div class="meter-fill" id="meter-fill"></div></div>
                <ul class="requirements">
                    <li class="req-item invalid" id="req-len">❌ Exactly 6 characters</li>
                    <li class="req-item invalid" id="req-upper">❌ Mixed case letters (A and a)</li>
                    <li class="req-item invalid" id="req-num">❌ At least one number (0-9)</li>
                    <li class="req-item invalid" id="req-spec">❌ Special character (@, #, $, etc.)</li>
                </ul>
            </div>

            <button type="submit">Register Account</button>
        </form>
        <p><a href="signin.php">Already have an account? Login</a></p>
    </div>

    <script>
    function checkStrength() {
        const pass = document.getElementById('password').value;
        const fill = document.getElementById('meter-fill');
        const label = document.getElementById('strength-label');
        document.getElementById('length-label').innerText = `${pass.length}/6 chars`;

        // Verification Flags
        const hasUpper = /[A-Z]/.test(pass);
        const hasLower = /[a-z]/.test(pass);
        const hasNum = /\d/.test(pass);
        const hasSpec = /[!@#$%^&*(),.?":{}|<>]/.test(pass);
        const isLen6 = pass.length === 6;

        // Update checklist UI elements dynamically
        document.getElementById('req-len').className = isLen6 ? 'req-item valid' : 'req-item invalid';
        document.getElementById('req-len').innerText = isLen6 ? '✅ Exactly 6 characters' : '❌ Exactly 6 characters';
        
        document.getElementById('req-upper').className = (hasUpper && hasLower) ? 'req-item valid' : 'req-item invalid';
        document.getElementById('req-upper').innerText = (hasUpper && hasLower) ? '✅ Mixed case letters (A and a)' : '❌ Mixed case letters (A and a)';
        
        document.getElementById('req-num').className = hasNum ? 'req-item valid' : 'req-item invalid';
        document.getElementById('req-num').innerText = hasNum ? '✅ At least one number (0-9)' : '❌ At least one number (0-9)';
        
        document.getElementById('req-spec').className = hasSpec ? 'req-item valid' : 'req-item invalid';
        document.getElementById('req-spec').innerText = hasSpec ? '✅ Special character (@, #, $, etc.)' : '❌ Special character (@, #, $, etc.)';

        // Evaluate overall criteria match score
        let score = 0;
        if (pass.length > 0) score += 1;
        if (hasUpper && hasLower) score += 1;
        if (hasNum) score += 1;
        if (hasSpec) score += 1;
        if (isLen6) score += 1; // High weight for hitting target configuration length

        // UI Representation adjustments based on state score values
        if (pass.length === 0) {
            fill.style.width = '0%';
            label.innerText = "Strength: None";
            label.style.color = "#64748b";
        } else if (score <= 2 || !isLen6) {
            fill.style.width = '33%';
            fill.style.background = '#dc2626'; // Red
            label.innerText = "Strength: Weak";
            label.style.color = "#dc2626";
        } else if (score <= 4) {
            fill.style.width = '66%';
            fill.style.background = '#eab308'; // Yellow
            label.innerText = "Strength: Medium";
            label.style.color = "#eab308";
        } else if (score === 5) {
            fill.style.width = '100%';
            fill.style.background = '#16a34a'; // Green
            label.innerText = "Strength: Strong & Valid";
            label.style.color = "#16a34a";
        }
    }

    function validateForm() {
        const pass = document.getElementById('password').value;
        const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),.?":{}|<>]).{6}$/;
        if(!pattern.test(pass)) {
            alert("Form validation rejected! Please satisfy all strength metrics to meet strict criteria specifications.");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>