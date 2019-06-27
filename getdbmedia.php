<?php
// array holding allowed Origin domains
$allowedOrigins = array(
    '(http(s)://)?(www\.)?my\-domain\.com'
);

if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] != '') {
    foreach ($allowedOrigins as $allowedOrigin) {
        if (preg_match('#' . $allowedOrigin . '#', $_SERVER['HTTP_ORIGIN'])) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            header('Access-Control-Max-Age: 1000');
            header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
            break;
        }
    }
}

//print_r(json_encode(array('saved' => 0, 'reason' => 'file missing')));
$data = [];
$conn = mysqli_connect('127.0.0.1', 'root', '', 'mediadap');
$query = "SELECT * FROM `media`";
//$result = mysqli_query($conn, $query);
//while ($row = mysqli_fetch_array($result)) {
//    array_push($data, $row);
//}
//$row = $result->fetch_assoc();


$result = mysqli_query($conn, $query);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    array_push($data, (int)$row['id']);
}

print_r(json_encode($data));
?>