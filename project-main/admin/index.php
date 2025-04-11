<?php
// Start session for admin authentication
session_start();

// Check if admin is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once '../db_connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeCare Hospital - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #0077b6;
            --primary-dark: #005f8b;
            --text-dark: #333;
            --text-light: #666;
            --background-light-blue: #f0f8ff;
            --border-color: #ddd;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: var(--background-light-blue);
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: white;
            box-shadow: var(--box-shadow);
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }
        
        .logo h1 {
            color: var(--primary-color);
            font-size: 1.5rem;
        }
        
        .nav-menu {
            padding: 20px 0;
        }
        
        .nav-item {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .nav-item:hover, .nav-item.active {
            background-color: var(--background-light-blue);
            color: var(--primary-color);
        }
        
        .nav-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background-color: white;
            padding: 15px 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .header h2 {
            color: var(--primary-color);
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .user-name {
            font-weight: 600;
        }
        
        .logout {
            color: var(--text-light);
            text-decoration: none;
            margin-left: 15px;
            transition: var(--transition);
        }
        
        .logout:hover {
            color: #dc3545;
        }
        
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .card-icon.blue {
            background-color: var(--primary-color);
        }
        
        .card-icon.green {
            background-color: #4caf50;
        }
        
        .card-icon.orange {
            background-color: #ff9800;
        }
        
        .card-title {
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .data-table {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table-title {
            font-size: 1.2rem;
            color: var(--primary-color);
        }
        
        .table-actions {
            display: flex;
            gap: 10px;
        }
        
        .table-filter, .table-export {
            padding: 8px 15px;
            background-color: var(--background-light-blue);
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .table-filter:hover, .table-export:hover {
            background-color: #e0f0ff;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--background-light-blue);
            color: var(--primary-color);
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            display: inline-block;
        }
        
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status.approved {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status.rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status.scheduled {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status.completed {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        
        .status.cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status.open {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .status.closed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            margin-right: 5px;
        }
        
        .view-btn {
            background-color: var(--primary-color);
            color: white;
        }
        
        .view-btn:hover {
            background-color: var(--primary-dark);
        }
        
        .edit-btn {
            background-color: #ffc107;
            color: #212529;
        }
        
        .edit-btn:hover {
            background-color: #e0a800;
        }
        
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        
        .delete-btn:hover {
            background-color: #c82333;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .page-item {
            margin: 0 5px;
        }
        
        .page-link {
            display: block;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            background-color: white;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
            box-shadow: var(--box-shadow);
        }
        
        .page-link:hover, .page-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .tab-container {
            margin-bottom: 20px;
        }
        
        .tabs {
            display: flex;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }
        
        .tab {
            padding: 15px 20px;
            cursor: pointer;
            transition: var(--transition);
            border-bottom: 3px solid transparent;
            font-weight: 600;
        }
        
        .tab:hover {
            background-color: var(--background-light-blue);
        }
        
        .tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 70px;
                overflow: visible;
            }
            
            .logo h1, .nav-item span {
                display: none;
            }
            
            .nav-item i {
                margin-right: 0;
                font-size: 1.2rem;
            }
            
            .main-content {
                margin-left: 70px;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-info {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <h1>SafeCare Admin</h1>
            </div>
            <div class="nav-menu">
                <a href="index.php" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="family_cards.php" class="nav-item">
                    <i class="fas fa-id-card"></i>
                    <span>Family Cards</span>
                </a>
                <a href="appointments.php" class="nav-item">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
                <a href="insurance.php" class="nav-item">
                    <i class="fas fa-file-medical"></i>
                    <span>Insurance</span>
                </a>
                <a href="settings.php" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h2>Dashboard</h2>
                <div class="user-info">
                    <img src="/placeholder.svg?height=40&width=40" alt="Admin">
                    <div>
                        <div class="user-name">Admin User</div>
                        <a href="logout.php" class="logout">Logout</a>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-cards">
                <?php
                // Count family card applications
                $family_card_query = "SELECT COUNT(*) as count FROM family_card_applications";
                $family_card_result = $conn->query($family_card_query);
                $family_card_count = $family_card_result->fetch_assoc()['count'];
                
                // Count appointments
                $appointments_query = "SELECT COUNT(*) as count FROM appointments";
                $appointments_result = $conn->query($appointments_query);
                $appointments_count = $appointments_result->fetch_assoc()['count'];
                
                // Count insurance queries
                $insurance_query = "SELECT COUNT(*) as count FROM insurance_queries";
                $insurance_result = $conn->query($insurance_query);
                $insurance_count = $insurance_result->fetch_assoc()['count'];
                ?>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Family Card Applications</div>
                            <div class="card-value"><?php echo $family_card_count; ?></div>
                        </div>
                        <div class="card-icon blue">
                            <i class="fas fa-id-card"></i>
                        </div>
                    </div>
                    <a href="family_cards.php" style="color: var(--primary-color); text-decoration: none;">View all applications</a>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Appointments</div>
                            <div class="card-value"><?php echo $appointments_count; ?></div>
                        </div>
                        <div class="card-icon green">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <a href="appointments.php" style="color: var(--primary-color); text-decoration: none;">View all appointments</a>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Insurance Queries</div>
                            <div class="card-value"><?php echo $insurance_count; ?></div>
                        </div>
                        <div class="card-icon orange">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                    <a href="insurance.php" style="color: var(--primary-color); text-decoration: none;">View all queries</a>
                </div>
            </div>
            
            <div class="tab-container">
                <div class="tabs">
                    <div class="tab active" data-tab="recent-applications">Recent Applications</div>
                    <div class="tab" data-tab="recent-appointments">Recent Appointments</div>
                    <div class="tab" data-tab="recent-queries">Recent Insurance Queries</div>
                </div>
            </div>
            
            <div class="tab-content active" id="recent-applications">
                <div class="data-table">
                    <div class="table-header">
                        <div class="table-title">Recent Family Card Applications</div>
                        <div class="table-actions">
                            <button class="table-filter"><i class="fas fa-filter"></i> Filter</button>
                            <button class="table-export"><i class="fas fa-download"></i> Export</button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Card Type</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get recent family card applications
                            $recent_applications_query = "SELECT * FROM family_card_applications ORDER BY application_date DESC LIMIT 5";
                            $recent_applications_result = $conn->query($recent_applications_query);
                            
                            if ($recent_applications_result->num_rows > 0) {
                                while($row = $recent_applications_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['application_id'] . "</td>";
                                    echo "<td>" . $row['full_name'] . "</td>";
                                    echo "<td>" . $row['email'] . "</td>";
                                    echo "<td>" . $row['card_type'] . "</td>";
                                    echo "<td>" . date('d M Y', strtotime($row['application_date'])) . "</td>";
                                    echo "<td><span class='status " . strtolower($row['status']) . "'>" . $row['status'] . "</span></td>";
                                    echo "<td>
                                            <button class='action-btn view-btn'><i class='fas fa-eye'></i></button>
                                            <button class='action-btn edit-btn'><i class='fas fa-edit'></i></button>
                                            <button class='action-btn delete-btn'><i class='fas fa-trash'></i></button>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center;'>No applications found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="tab-content" id="recent-appointments">
                <div class="data-table">
                    <div class="table-header">
                        <div class="table-title">Recent Appointments</div>
                        <div class="table-actions">
                            <button class="table-filter"><i class="fas fa-filter"></i> Filter</button>
                            <button class="table-export"><i class="fas fa-download"></i> Export</button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient Name</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get recent appointments
                            $recent_appointments_query = "SELECT * FROM appointments ORDER BY booking_date DESC LIMIT 5";
                            $recent_appointments_result = $conn->query($recent_appointments_query);
                            
                            if ($recent_appointments_result->num_rows > 0) {
                                while($row = $recent_appointments_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['booking_id'] . "</td>";
                                    echo "<td>" . $row['patient_name'] . "</td>";
                                    echo "<td>" . $row['department'] . "</td>";
                                    echo "<td>" . date('d M Y', strtotime($row['appointment_date'])) . "</td>";
                                    echo "<td>" . $row['appointment_time'] . "</td>";
                                    echo "<td><span class='status " . strtolower($row['status']) . "'>" . $row['status'] . "</span></td>";
                                    echo "<td>
                                            <button class='action-btn view-btn'><i class='fas fa-eye'></i></button>
                                            <button class='action-btn edit-btn'><i class='fas fa-edit'></i></button>
                                            <button class='action-btn delete-btn'><i class='fas fa-trash'></i></button>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center;'>No appointments found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="tab-content" id="recent-queries">
                <div class="data-table">
                    <div class="table-header">
                        <div class="table-title">Recent Insurance Queries</div>
                        <div class="table-actions">
                            <button class="table-filter"><i class="fas fa-filter"></i> Filter</button>
                            <button class="table-export"><i class="fas fa-download"></i> Export</button>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Insurance Provider</th>
                                <th>Query Type</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get recent insurance queries
                            $recent_queries_query = "SELECT * FROM insurance_queries ORDER BY submission_date DESC LIMIT 5";
                            $recent_queries_result = $conn->query($recent_queries_query);
                            
                            if ($recent_queries_result->num_rows > 0) {
                                while($row = $recent_queries_result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['query_id'] . "</td>";
                                    echo "<td>" . $row['full_name'] . "</td>";
                                    echo "<td>" . $row['insurance_provider'] . "</td>";
                                    echo "<td>" . $row['query_type'] . "</td>";
                                    echo "<td>" . date('d M Y', strtotime($row['submission_date'])) . "</td>";
                                    echo "<td><span class='status " . strtolower($row['status']) . "'>" . $row['status'] . "</span></td>";
                                    echo "<td>
                                            <button class='action-btn view-btn'><i class='fas fa-eye'></i></button>
                                            <button class='action-btn edit-btn'><i class='fas fa-edit'></i></button>
                                            <button class='action-btn delete-btn'><i class='fas fa-trash'></i></button>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' style='text-align: center;'>No queries found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Tab switching functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.getAttribute('data-tab');
                
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                tab.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>
</html>