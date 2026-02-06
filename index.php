<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Blood Donation System</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #e0f7fa; margin: 0; padding: 20px; box-sizing: border-box;">
        <div style="max-width: 800px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <h1 style="text-align: center; color: #00796b;">Blood Donation System</h1>

            <?php
            $errors = []; //an empty array for errors
            $success_message = ''; // the success message variable

            if (!empty($errors)) {
                echo "<div style='color: red; text-align: center; font-weight: bold;'>" . implode('<br>', $errors) . "</div>";
            }
            if ($success_message) {
                echo "<div style='color: green; text-align: center; font-weight: bold;'>$success_message</div>";
            }
            ?>

            <!--<h1>Blood Donation System</h1>
            <h1>Insert New Donor</h1>-->

            <?php
            $conn = new mysqli("localhost", "root", "", "blood_donation_system");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['name'])) {
                    // Insert single record
                    $name = $conn->real_escape_string($_POST['name']);
                    $age = (int) $_POST['age'];
                    $blood_type = $conn->real_escape_string($_POST['blood_type']);
                    $contact_number = $conn->real_escape_string($_POST['contact_number']);

                    $sql = "INSERT INTO donors (name, age, blood_type, contact_number) 
                    VALUES ('$name', $age, '$blood_type', '$contact_number')";

                    if ($conn->query($sql) === TRUE) {
                        $success_message = "Record inserted successfully."; // Set success message
                    } else {
                        $errors[] = "Error: " . $conn->error;
                    }
                } elseif (isset($_FILES['csv_file'])) {
                    // Handle CSV file upload
                    $file = $_FILES['csv_file']['tmp_name'];
                    $handle = fopen($file, "r");
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $sql = "INSERT INTO donors (name, age, blood_type, contact_number) 
                        VALUES ('$data[0]', '$data[1]', '$data[2]', '$data[3]')";
                        $conn->query($sql);
                    }
                    fclose($handle);
                    $success_message = "CSV records inserted successfully."; // Set success message
                }
            }

            $conn->close();
            ?>

            <form action="" method="post" style="margin-bottom: 20px;">
                <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Full Name:</label>
                    <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="age" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Age:</label>
                    <input type="number" id="age" name="age" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="blood_type" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Blood Type:</label>
                    <select id="blood_type" name="blood_type" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                        <option value="" disabled selected>Select Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label for="contact_number" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Contact Number:</label>
                    <input type="text" id="contact_number" name="contact_number" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                </div>
                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: #00796b; color: #ffffff; border: none; border-radius: 5px; font-size: 18px; cursor: pointer; transition: background-color 0.3s ease;">Insert Record</button>
                </div>
            </form>

            <div style="text-align: center; margin-bottom: 20px;">
                <a href="view.php" style="display: inline-block; padding: 10px 20px; background-color: #00796b; color: #ffffff; text-decoration: none; border-radius: 5px; font-size: 16px; cursor: pointer; transition: background-color 0.3s ease;">View All Donors</a>
            </div>

            <h2 style="text-align: center; color: #00796b;">Bulk Insert from CSV</h2>
            <form method="post" enctype="multipart/form-data">
                <div style="margin-bottom: 15px;">
                    <label for="csv_file" style="display: block; margin-bottom: 5px; font-weight: bold; color: #004d40;">Upload CSV File:</label>
                    <input type="file" id="csv_file" name="csv_file" required style="width: 100%; padding: 10px; border: 1px solid #00796b; border-radius: 5px; font-size: 16px; color: #004d40; box-sizing: border-box;">
                </div>
                <div>
                    <button type="submit" style="width: 100%; padding: 12px; background-color: #00796b; color: #ffffff; border: none; border-radius: 5px; font-size: 18px; cursor: pointer; transition: background-color 0.3s ease;">Upload</button>
                </div>
            </form>

        </div>

    </body>
</html>
