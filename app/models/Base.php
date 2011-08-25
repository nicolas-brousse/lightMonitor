<?php

Namespace Model;

use App;

Abstract Class Base
{
  private static $instance;
  protected $db;

  final public function __construct()
  {
    $app = App::getInstance();
    $this->db = $app['db'];
  }

  public static function getInstance()
  {
    if (!isset(self::$instance)) {
      $className = get_called_class();
      self::$instance = new $className;
    }
    return self::$instance;
  }

  public function getColumns()
  {
    // TODO return list of columns of current table
    return;
  }

  public function findAll()
  {
    return $this->db->fetchAll("SELECT * FROM ".$this->_tablename);
  }

  public function find($id)
  {
    return $this->db->fetchAll("SELECT * FROM ".$this->_tablename." WHERE id = ? ", array($id));
  }

  public function delete($id)
  {
    return $this->db->delete($this->_tablename, array('id' => $id));
  }
}