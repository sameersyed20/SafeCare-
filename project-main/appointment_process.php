<?php
// Include database connection
require_once 'db_connection.php';

// Function to generate unique booking ID
function generateBookingId() {
    return 'HC-' . mt_rand(100000, 999999);
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
    // Generate unique booking ID
    $booking_id = generateBookingId();
    
    // Sanitize form data
    $patient_name = sanitizeInput($_POST['patient-name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $appointment_date = sanitizeInput($_POST['appointment-date']);
    $appointment_time = sanitizeInput($_POST['appointment-time']);
    $branch = sanitizeInput($_POST['branch']);
    $department = sanitizeInput($_POST['department']);
    $doctor = isset($_POST['doctor']) ? sanitizeInput($_POST['doctor']) : null;
    $reason = sanitizeInput($_POST['reason']);
    $previous_visit = isset($_POST['previous-visit']) ? 1 : 0;
    $special_requirements = isset($_POST['special-requirements']) ? sanitizeInput($_POST['special-requirements']) : null;
    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO appointments (booking_id, patient_name, email, phone, appointment_date, appointment_time, branch, department, doctor, reason, previous_visit, special_requirements) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssssssss", $booking_id, $patient_name, $email, $phone, $appointment_date, $appointment_time, $branch, $department, $doctor, $reason, $previous_visit, $special_requirements);
    
    if($stmt->execute()) {
        // Return success response as JSON
        $response = [
            'status' => 'success',
            'message' => 'Appointment booked successfully!',
            'booking_id' => $booking_id
        ];
        
        echo json_encode($response);
    } else {
        // Return error response as JSON
        $response = [
            'status' => 'error',
            'message' => 'Error booking appointment. Please try again.'
        ];
        
        echo json_encode($response);
    }
    
    $stmt->close();
} else {
    // If not a POST request, redirect to the form page
    header("Location: appointment.html");
    exit();
}
?>