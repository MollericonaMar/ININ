<?php
namespace Models;

use Config\Database;

class Order
{
  private $id;
  private $user_id;
  private $cellphone;
    private $direction;
  private $price;
  private $state;
  private $database;

  public function __construct()
  {
    $this->database = Database::getInstance()->get_database_instance();
  }

  function getId()
  {
    return $this->id;
  }

  function setId($id)
  {
    $this->id = $id;
  }

  function getUserId()
  {
    return $this->user_id;
  }

  function setUserId($user_id)
  {
    $this->user_id = $user_id;
  }

  function getCellphone()
{
    return $this->cellphone;
}


function setCellphone($cellphone)
{
    $this->cellphone = $this->database->real_escape_string($cellphone);
}


  
  function getDirection()
  {
    return $this->direction;
  }

  function setDirection($direction)
  {
    $this->direction = $this->database->real_escape_string($direction);
  }

  function getPrice()
  {
    return $this->price;
  }

  function setPrice($price)
  {
    $this->price = $price;
  }

  function getState()
  {
    return $this->state;
  }

  function setState($state)
  {
    $this->state = $this->database->real_escape_string($state);
  }

  public function getAll()
  {
    $sql = "SELECT * FROM pedidos ORDER BY id ASC";
    $result = $this->database->query($sql);
    return $result;
  }

  public function getOne()
  {
    try {
      $sql = "SELECT * FROM pedidos WHERE id = {$this->getId()}";
      $result = $this->database->query($sql);
      return $result->fetch_object();
    } catch (\Throwable $th) {
      return false;
    }
  }

  public function getOneByUserId()
  {
    try {
      $sql = "SELECT * FROM pedidos WHERE usuario_id = {$this->getUserId()} ORDER BY id DESC LIMIT 1";
      $result = $this->database->query($sql);
      return $result->fetch_object();
    } catch (\Throwable $th) {
      return false;
    }
  }

  public function getAllByUserId()
  {
    try {
      $sql = "SELECT * FROM pedidos WHERE usuario_id = {$this->getUserId()} ORDER BY id ASC";
      $result = $this->database->query($sql);
      return $result;
    } catch (\Throwable $th) {
      return false;
    }
  }

  public function getProducts()
  {
    $sql = "SELECT pr.id, pp.unidades, pr.precio, pr.nombre, pr.imagen " .
      "FROM pedidos_has_productos pp INNER JOIN pedidos p INNER JOIN productos pr " .
      "ON p.id = pp.pedido_id AND pr.id = pp.producto_id AND p.id=? ORDER BY p.id ASC;";
    try {
      $stmt = $this->database->prepare($sql);
      $order_id = $this->getId();
      $stmt->bind_param("i", $order_id);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      return $result;
    } catch (\Throwable $th) {
      return false;
    }
  }

  public function save()
  {
    $sql = "INSERT INTO pedidos (usuario_id, celular, direccion, coste, estado, fecha, hora) 
        VALUES (?, ?, ?, ?, 'pendiente', CURDATE(), CURTIME());";
    try {
      $stmt = $this->database->prepare($sql);
      $user_id = $this->getUserId();        // Debe ser un número entero (int)
      $cellphone = $this->getCellphone();    // Debe ser un número entero (int)
      $direction = $this->getDirection();    // Cadena de texto (string)
      $price = $this->getPrice();            // Valor decimal (float)
      $stmt->bind_param("iisd", $user_id, $cellphone, $direction, $price);
      $stmt->execute();
      $stmt->close();


      $this->setId($this->database->insert_id);
      return true;
    } catch (\Throwable $th) {
      return false;
    }
  }

  public function changeState()
  {
    $sql = "UPDATE pedidos SET estado = ? WHERE id = ?";
    try {
      $stmt = $this->database->prepare($sql);
      $state = $this->getState();
      $id = $this->getId();
      $stmt->bind_param("si", $state, $id);
      $stmt->execute();
      $stmt->close();
      return true;
    } catch (\Throwable $th) {
      return false;
    }
  }
}
