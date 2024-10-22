<?php


namespace Config;

define("SERVER", "localhost");
define("DATABASE", "ecom");
define("USERNAME", "root");
define("PASSWORD", "moll4503");

class Database
{
  private $connection;
  private static $instance;

  private function __construct()
  {
    $this->make_connection();
  }

  public static function getInstance()
  {
    if (!self::$instance instanceof self)
      self::$instance = new self();
    return self::$instance;
  }

  private function  make_connection()
  {
    $db = new \mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
    if ($db->connect_errno)
      die("Falló la conexión: {$db->connect_error}");

    $setnames = $db->prepare("SET NAMES utf8mb4");
    $setnames->execute();

    $this->connection = $db;
  }

  public function get_database_instance()
  {
    return $this->connection;
  }
}
