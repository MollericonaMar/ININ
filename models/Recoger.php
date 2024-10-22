<?php
namespace Models;

use Config\Database;

class Recoger
{
  private $id;
  private $usuario_id;
  private $nombre;
  private $ci;
  private $sucursal;
  private $coste;
  private $estado;
  private $fecha;
  private $hora;
  private $database;

  public function __construct()
  {
    $this->database = Database::getInstance()->get_database_instance();
  }

  // Getters y Setters
  function getId()
  {
    return $this->id;
  }

  function setId($id)
  {
    $this->id = $id;
  }

  function getUsuarioId()
  {
    return $this->usuario_id;
  }

  function setUsuarioId($usuario_id)
  {
    $this->usuario_id = $usuario_id;
  }

  function getNombre()
  {
    return $this->nombre;
  }

  function setNombre($nombre)
  {
    $this->nombre = $this->database->real_escape_string($nombre);
  }

  function getCi()
  {
    return $this->ci;
  }

  function setCi($ci)
  {
    $this->ci = $this->database->real_escape_string($ci);
  }

  function getSucursal()
  {
    return $this->sucursal;
  }

  function setSucursal($sucursal)
  {
    $this->sucursal = $this->database->real_escape_string($sucursal);
  }

  function getCoste()
  {
    return $this->coste;
  }

  function setCoste($coste)
  {
    $this->coste = $coste;
  }

  function getEstado()
  {
    return $this->estado;
  }

  function setEstado($estado)
  {
    $this->estado = $this->database->real_escape_string($estado);
  }

  function getFecha()
  {
    return $this->fecha;
  }

  function setFecha($fecha)
  {
    $this->fecha = $fecha;
  }

  function getHora()
  {
    return $this->hora;
  }

  function setHora($hora)
  {
    $this->hora = $hora;
  }

  // Métodos para consultas
  public function getAll()
  {
    $sql = "SELECT * FROM recoger ORDER BY id ASC";
    $result = $this->database->query($sql);
    return $result;
  }

  public function getOne()
  {
    try {
      $sql = "SELECT * FROM recoger WHERE id = {$this->getId()}";
      $result = $this->database->query($sql);
      return $result->fetch_object();
    } catch (\Throwable $th) {
      return false;
    }
  }
  public function getOneByUserId()
  {
    try {
      $sql = "SELECT * FROM recoger WHERE usuario_id = {$this->getUsuarioId()} ORDER BY id DESC LIMIT 1";
      $result = $this->database->query($sql);
      return $result->fetch_object();
    } catch (\Throwable $th) {
      return false;
    }
  }

  public function getAllByUserId()
  {
    try {
      $sql = "SELECT * FROM recoger WHERE usuario_id = {$this->getUsuarioId()} ORDER BY id ASC";
      $result = $this->database->query($sql);
      return $result;
    } catch (\Throwable $th) {
      return false;
    }
  }
  public function getProducts()
  {
    $sql = "SELECT pr.id, pp.unidades, pr.precio, pr.nombre, pr.imagen " .
      "FROM recoger_has_productos pp INNER JOIN recoger p INNER JOIN productos pr " .
      "ON p.id = pp.recoger_id AND pr.id = pp.productos_id AND p.id=? ORDER BY p.id ASC;";
      try {
        $stmt = $this->database->prepare($sql);
        $recoger_id = $this->getId();
        $stmt->bind_param("i", $recoger_id);
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
    $sql = "INSERT INTO recoger (usuario_id, nombre, ci, sucursal, coste, estado, fecha, hora) 
        VALUES (?, ?, ?, ?, ?, 'pendiente', CURDATE(), CURTIME());";
    try {
      $stmt = $this->database->prepare($sql);
      $usuario_id = $this->getUsuarioId();
      $nombre = $this->getNombre();
      $ci = $this->getCi();
      $sucursal = $this->getSucursal();
      $coste = $this->getCoste();
      $stmt->bind_param("isssd", $usuario_id, $nombre, $ci, $sucursal, $coste);
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
    $sql = "UPDATE recoger SET estado = ? WHERE id = ?";
    try {
      $stmt = $this->database->prepare($sql);
      $estado = $this->getEstado();
      $id = $this->getId();
      $stmt->bind_param("si", $estado, $id);
      $stmt->execute();
      $stmt->close();
      return true;
    } catch (\Throwable $th) {
      return false;
    }
  }
}
