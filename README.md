# 🛡️ Smart Fraud Detection System

## 🏦 Secure Banking Transaction Monitoring Platform

A web-based fraud detection system developed using **PHP**, **MySQL**, **HTML5**, **CSS3**, and **JavaScript** that monitors banking transactions and automatically flags suspicious activities using real-time, rule-based logic engines and advanced database-layer engineering.

---

## 📖 Project Overview

The Smart Fraud Detection System is a high-integrity, data-driven banking application engineered to handle transaction processing while actively mitigating financial risk. 

Moving beyond standard passive CRUD functions, the system evaluates transactions live against programmatic risk criteria, utilizes atomic ACID database blocks to maintain immaculate data ledger records, tracks historical states through event automation, and builds dynamic aggregate matrices exposed through a responsive Admin Dashboard.

---

## 🎯 Project Objectives

* Secure user registration, authentication, and session control rules
* Record, process, and structure multi-entity consumer transactions
* Intercept, evaluate, and flag potentially fraudulent transfers in real-time
* Maintain strict, immutable historical transaction logs and audit trails
* Deliver real-time operational aggregates via an interactive admin interface
* Demonstrate professional implementation of advanced enterprise DBMS constraints

---

## ✨ Features

### 👤 User Management & Security

* **User Registration & Validation:** Creates relational user profiles mapping unique constraints.
* **Cryptographic Security:** Standard credential protection utilizing secure `bcrypt` password hashing functions.
* **Privilege-Based Routing:** Enforces secure session state management (`session_start`) to shield administrative views from malicious privilege escalations.

### 💳 Transaction Processing & Automation

* **State-Aware Portals:** Customer interfaces tailored to dispatch dynamic transactional metadata vectors (amounts and geolocation tracking).
* **ACID Transaction Safeguards:** Leverages robust server-side error capturing (`try-catch`) wrapped inside strict database transaction loops (`begin_transaction`, `commit`, `rollback`) ensuring multi-table writes succeed perfectly or leave zero partial entries.
* **Database Trigger Integration:** Uses native database event scheduling to dynamically update historical metadata profiles within the active tables without overloading application script runtimes.

### 🚩 Fraud Detection Rules

#### Rule 1: Velocity / High Amount Detection

Transactions exceeding a predefined liquidity threshold (`> ₹20,000`) are immediately intercepted and routed to the fraud logging ledger.

#### Rule 2: Spatial / Location Mismatch Detection

The system compares the incoming transaction's location parameter against the user's last known physical state variables. Any historical mismatch triggers an instant system alert.

---

## 📊 Admin Dashboard

* **Total Transaction Volume Accumulation:** Dynamically computes global systemic cash flow records.
* **Total Transactions Processed:** Tracks the comprehensive numerical ledger size.
* **Active Fraud Counter:** Computes real-time incident records globally.
* **Dynamic System Risk Rate:** Calculates live mathematical fraud percentage metrics.
* **Relational Record Joins:** Aggregates user entities and transactional logs onto a unified viewing dashboard.
* **Conditional High-Contrast Row Formatting:** Visually isolates risk alerts (`🚩 FRAUD`) instantly from standard states (`✅ SAFE`).

---

## 🗄️ Database Tables

The data store is built using rigid declarative relationships, utilizing auto-incrementing primary keys and cascade modifiers (`ON DELETE CASCADE`) to protect relational stability during deletions.

### Users

| Field         | Type         | Key | Description                         |
| ------------- | ------------ | --- | ----------------------------------- |
| id            | INT          | PRI | Auto-Incrementing Primary Key       |
| name          | VARCHAR(100) |     | Customer Identity Name              |
| account_no    | VARCHAR(20)  | UNI | Unique Account Mapping Identifier   |
| password      | VARCHAR(255) |     | Cryptographically Hashed String     |
| role          | ENUM         |     | Access Controls ('customer', 'admin')|
| last_location | VARCHAR(50)  |     | Historical Location Tracker State   |

### Transactions

| Field      | Type          | Key | Description                         |
| ---------- | ------------- | --- | ----------------------------------- |
| id         | INT           | PRI | Auto-Incrementing Primary Key       |
| user_id    | INT           | MUL | Relational Foreign Key (References Users)|
| amount     | DECIMAL(10,2) |     | Direct Transactional Asset Volume   |
| location   | VARCHAR(50)   |     | Dispatched Transaction Geolocation  |
| is_fraud   | TINYINT(1)    |     | Fraud Status Flag (0 = Safe, 1 = Fraud)|
| trans_time | TIMESTAMP     |     | Automated Ledger Creation Inbound   |

### Fraud Logs

| Field          | Type         | Key | Description                         |
| -------------- | ------------ | --- | ----------------------------------- |
| id             | INT          | PRI | Primary Key for Incident Tracking   |
| transaction_id | INT          | MUL | Foreign Key (References Transactions)|
| fraud_reason   | VARCHAR(255) |     | Contextual Descriptive Rule Breach  |
| detected_time  | TIMESTAMP    |     | System Interception Time            |

### Transaction History

| Field          | Type          | Key | Description                         |
| -------------- | ------------- | --- | ----------------------------------- |
| id             | INT           | PRI | Primary Key for Secondary Audit Loop|
| transaction_id | INT           | MUL | Foreign Key (References Transactions)|
| user_id        | INT           | MUL | Foreign Key (References Users)      |
| amount         | DECIMAL(10,2) |     | Immutably Recorded Volume Record   |
| location       | VARCHAR(100)  |     | Archive Location Metric             |

---

## 🖥️ Database Automation: Event Triggers

The relational system incorporates an automated database-level constraint engine to ensure synchronous state tracking independent of the frontend:

```sql
DELIMITER //
CREATE TRIGGER update_location_after_transaction
AFTER INSERT ON transactions
FOR EACH ROW
BEGIN
    UPDATE users SET last_location = NEW.location WHERE id = NEW.user_id;
END;
//
DELIMITER ;
```

## 💻 Technology Stack

* HTML5
* CSS3
* JavaScript
* PHP
* MySQL
* XAMPP
* GitHub

---

## 🎓 DBMS Concepts Used

* Relational Database Design
* Primary Keys
* Foreign Keys
* Joins
* Aggregate Functions
* Transactions
* Session Management
* Authentication

---

## 👨‍💻 Team Members

### Team Lead

MANASA N S — Backend Architecture, Database Optimization & Transaction Constraints

### Collaborator

VAISHNAVI KAVATAGI — Frontend Interface, Security Session State Controls & Dashboard Visualizations

---

### 🛡️ Secure • Reliable • Intelligent

DBMS Mini Project
