<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Donor Records</title>
        <link rel="stylesheet" href="styles.css"> <!-- Ensure this path is correct -->
    </head>
    <body>
        <div class="container">
            <h1>Donor Records</h1>

            <!-- Display feedback message if available -->
            <?php
            if (isset($_GET['message'])) {
                echo "<p class='success-message'>" . htmlspecialchars($_GET['message']) . "</p>";
            }
            ?>

            <!-- Form for deleting selected records -->
            <form method="POST" action="delete.php">
                <table class="record-table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Blood Type</th>
                            <th>Contact Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection
                        $conn = new mysqli('localhost', 'root', '', 'blood_donation_system');
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Fetch records
                        $sql = "SELECT * FROM donors";
                        $result = $conn->query($sql);

                        // Check if there's a record to highlight
                        $highlight_id = isset($_GET['highlight']) ? intval($_GET['highlight']) : null;

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Add 'highlight' class if this row is the one to be highlighted
                                $highlight_class = ($highlight_id == $row['id']) ? 'highlight' : '';
                                echo "<tr class='$highlight_class'>";
                                echo "<td><input type='checkbox' name='delete_ids[]' value='" . $row['id'] . "'></td>";
                                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['contact_number']) . "</td>";
                                echo "<td><a href='update.php?id=" . $row['id'] . "' class='button'>Update</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No records found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
                <div class="button-container">
                    <button type="submit" class="button button-submit">Delete Selected</button>
                    <a href="insert.php" class="button button-submit">Add New Donor</a>
                </div>
            </form>
        </div>
    </body>
</html>
