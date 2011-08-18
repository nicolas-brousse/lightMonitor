<?php
/**
 *
 * Asker Extension
 * @adapter SshPubkey
 *
 * @package Asker Extension
 * @version 1
 * @author Nicolas BROUSSE <pro@nicolas-brousse.fr>
 */

namespace Asker\Adapter;

use Asker\Asker_Adapter_Exception;

Class SshPubkey extends Ssh
{
  private $_connection = false;

  public function init()
  {
    if (!function_exists('ssh2_connect')) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: To use SSH protocol, install PHP extention for SSH (php5-ssh2) !");
    }

    /**
     * Verif configurations
     */
    $config = $this->_config;
    if (!isset($config["host"]) OR empty($config["host"])) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: Precise host !");
    }
    if (!isset($config["login"]) OR empty($config["login"])) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: Precise login !");
    }
    if (!isset($config["pubkey"])) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: Precise public key !");
    }
      if (!isset($config["privkey"])) {
        /**
         * @see Asker\Adapter\Exception
         */
        require_once 'Exception.php';
        throw new Asker_Adapter_Exception("ERROR: Precise private key !");
      }

    /**
     * Generate SSH connection
     */
    $this->_connection = @ssh2_connect($config["host"], !empty($config["port"]) ? $config["port"] : 22);
    if (!$this->_connection) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: SSH Connection to '{$config["host"]}:{$config["port"]}' failed !");
    }
    if (!@ssh2_auth_pubkey_file($this->_connection, $config["login"], $config["pubkey"], $config["privkey"])) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: Authentication failed for {$config["login"]} using pubkey !");
    }
  }
}

/***
TODO For create temp file with pubkey and privkey
$temp = tmpfile();
fwrite($temp, "Écriture dans le fichier temporaire");
fseek($temp, 0);
echo fread($temp, 1024);
fclose($temp); // ceci va effacer le fichier

**/