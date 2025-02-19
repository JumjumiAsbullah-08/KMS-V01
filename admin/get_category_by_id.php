<?php
include '../database/koneksi.php';

$kategoriId = $_GET['id'];
$query = "SELECT * FROM categories WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $kategoriId);
$stmt->execute();
$result = $stmt->get_result();
$category = $result->fetch_assoc();

echo json_encode($category);
?>
