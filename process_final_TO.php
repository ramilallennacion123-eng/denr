<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "to_inventory");


if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $purposes_json = isset($_POST['Purpose']) ? json_encode($_POST['Purpose']) : '[]';
    $assistants_json = isset($_POST['Assistants']) ? json_encode($_POST['Assistants']) : '[]';
    $signature_path = '';
    if (isset($_FILES['e_signature']) && $_FILES['e_signature']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/signatures/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_tmp_path = $_FILES['e_signature']['tmp_name'];
        $file_name = $_FILES['e_signature']['name'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = array('jpg', 'jpeg', 'png');

        if (in_array($file_extension, $allowed_exts)) {
            $new_file_name = uniqid('sig_', true) . '.' . $file_extension;
            $destination = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp_path, $destination)) {
                $signature_path = $destination;
            } else {
                die("Error moving the uploaded file.");
            }
        } else {
            die("Invalid file type. Only JPG, JPEG, and PNG are allowed.");
        }
    } else {
        die("Please upload an e-signature.");
    }
    $sql = "INSERT INTO travel_orders (
                name, salary, position, division_unit, departure_date, 
                official_station, destination, arrival_date, purpose, 
                per_diems, assistants, appropriation, remarks, 
                officer_id, applicant_signature, status
            ) VALUES (
                ?, ?, ?, ?, ?, 
                ?, ?, ?, ?, 
                ?, ?, ?, ?, 
                ?, ?, 'pending_do'
            )";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssssssssss", 
            $_POST['Name'], 
            $_POST['Salary'], 
            $_POST['Position'], 
            $_POST['Div/Sec/Unit'], 
            $_POST['Departure_Date'], 
            $_POST['Official_Station'], 
            $_POST['Destination'], 
            $_POST['Arrival_Date'], 
            $purposes_json, 
            $_POST['Per_Diems'], 
            $assistants_json, 
            $_POST['Appropriation'], 
            $_POST['Remarks'], 
            $_POST['Officer'], 
            $signature_path
        );

        if (mysqli_stmt_execute($stmt)) {
            echo "Travel order successfully submitted and is awaiting Division Officer approval.";
             header("Location: message.php"); 
        } else {
            die("Error saving to database: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error preparing statement: " . mysqli_error($conn));
    }
    mysqli_close($conn);
}
?>