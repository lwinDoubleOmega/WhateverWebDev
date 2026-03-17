<?php
include 'database.php';
$pid=100;
$sql = "SELECT p.*, b.name AS brand_name
    FROM products p
    LEFT JOIN brands b ON p.brand_id = b.id
    WHERE p.type = 'brand' AND p.is_active = 1
";
$products = $conn->query($sql);

if ($products) {
while ($row = $products->fetch_assoc()) {
echo "Product ID: " . $row["id"] . "<br>";
echo "Product Name: " . $row["name"] . "<br>";
echo "Price: $" . $row["price"] . "<br>";
echo "Brand: " . $row["brand_name"] . "<br>";
echo "<br>"; 
}
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>