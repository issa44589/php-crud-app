<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simple_project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// إعداد ملف Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=records_export.xls");
header("Pragma: no-cache");
header("Expires: 0");

// إخراج العناوين
echo "ID\tName\tEmail\tPhone\tCreated At\n";

// جلب البيانات من قاعدة البيانات
$result = $conn->query("SELECT id, name, email, phone, created_at FROM records");

while ($row = $result->fetch_assoc()) {
    echo $row['id'] . "\t" .
         $row['name'] . "\t" .
         $row['email'] . "\t" .
         $row['phone'] . "\t" .
         $row['created_at'] . "\n";
}

$conn->close();
?>
