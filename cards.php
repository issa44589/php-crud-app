<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "simple_project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";

$query = "SELECT * FROM records";
if (!empty($search)) {
    $query .= " WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

$result = $conn->query($query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cards View</title>
    <style>
        body {
            background-color: #f7f9fc;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: cover;
        }

        .name {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0 5px;
        }

        .email, .phone, .date {
            color: #555;
            font-size: 14px;
            margin: 3px 0;
        }

        .back-btn {
            display: block;
            margin: 20px auto 0;
            background-color: #444;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Records as Cards</h1>
        
        <!-- شريط البحث -->
        <form method="GET" style="text-align: center; margin-bottom: 30px;">
            <input type="text" name="search" placeholder="ابحث بالاسم أو البريد الإلكتروني"
                   value="<?php echo htmlspecialchars($search); ?>"
                   style="padding: 10px; width: 60%; max-width: 400px; border: 1px solid #ccc; border-radius: 6px;">
            <button type="submit" style="padding: 10px 15px; background-color: #444; color: white; border: none; border-radius: 6px; cursor: pointer;">
                بحث
            </button>
        </form>

        <!-- عرض الكروت -->
        <div class="card-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <img class="avatar" src="https://api.dicebear.com/7.x/initials/svg?seed=<?php echo urlencode($row['name']); ?>" alt="Avatar for <?php echo htmlspecialchars($row['name']); ?>">
                        <div class="name"><?php echo htmlspecialchars($row['name']); ?></div>
                        <div class="email"><?php echo htmlspecialchars($row['email']); ?></div>
                        <div class="phone"><?php echo htmlspecialchars($row['phone']); ?></div>
                        <div class="date"><?php echo $row['created_at']; ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; color:#888;">لا توجد نتائج مطابقة.</p>
            <?php endif; ?>
        </div>

        <!-- زر العودة -->
        <a href="new.php" class="back-btn">Back to Table View</a>
    </div>
</body>
</html>
