<?php

session_start();

$logged_in_do_id = 101; 


$host = 'localhost';
$dbname = 'to_inventory';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


$sql = "SELECT id, name, position, destination, departure_date, created_at 
        FROM travel_orders 
        WHERE officer_id = :officer_id AND status = 'pending_do' 
        ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':officer_id', $logged_in_do_id, PDO::PARAM_INT);
$stmt->execute();
$pending_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Division Officer Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            padding: 40px;
            color: #2c3e50;
        }
        .dashboard-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        h2 { border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dcdde1;
        }
        th { background-color: #f8f9fa; color: #2c3e50; }
        tr:hover { background-color: #f1f2f6; }
        .btn-review {
            background-color: #3498db;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        .btn-review:hover { background-color: #2980b9; }
        .empty-state { text-align: center; padding: 40px; color: #7f8c8d; }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h2>Welcome, <?php $username ?> </h2>
        <p>You have <strong><?php echo count($pending_orders); ?></strong> travel orders waiting for your approval.</p>

        <?php if (count($pending_orders) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Date Submitted</th>
                        <th>Applicant Name</th>
                        <th>Position</th>
                        <th>Destination</th>
                        <th>Departure Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_orders as $order): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($order['name']); ?></td>
                            <td><?php echo htmlspecialchars($order['position']); ?></td>
                            <td><?php echo htmlspecialchars($order['destination']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['departure_date'])); ?></td>
                            <td>
                                <a href="review_to.php?id=<?php echo $order['id']; ?>" class="btn-review">Review & Approve</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <h3>All caught up!</h3>
                <p>There are no pending travel orders requiring your attention right now.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>