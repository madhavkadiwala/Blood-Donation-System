<?php
$conn = new mysqli('localhost', 'root', '', 'blood_donation_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// variables
$id = $name = $age = $blood_type = $contact_number = '';

// Get the ID from the query string
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch record for the given ID
    $sql = "SELECT * FROM donors WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);
        $age = htmlspecialchars($row['age']);
        $blood_type = htmlspecialchars($row['blood_type']);
        $contact_number = htmlspecialchars($row['contact_number']);
    } else {
        die("Record not found.");
    }

    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $age = $_POST['age'];
    $blood_type = $_POST['blood_type'];
    $contact_number = $_POST['contact_number'];

    // Update record
    $sql = "UPDATE donors SET name = ?, age = ?, blood_type = ?, contact_number = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sisis', $name, $age, $blood_type, $contact_number, $id);

    if ($stmt->execute()) {
        $message = "Record updated successfully.";
        // message and ID to highlight the updated record
        header("Location: view.php?message=" . urlencode($message) . "&highlight=" . $id);
        exit;
    } else {
        $message = "Error updating record: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Update Donor Record</title>
        <link rel="stylesheet" href="styles.css"> <!-- Ensure this path is correct -->
    </head>
    <body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #e0f7fa; margin: 0; padding: 20px; box-sizing: border-box;">
        <div style="max-width: 800px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <h1 style="text-align: center; color: #00796b;">Update Donor Record</h1>
            <?php
            // Display feedback message if available
            if (isset($message)) {
                echo "<p class='success-message'>" . htmlspecialchars($message) . "</p>";
            }
            ?>
            <form method="POST" action="update.php">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="age" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="blood_type" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Blood Type:</label>
                    <select id="blood_type" name="blood_type" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                        <option value="" disabled>Select Blood Type</option>
                        <option value="A+" <?php if ($blood_type === 'A+') echo 'selected'; ?>>A+</option>
                        <option value="A-" <?php if ($blood_type === 'A-') echo 'selected'; ?>>A-</option>
                        <option value="B+" <?php if ($blood_type === 'B+') echo 'selected'; ?>>B+</option>
                        <option value="B-" <?php if ($blood_type === 'B-') echo 'selected'; ?>>B-</option>
                        <option value="O+" <?php if ($blood_type === 'O+') echo 'selected'; ?>>O+</option>
                        <option value="O-" <?php if ($blood_type === 'O-') echo 'selected'; ?>>O-</option>
                        <option value="AB+" <?php if ($blood_type === 'AB+') echo 'selected'; ?>>AB+</option>
                        <option value="AB-" <?php if ($blood_type === 'AB-') echo 'selected'; ?>>AB-</option>
                    </select>
                </div>

                <div style="margin-bottom: 15px;">
                    <label for="contact_number" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Contact Number:</label>
                    <input type="text" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                </div>

                <div class="button-container" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;">
                    <button type="submit" class="button" style="width: auto; padding: 12px 20px; background-color: #00796b; color: #ffffff; border: none; border-radius: 5px; font-size: 18px; cursor: pointer; transition: background-color 0.3s ease;">Update Record</button>
                    <a href="view.php" class="button" style="padding: 12px 20px; background-color: #00796b; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 18px; cursor: pointer; transition: background-color 0.3s ease;">Cancel</a>
                </div>
            </form>

        </div>
    </body>
</html>
