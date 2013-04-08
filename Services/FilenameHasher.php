<?php
namespace Cogipix\CogimixMPDBundle\Services;


class FilenameHasher{

    private $secret = '1234';

    public function __construct($secret){
        $this->secret= $secret;
    }

    public function crypt($filename){
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->secret), $filename, MCRYPT_MODE_CBC, md5(md5($this->secret))));
    }

    public function decrypt($encrypted){
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->secret), base64_decode($encrypted), MCRYPT_MODE_CBC, md5(md5($this->secret))), "\0");
    }

}