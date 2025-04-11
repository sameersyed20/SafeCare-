<?php
// Include database connection
require_once 'db_connection.php';

// Function to generate unique application ID
function generateApplicationId() {
    return 'FC-' . mt_rand(100000, 999999);
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
    echo "Form submitted successfully!";
    exit;
} else {
    echo "Invalid request method.";
    exit;
}

// Generate unique application ID
$application_id = generateApplicationId();

// Sanitize form data
$full_name = sanitizeInput($_POST['full-name']);
$email = sanitizeInput($_POST['email']);
$phone = sanitizeInput($_POST['phone']);
$address = sanitizeInput($_POST['address']);
$city = sanitizeInput($_POST['city']);
$state = sanitizeInput($_POST['state']);
$pincode = sanitizeInput($_POST['pincode']);
$dob = sanitizeInput($_POST['dob']);
$gender = sanitizeInput($_POST['gender']);
$card_type = sanitizeInput($_POST['card-type']);
$family_members = (int)sanitizeInput($_POST['family-members']);

// File handling
$upload_dir = "uploads/family_card/";

// Create directory if it doesn't exist
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Process ID proof file
$id_proof_file = "";
if(isset($_FILES['id-proof']) && $_FILES['id-proof']['error'] == 0) {
    $id_proof_name = $application_id . "_id_" . basename($_FILES['id-proof']['name']);
    $id_proof_path = $upload_dir . $id_proof_name;
    
    if(move_uploaded_file($_FILES['id-proof']['tmp_name'], $id_proof_path)) {
        $id_proof_file = $id_proof_path;
    }
}

// Process address proof file
$address_proof_file = "";
if(isset($_FILES['address-proof']) && $_FILES['address-proof']['error'] == 0) {
    $address_proof_name = $application_id . "_address_" . basename($_FILES['address-proof']['name']);
    $address_proof_path = $upload_dir . $address_proof_name;
    
    if(move_uploaded_file($_FILES['address-proof']['tmp_name'], $address_proof_path)) {
        $address_proof_file = $address_proof_path;
    }
}

// Process photo file
$photo_file = "";
if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $photo_name = $application_id . "_photo_" . basename($_FILES['photo']['name']);
    $photo_path = $upload_dir . $photo_name;
    
    if(move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
        $photo_file = $photo_path;
    }
}

// Prepare and execute SQL statement for main application
$stmt = $conn->prepare("INSERT INTO family_card_applications (application_id, full_name, email, phone, address, city, state, pincode, dob, gender, card_type, family_members, id_proof_file, address_proof_file, photo_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssssss", $application_id, $full_name, $email, $phone, $address, $city, $state, $pincode, $dob, $gender, $card_type, $family_members, $id_proof_file, $address_proof_file, $photo_file);

if ($stmt->execute()) {
    $main_result = true;
} else {
    error_log("Database error: " . $stmt->error); // Log the error
    $main_result = false;
}
$stmt->close();

// Process family members if any
$family_success = true;

if($family_members > 1) {
    for($i = 1; $i < $family_members; $i++) {
        if(isset($_POST["member-name-$i"])) {
            $member_name = sanitizeInput($_POST["member-name-$i"]);
            $member_relation = sanitizeInput($_POST["member-relation-$i"]);
            $member_dob = sanitizeInput($_POST["member-dob-$i"]);
            $member_gender = sanitizeInput($_POST["member-gender-$i"]);
            
            $stmt = $conn->prepare("INSERT INTO family_members (application_id, name, relation, dob, gender) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $application_id, $member_name, $member_relation, $member_dob, $member_gender);
            
            if(!$stmt->execute()) {
                error_log("Database error: " . $stmt->error); // Log the error
                $family_success = false;
            }
            
            $stmt->close();
        }
    }
}

// Check if all operations were successful
if($main_result && $family_success) {
    // Return success response as JSON
    $response = [
        'status' => 'success',
        'message' => 'Application submitted successfully!',
        'application_id' => $application_id
    ];
    
    echo json_encode($response);
} else {
    // Return error response as JSON
    $response = [
        'status' => 'error',
        'message' => 'Error submitting application. Please try again.'
    ];
    
    echo json_encode($response);
}
 else {
    // If not a POST request, redirect to the form page
    header("Location: family-card.html");
    exit();
}
?>