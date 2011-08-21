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

Namespace Asker\Adapter;

Use Asker\Asker_Adapter_Exception;

Class SshPubkey extends Ssh
{
  protected $_paramsStructure = array(
    'port' => array('type' => 'integer'),
    'pubkey' => array('type' => 'text'),
    'privkey' => array('type' => 'text'),
    'passphrase' => array('type' => 'password', 'allowEmpty' => true),
  );

  private $_connection = false;
  private $_tmpFiles = array();

  public function init()
  {
    if (!function_exists('ssh2_connect') || !function_exists('ssh2_auth_pubkey_file')) {
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
    if (!@ssh2_auth_pubkey_file($this->_connection, $config["login"], $this->_generateTmpFile($config["pubkey"]), $this->_generateTmpFile($config["privkey"]), $config['passphrase'] ? $config['passphrase'] : null)) {
      /**
       * @see Asker\Adapter\Exception
       */
      require_once 'Exception.php';
      throw new Asker_Adapter_Exception("ERROR: Authentication failed for {$config["login"]} using pubkey !");
    }

    /**
     * Remove temporaries files
     */
    $this->_removeTmpFiles();
  }

  private function _generateTmpFile($data)
  {
    $tmpfile = tempnam(APPLICATION_BASE_URI . "/data/tmp", "ssh");

    $handle = fopen($tmpfile, "w");
    fwrite($handle, $data);
    fclose($handle);

    $this->_tmpFiles[] = $tmpfile;
  }

  private function _removeTmpFiles()
  {
    foreach ($this->_tmpFiles as $file)
    {
      if (is_file($file)) {
        unlink($file);
      }
    }
  }
}
