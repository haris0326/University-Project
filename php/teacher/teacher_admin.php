<?php

session_start(); // Start the session

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page if not logged in
    header("Location: http://localhost/University-Project/stand_ford/php/admin_login.php");
    exit();
}

include 'teacher_db.php';

// Handle approval or rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['id'], $_POST['action'])) {
        $teacher_id = $_POST['id'];
        $action = $_POST['action']; // 'approve' or 'reject'

        if($action === 'approve' || $action === 'reject') {
            $is_approved = ($action == 'approve') ? 1 : -1;

            $sql = "UPDATE teacher SET is_approved = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param("ii", $is_approved, $teacher_id);

            

            if ($stmt->execute()) {
                $message = "Teacher status updated successfully.";
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $message = "Invalid action.";
        }
    } else {
        $message = "Incomplete parameters.";
    }
}

// Fetch pending teachers
$sql = "SELECT * FROM teacher WHERE is_approved = 0";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Admin Panel - Teacher Approval</title>

    <style>
        /* Insert the improved CSS here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f7f7f7;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }

        p {
            color: #333;
            text-align: center;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        button a {
            color: white;
            text-decoration: none;
        }

        button:hover {
            background-color: #45a049;
        }

        .button-container {
            text-align: center;
            margin: 20px 0;
        }

        .back-button {
            background-color: #555;
        }

    </style>
    
</head>
<body>
    <div class="container">
        <h2>Admin Panel - Teacher Approval</h2>
        
        <?php if (isset($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Qualifications</th>
                        <th>Experience</th>
                        <th>Degree</th>
                        <!-- <th>Subjects</th> -->
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['qualifications']; ?></td>
                            <td><?php echo $row['experience']; ?></td>
                            <td><?php echo $row['degree']; ?></td>
                            <!-- <td><?php // echo $row['subject_name']; ?></td> -->
                            <td><?php echo $row['address']; ?></td>
                            <td>
                            <form action="teacher_admin.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit">Approve</button>
                        </form>
                        <form action="teacher_admin.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="action" value="reject">
                            <button type="submit">Reject</button>
                        </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending teachers.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>
    <div class="button-container">
        <button class="back-button"><a href="http://localhost/University-Project/stand_ford/php/admin_home.php">Back to Admin Dashboard</a></button>
    </div>
</body>
</html>
