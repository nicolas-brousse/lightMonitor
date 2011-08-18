<?php

Namespace Controller\Helper;

Class Krypt
{
  private $_key;
  private $_cipher;
  private $_mode;
  private $_iv;

  public function __construct($key, $cipher = MCRYPT_RIJNDAEL_256, $mode = MCRYPT_MODE_ECB)
  {
    $this->_key = $key;
    $this->_cipher = $cipher;
    $this->_mode = $mode;

    $size = mcrypt_get_iv_size($this->_cipher, $this->_mode);
    $this->_iv = mcrypt_create_iv($size, MCRYPT_RAND);
  }

  public function encrypt($data)
  {
    if (is_null($data)) {
      return null;
    }

    return bin2hex(mcrypt_encrypt($this->_cipher, $this->_key, $data, $this->_mode, $this->_iv));
  }

  public function decrypt($data)
  {
    if (is_null($data)) {
      return null;
    }

    return rtrim(mcrypt_decrypt($this->_cipher, $this->_key, $this->_hex2bin($data), $this->_mode, $this->_iv), "\x0");
  }

  private function _hex2bin($str)
  {
    return pack('H*', $str);
  }
}