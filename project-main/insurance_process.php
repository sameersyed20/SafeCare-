<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log the raw POST data
file_put_contents('debug.log', "Raw POST Data:\n" . print_r($_POST, true), FILE_APPEND);

// Output the received data
echo "Received Data:\n";
print_r($_POST);

// Include database connection
require_once 'db_connection.php';

// Function to generate unique query ID
function generateQueryId() {
    return 'INS-' . mt_rand(100000, 999999);
}

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging: Log the raw POST data
    file_put_contents('debug.log', "Raw POST Data:\n" . print_r($_POST, true), FILE_APPEND);

    // Generate unique query ID
    $query_id = generateQueryId();
    
    // Sanitize form data
    $full_name = sanitizeInput($_POST['full-name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $insurance_provider = sanitizeInput($_POST['insurance-provider']);
    $policy_number = isset($_POST['policy-number']) ? sanitizeInput($_POST['policy-number']) : null;
    $query_type = sanitizeInput($_POST['query-type']);
    $message = sanitizeInput($_POST['query-details']);

    // Debugging: Log the sanitized data
    file_put_contents('debug.log', "Sanitized Data:\n" . print_r([
        'query_id' => $query_id,
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'insurance_provider' => $insurance_provider,
        'policy_number' => $policy_number,
        'query_type' => $query_type,
        'message' => $message
    ], true), FILE_APPEND);

    // Debugging: Log the data being inserted
    file_put_contents('debug.log', print_r([
        'query_id' => $query_id,
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'insurance_provider' => $insurance_provider,
        'policy_number' => $policy_number,
        'query_type' => $query_type,
        'message' => $message
    ], true), FILE_APPEND);

    // Check for empty fields
    if (empty($full_name) || empty($email) || empty($phone) || empty($insurance_provider) || empty($query_type) || empty($message)) {
        die("Error: All required fields must be filled.");
    }

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO insurance_queries (query_id, full_name, email, phone, insurance_provider, policy_number, query_type, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $query_id, $full_name, $email, $phone, $insurance_provider, $policy_number, $query_type, $message);
    
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Insurance query submitted successfully!',
            'query_id' => $query_id
        ]);
    } else {
        die("Error executing query: " . $stmt->error);
    }
    
    $stmt->close();
} else {
    // If not a POST request, redirect to the form page
    header("Location: /safecare-website-main/project-main/insurance.html");
    exit();
}
?>