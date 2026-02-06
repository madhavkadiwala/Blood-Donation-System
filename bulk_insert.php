<?php
$conn = new mysqli("localhost", "root", "", "blood_donation_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, "r");

    if ($handle) {
        fgetcsv($handle, 1000, ",");

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $name = $data[0];
            $age = $data[1];
            $blood_type = $data[2];
            $contact_number = $data[3];

            $sql = "INSERT INTO donations ($name, $age, $blood_type, $contact_number) 
                    VALUES ($name, $age, $blood_type, $contact_number)";

            if (!$conn->query($sql)) {
                echo "Error inserting data: " . "<br>";
            }
        }
        fclose($handle);
        echo "Data inserted successfully.";
    } else {
        echo "Error opening the file.";
    }

    header("Location: view.php");
    exit();
}
?>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<header>
    <h2>Donation System</h2>
    <nav>
        <a href="insert.php">Insert</a>
        <a href="view.php">View</a>
        <a href="bulk_insert.php">Bulk Insert</a>
    </nav>
</header>
<body>
    <form method="post" enctype="multipart/form-data">
        Upload CSV File: <input type="file" name="csv_file"><br>
        <input type="submit" value="Upload">
    </form>
</body>
</html>