# 🛡️ Smart Fraud Detection System

### 📌 Project Overview
The **Smart Fraud Detection System** is a web-based DBMS project designed to monitor financial transactions in real-time. Unlike standard transaction logs, this system applies **rule-based logic** to identify and flag suspicious activities automatically, helping to prevent unauthorized access and financial loss.

---

### 🚀 Key Features
*   **🕒 Real-time Monitoring:** Tracks every transaction with precise timestamps and geolocation data.
*   **🧠 Rule-Based Detection:**
    *   **📍 Location Velocity Check:** Flags transactions if they occur in different cities within an impossible travel timeframe.
    *   **💰 Threshold Monitoring:** Identifies transactions that deviate significantly from a user's typical spending pattern.
*   **⚠️ Automated Alerting:** Sets an `is_fraud` flag to **1 (Suspicious)** for any transaction failing the logic checks.
*   **🖥️ Admin Dashboard:** Provides a clear visual interface where flagged transactions are highlighted in **Red** for immediate review.

---

### 💻 Tech Stack
*   **Frontend:** ![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=flat&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=flat&logo=css3&logoColor=white) ![JavaScript](https://img.shields.io/badge/javascript-%23F7DF1E.svg?style=flat&logo=javascript&logoColor=black)
*   **Backend:** ![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=flat&logo=php&logoColor=white)
*   **Database:** ![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=flat&logo=mysql&logoColor=white)
*   **Tools:** ![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=flat&logo=XAMPP&logoColor=white) ![Git](https://img.shields.io/badge/git-%23F05033.svg?style=flat&logo=git&logoColor=white) ![GitHub](https://img.shields.io/badge/github-%23121011.svg?style=flat&logo=github&logoColor=white)

---

### 🗄️ Database Schema
The system operates on a relational model consisting of:

1.  **`users` Table:** Stores user profiles, account numbers, and real-time balances.
2.  **`transactions` Table:** Records transaction history, including amount, location, and the automated fraud status flag.



---

### 🛠️ Installation & Setup
1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/YOUR_USERNAME/Smart-Fraud-Detection.git](https://github.com/YOUR_USERNAME/Smart-Fraud-Detection.git)
2. **Start XAMPP:** Turn on Apache and MySQL.
3. **Import Database:** Import the provided .sql file via **phpMyAdmin.**
4. **Run:** Move the project folder to htdocs and visit localhost/folder_name in your browser.

**👥 Team Members**
**Team Lead: MANASA N S**
**Collaborator: VAISHNAVI KAVATAGI**
