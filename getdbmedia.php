<?php
include 'connect.php';

$data = [];

$query = "SELECT * FROM `media`";

$result = mysqli_query($conn, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    array_push($data, (int)$row['id']);
}

print_r(json_encode($data));
?>