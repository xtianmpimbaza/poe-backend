<?php
//allow cors from frontend request
header("Access-Control-Allow-Origin: http://localhost:3000");
require 'ipfs/IPFS.php';

use Cloutier\PhpIpfsApi\IPFS;

// connect to ipfs daemon API server
$ipfs = new IPFS("127.0.0.1", "8080", "5001");

// connect to database
$conn = mysqli_connect('127.0.0.1', 'root', '', 'mediadap');
?>