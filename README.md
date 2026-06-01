# 🛡️ Smart Fraud Detection System

## 🏦 Secure Banking Transaction Monitoring Platform

A web-based fraud detection system developed using **PHP**, **MySQL**, **HTML5**, **CSS3**, and **JavaScript** that monitors banking transactions and automatically flags suspicious activities using rule-based fraud detection.

---

## 📖 Project Overview

The Smart Fraud Detection System is a DBMS-based banking application that allows customers to perform transactions while automatically detecting suspicious activities.

The system records every transaction, applies fraud detection rules, stores transaction history in a MySQL database, and provides an Admin Dashboard for monitoring and analysis.

---

## 🎯 Project Objectives

* Secure user registration and login
* Record customer transactions
* Detect potentially fraudulent transactions
* Maintain transaction history
* Provide administrative monitoring tools
* Demonstrate practical DBMS concepts

---

## ✨ Features

### 👤 User Management

* User Registration
* Secure Login Authentication
* Password Hashing
* Session-Based Access Control

### 💳 Transaction Processing

* Customer Transaction Portal
* Real-Time Transaction Submission
* Transaction Logging
* Location Tracking
* Fraud Status Generation

### 🚩 Fraud Detection Rules

#### Rule 1: High Amount Detection

Transactions greater than ₹20,000 are automatically flagged.

#### Rule 2: Location Mismatch Detection

If the customer's current transaction location differs from the previously recorded location, the transaction is marked as suspicious.

---

## 📊 Admin Dashboard

* Total Transaction Volume
* Total Transactions Processed
* Fraud Count
* Fraud Percentage
* View All Transactions
* Fraud Highlighting

---

## 🗄️ Database Tables

### Users

| Field         | Description           |
| ------------- | --------------------- |
| id            | Primary Key           |
| name          | Customer Name         |
| account_no    | Unique Account Number |
| password      | Hashed Password       |
| role          | User Role             |
| last_location | Last Known Location   |

### Transactions

| Field      | Description           |
| ---------- | --------------------- |
| id         | Primary Key           |
| user_id    | Foreign Key           |
| amount     | Transaction Amount    |
| location   | Transaction Location  |
| is_fraud   | Fraud Status          |
| trans_time | Transaction Timestamp |

---

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

MANASA N S

### Collaborator

VAISHNAVI KAVATAGI

---

### 🛡️ Secure • Reliable • Intelligent

DBMS Mini Project
