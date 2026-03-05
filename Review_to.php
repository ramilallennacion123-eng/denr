<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "to_inventory");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No Travel Order ID provided.");
}

$order_id = $_GET['id'];
$logged_in_do_id = 102; 

$do_saved_signature = 'uploads/signatures/do_' . $logged_in_do_id . '.png';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_btn'])) {
    
    $update_sql = "UPDATE travel_orders SET status = 'pending_rd',do_signature = ? WHERE id = ? AND officer_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    
    if ($update_stmt) {
        mysqli_stmt_bind_param($update_stmt, "sii", $do_saved_signature, $order_id, $logged_in_do_id);
        mysqli_stmt_execute($update_stmt);
        mysqli_stmt_close($update_stmt);
        
        header("Location: do_dashboard.php");
        exit();
    } else {
        die("Error updating record: " . mysqli_error($conn));
    }
}

$sql = "SELECT * FROM travel_orders WHERE id = ? AND status = 'pending_do'";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    die("Error fetching data: " . mysqli_error($conn));
}

if (!$order) {
    die("Travel Order not found, or it has already been processed.");
}

$purposes = json_decode($order['purpose'], true);
$assistants = json_decode($order['assistants'], true);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Travel Order</title>
    <style>
       :root { --primary-color: #2c3e50; --accent-color: #27ae60; --bg-color: #f4f7f6; --card-bg: #ffffff; --border-color: #dcdde1; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); padding: 40px 20px; display: flex; justify-content: center; }
        .box { width: 100%; max-width: 850px; background-color: var(--card-bg); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 40px; }
        h1 { color: var(--primary-color); text-align: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 30px;}
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .detail-item { background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); }
        .detail-item strong { display: block; font-size: 0.85rem; color: #7f8c8d; text-transform: uppercase; margin-bottom: 5px; }
        .full-width { grid-column: span 2; }
        ul { margin: 0; padding-left: 20px; }
        .signature-box { text-align: center; margin-top: 20px; padding: 20px; border: 2px dashed #bdc3c7; background: #fafafa; }
        .signature-box img { max-width: 250px; max-height: 120px; margin-top: 10px; }
        .action-area { margin-top: 40px; border-top: 2px solid #eee; padding-top: 20px; }
        .auth-section { background: #e8f4f8; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bce0ee; text-align: center; }
        .auth-section img { max-width: 200px; max-height: 80px; margin-top: 10px; border-bottom: 1px solid #bdc3c7; }
        .button-group { display: flex; justify-content: space-between; align-items: center; }
        .btn { padding: 12px 25px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; text-decoration: none; font-size: 1rem; transition: background 0.2s; }
        .btn-cancel { background-color: #95a5a6; color: white; }
        .btn-approve { background-color: var(--accent-color); color: white; }
        .btn-approve:hover { background-color: #219653; }
    </style>
</head>
<body>

    <div class="box">
        <h1>Review Travel Order</h1>
        
        <div class="detail-grid">
            <div class="detail-item"><strong>Name</strong> <?php echo htmlspecialchars($order['name']); ?></div>
            <div class="detail-item"><strong>Position</strong> <?php echo htmlspecialchars($order['position']); ?></div>
            <div class="detail-item"><strong>Division/Unit</strong> <?php echo htmlspecialchars($order['division_unit']); ?></div>
            <div class="detail-item"><strong>Salary</strong> <?php echo htmlspecialchars($order['salary']); ?></div>
            
            <div class="detail-item"><strong>Official Station</strong> <?php echo htmlspecialchars($order['official_station']); ?></div>
            <div class="detail-item"><strong>Destination</strong> <?php echo htmlspecialchars($order['destination']); ?></div>
            <div class="detail-item"><strong>Departure Date</strong> <?php echo htmlspecialchars($order['departure_date']); ?></div>
            <div class="detail-item"><strong>Arrival Date</strong> <?php echo htmlspecialchars($order['arrival_date']); ?></div>

            <div class="detail-item full-width">
                <strong>Purpose of Travel</strong>
                <ul>
                    <?php 
                    if (!empty($purposes)) {
                        foreach ($purposes as $p) { echo "<li>" . htmlspecialchars($p) . "</li>"; }
                    } else { echo "<li>None specified</li>"; }
                    ?>
                </ul>
            </div>

            <div class="detail-item full-width">
                <strong>Assistants Allowed</strong>
                <ul>
                    <?php 
                    if (!empty($assistants)) {
                        foreach ($assistants as $a) { echo "<li>" . htmlspecialchars($a) . "</li>"; }
                    } else { echo "<li>None specified</li>"; }
                    ?>
                </ul>
            </div>
            
            <div class="detail-item"><strong>Per Diems/Expenses</strong> <?php echo htmlspecialchars($order['per_diems']); ?></div>
            <div class="detail-item"><strong>Appropriation</strong> <?php echo htmlspecialchars($order['appropriation']); ?></div>
            
            <div class="detail-item full-width"><strong>Remarks</strong> <?php echo htmlspecialchars($order['remarks']); ?></div>
        </div>

        <div class="signature-box">
            <strong>Applicant E-Signature</strong><br>
            <?php if (!empty($order['applicant_signature'])): ?>
                <img src="<?php echo htmlspecialchars($order['applicant_signature']); ?>" alt="Applicant Signature">
            <?php else: ?>
                <p>No signature uploaded.</p>
            <?php endif; ?>
        </div>
        <form method="post" class="action-area">
            
            <div class="auth-section">
                <p>By clicking approve, your digital signature will be attached:</p>
                <img src="<?php echo htmlspecialchars($do_saved_signature); ?>" alt="Your Signature">
            </div>

            <div class="button-group">
                <a href="division_officer.php" class="btn btn-cancel">Go Back</a>
                <button type="submit" name="approve_btn" class="btn btn-approve">
                    Sign, Approve & Forward to Regional Director
                </button>
            </div>
        </form>

    </div>

</body>
</html>