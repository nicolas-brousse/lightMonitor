<?php

Namespace Controller\Helper;

use App;

Class Krypt extends Base
{
  private $_key;
  private $_cipher;
  private $_mode;
  private $_iv;

  public function init($key=null, $cipher = MCRYPT_RIJNDAEL_256, $mode = MCRYPT_MODE_ECB)
  {
    if ($key == null)
    {
      $configs = App::loadConfigs(APPLICATION_BASE_URI . '/app/configs/', APPLICATION_ENV);
      if (empty($configs->app['app']['passphrase'])) {
        throw new \Exception('ERROR: Key is not precise, and is not found in config file app.yml');
      }
    }
    
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
    return @pack('H*', $str);
  }
}