<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simple_project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL لإضافة قاعدة البيانات إذا لم تكن موجودة
$sql = "CREATE DATABASE IF NOT EXISTS simple_project";
$conn->query($sql);


// SQL لإنشاء الجدول إذا لم يكن موجودًا
$sql = "CREATE TABLE IF NOT EXISTS records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);




// إضافة بيانات عند استخدام POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO records (name, email, phone) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $phone);
    $stmt->execute();
    $stmt->close();
}



// حذف سجل عند استخدام GET مع معرف السجل
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM records WHERE id = $id");
}

// Fetch Data
// عدد السجلات في كل صفحة
$limit = 5;

// رقم الصفحة الحالية (إذا لم يُحدد، اجعله 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// بداية السجلات للعرض
$start = ($page - 1) * $limit;

// عدد السجلات الكلي
$total_result = $conn->query("SELECT COUNT(*) AS total FROM records");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];

// كم عدد الصفحات نحتاج؟
$total_pages = ceil($total_records / $limit);

// جلب السجلات الحالية فقط
$result = $conn->query("SELECT * FROM records ORDER BY id DESC LIMIT $start, $limit");

// عدد السجلات في كل صفحة
$limit = 5;

// رقم الصفحة الحالية (إذا لم يُحدد، اجعله 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// بداية السجلات للعرض
$start = ($page - 1) * $limit;

// عدد السجلات الكلي
$total_result = $conn->query("SELECT COUNT(*) AS total FROM records");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];

// كم عدد الصفحات نحتاج؟
$total_pages = ceil($total_records / $limit);

// جلب السجلات الحالية فقط
$result = $conn->query("SELECT * FROM records ORDER BY id DESC LIMIT $start, $limit");


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Simple Project</title>
    <style>
        /* إضافة خلفية */
        body {
            background-color: #f0f8ff; /* خلفية فاتحة */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* مركز المحتوى داخل الصفحة */
        .container {
            width: 80%;
            max-width: 800px;
            background-color: #ffffff; /* خلفية بيضاء للمحتوى */
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-right: 10px;
        }

        input[type="text"], input[type="email"] {
            padding: 8px;
            margin: 10px 0;
            width: 100%;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color:rgb(144, 175, 76);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        button.print-btn {
            width: 100%;
            background-color: #008CBA;
        }

        /* زر الحذف */
        .delete-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Simple PHP Project</h1>

        <!-- Input Form -->
        <h2>Add Record</h2>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone"><br>

            <button type="submit" name="add">Add</button>
        </form>

        <!-- Display Records -->
        <h2>Records</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn">Delete</a>
                         <!-- زر التعديل -->
    <a href="edit.php?id=<?php echo $row['id']; ?>" class="edit-btn" style="background-color: #ffa500; color: white; padding: 5px 10px; margin-left: 5px; border-radius: 4px; text-decoration: none;">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <!-- شريط الصفحات -->
<div style="text-align:center; margin-top: 20px;">
    <?php if ($total_pages > 1): ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" style="margin:0 5px; text-decoration:none; padding:5px 10px;
                background-color: <?php echo $i == $page ? '#6a5acd' : '#ddd'; ?>;
                color: <?php echo $i == $page ? '#fff' : '#000'; ?>;
                border-radius: 5px;">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    <?php endif; ?>
</div>


        <a href="export_excel.php">
<button style="background-color: #28a745; margin-top: 10px;">تصدير Excel</button>
</a>

<a href="export_print_pdf.php" target="_blank">
    <button style="background-color: #00796b;">تصدير PDF</button>
</a>


        <!-- Print Button -->
        <button class="print-btn" onclick="window.print()">Print</button>

 


        <!-- View as Cards Button -->
<a href="cards.php">
    <button style="background-color: #6a5acd; margin-top: 10px;">View as Cards</button>
</a>
    </div>
</body>
</html>