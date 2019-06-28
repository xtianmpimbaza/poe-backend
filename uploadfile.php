<?php
include 'connect.php';
if (isset($_FILES["file"]["type"]) && isset($_POST['title'])) {

    $filename = $_FILES["file"]["name"];
    $file_basename = substr($filename, 0, strripos($filename, '.')); // get file name
    $file_ext = substr($filename, strripos($filename, '.')); // get file extention
    $filesize = $_FILES["file"]["size"];
    $title = '' . $_POST['title'];

    $validextensions = array("jpeg", "jpg", "png");
    $temporary = explode(".", $filename);
    $file_extension = end($temporary);


    if ($_FILES["file"]["error"] > 0) {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
    } else {
        $sourcePath = $_FILES['file']['tmp_name']; // Storing source path of the file in a variable

        $image = $_FILES['file']['tmp_name'];
        $fo = fopen($_FILES['file']['tmp_name'], "r");
        $imageContent = fread($fo, filesize($image));

        $metadata = str_replace(" ", "_", $title) . "_stream";

        $test_hash = $ipfs->check($imageContent);

//        if ($ipfs->ls($test_hash) != '1') {
//            print_r(json_encode(array('saved' => 0, 'reason' => 'file exists')));
//        } else {
            $hash = $ipfs->add($imageContent);

            mysqli_query($conn, "INSERT INTO `media` (`id`, `user_id`, `title`) VALUES (NULL, '1', '$title')");
            $id = mysqli_insert_id($conn);
            print_r(json_encode(array('saved' => 1, 'id' => $id, 'user' => 1, 'title' => $title, 'hash' => $hash)));
//        }
    }
} else {
    print_r(json_encode(array('saved' => 0, 'reason' => 'file missing')));
}

?>