<?php
require_once 'easybitcoin.php';

class Functions
{
    protected $bitcoin;
    protected $username = 'multichainrpc';
    protected $password = 'H9TPbgUMVoJwKjYVqHSaEnGGTEMpTyD65N8RQQZ9WJm1';
    protected $host = 'nj.binusu.com';
    protected $port = '4284';


    public function __construct()
    {
        $this->bitcoin = new Bitcoin($this->username, $this->password, $this->host, $this->port);
    }

    //=============================================================== custom methods
    public function toJson($data)
    {
        return json_encode($data);
    }

    //=============================================================== general methods
    public function getBlockchainParams()
    {
        return $this->bitcoin->getblockchainparams();
    }

    public function getRuntimeParams()
    {
        return $this->bitcoin->getruntimeparams();
    }

    public function setRuntimeParam($param_name, $value)
    {
        return $this->bitcoin->setruntimeparam($param_name, $value);
    }

    public function getInfo()
    {
        return $this->bitcoin->getinfo();
    }

    //=============================================================== managing wallet addresses
    public function getAddresses($verbose)
    {
        return $this->bitcoin->getaddresses($verbose);
    }

    public function getAllAddresses()
    {
        return $this->bitcoin->getaddresses();
    }

    public function getNewAddress()
    {
        return $this->bitcoin->getnewaddress();
    }

    public function getUploadAsset()    //----------- not implemented
    {
        return $this->bitcoin;
    }

    public function grantPermission()  //----------- not implemented
    {
        return $this->bitcoin;
    }

    //=============================================================== streams

//    public function createStream($stream)
//    {
//        return $this->bitcoin->create("stream", $stream, false);
//    }

    public function createStream($streamname)
    {
        return $this->bitcoin->create("stream", $streamname, true);
    }

    public function liststreams()
    {
        return $this->bitcoin->liststreams();
    }

    public function subscribe($idn)
    {
        return $this->bitcoin->subscribe($idn);
    }

    public function listStreamItems($stream)
    {
        return $this->bitcoin->liststreamitems($stream);
    }

    public function listStreamKeyItems($stream, $key)
    {
        return $this->bitcoin->liststreamkeyitems($stream, $key, false, 1);
    }

    public function listStreamItemsByKey($stream, $key)
    {
        return $this->bitcoin->liststreamkeyitems($stream, $key);
    }


    public function publishFrom($stream, $key, $hex)
    {
        return $this->bitcoin->publish($stream, $key, $hex);
//        return $this->bitcoin->publishfrom($pub, $stream, $key, $hex);
    }

    public function hashImage($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

    public function addAssets($address, $asset_name, $custom_fields)
    {
        $quantity = 1;
        $smallest_unit = 1;
        $native_amount = 0;
//        $custom_fields = array('file' => $image, 'stream' => $metadata);
        return $this->bitcoin->issue($address, $asset_name, $quantity, $smallest_unit, $native_amount, $custom_fields);
    }

    public function listAssets()
    {
        return $this->bitcoin->listassets();
    }

    public function listAssetsById($assetid)
    {
        return $this->bitcoin->listassets($assetid);
    }

    //=============================================================== Permissions management

    public function grantFrom($to, $permissions)
    {
        return $this->bitcoin->grant($to, $permissions); //permissions = a string of permissions comma delimited
//        return $this->bitcoin->grantfrom($from, $to, $permissions); //permissions = a string of permissions comma delimited
    }

    public function revokeFrom($permissions)
    {
        return $this->bitcoin->revoke($permissions); //permissions = a string of permissions comma delimited
    }

    public function listPermissions()
    {
        $permissions = 'issue';
        $addresses = $this->bitcoin->listpermissions($permissions);
        return $addresses[0]['address'];
    }

    public function listAddresses()
    {
        $addresses = $this->bitcoin->listaddresses();
        return $addresses[0]['address'];
    }

    public function getInitialAdmin()
    {
//        $permissions = 'mine';
//        $addresses = $this->bitcoin->listpermissions($permissions);
        $addresses = $this->bitcoin->listaddresses();
        return $addresses[0]['address'];
    }

    public function listIssuePermissions()
    {
        $address_list = [];
        $permissions = 'issue';
        $addresses = $this->bitcoin->listpermissions($permissions);
        foreach ($addresses as $item) {
            array_push($address_list, $item['address']);
        }
        return $address_list;
    }

    //------------------------------------- return errors
    public function getErrors()
    {
        return $this->bitcoin->error;
    }

    public function upload($filepath)
    {
//        if (isset($_POST['submit']))
//        {
        $filename = basename($filepath);
//            $filename = $_FILES["file"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // get file name
        $file_ext = substr($filename, strripos($filename, '.')); // get file extention
        $filesize = $_FILES["file"]["size"];
        $allowed_file_types = array('.png', '.jpg', '.pdf', '.jpeg');

        if (in_array($file_ext, $allowed_file_types) && ($filesize < 200000)) {
            // Rename file
            $newfilename = md5($file_basename) . $file_ext;
            if (file_exists("uploads/" . $newfilename)) {
                // file already exists error
                echo "You have already uploaded this file.";
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $newfilename);
                echo "File uploaded successfully.";
            }
        } elseif (empty($file_basename)) {
            // file selection error
            echo "Please select a file to upload.";
        } elseif ($filesize > 200000) {
            // file size error
            echo "The file you are trying to upload is too large.";
        } else {
            // file type error
            echo "Only these file typs are allowed for upload: " . implode(', ', $allowed_file_types);
            unlink($_FILES["file"]["tmp_name"]);
        }
//        }
    }

//======================================================================
    function strToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }
        return strToUpper($hex);
    }

    function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }


}