<?php
namespace Cogipix\CogimixMPDBundle\Services;


class FilenameHasher{

private $salt = '1234';

    public function __construct(){

    }

    public function crypt($filename){
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->salt), $filename, MCRYPT_MODE_CBC, md5(md5($this->salt))));
    }

    public function decrypt($encrypted){
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->salt), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($this->salt))), "\0");
    }

}