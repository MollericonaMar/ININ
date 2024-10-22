<?php

namespace Models;

use Config\Database;

class Recoger_has_product
{
  private $id;
  private $recoger_id;
  private $productos_id;
  private $unidades;
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
  function getRecogerId()
  {
    return $this->recoger_id;
  }

  function setRecogerId($recoger_id)
  {
    $this->recoger_id = $recoger_id;
  }

  function getProductosId()
  {
    return $this->productos_id;
  }

  function setProductosId($productos_id)
  {
    $this->productos_id = $productos_id;
  }

  function getUnidades()
  {
    return $this->unidades;
  }

  function setUnidades($unidades)
  {
    $this->unidades = $unidades;
  }

  public function save()
  {
    $sql = "INSERT INTO recoger_has_productos (recoger_id, productos_id,  unidades) VALUES (?, ?, ?);";
    try {
      $stmt = $this->database->prepare($sql);
      $recoger_id = $this->getRecogerId();
      $productos_id = $this->getProductosId();
      $unidades = $this->getUnidades();
      $stmt->bind_param("iii", $recoger_id, $productos_id, $unidades);
      $stmt->execute();
      $stmt->close();
      return true;
    } catch (\Throwable $th) {
      return false;
    }
  }
 
  
}