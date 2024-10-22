<?php
namespace Helpers;

use Models\Category;

class Utils
{
  public static function deleteSession($value)
  {
    unset($_SESSION[$value]);
  }

  public static function showError($error)
  {
    if (isset($_SESSION["errors"][$error])) {
      echo "<div class='alerta alerta-error'>" . $_SESSION["errors"][$error] . "</div>";
    }
  }

  public static function isAdmin()
  {
    if (isset($_SESSION["admin"])) {
      return true;
    }
    header("Location: ../../product/index");
    exit();
  }

  public static function showCategories()
  {
    $category = new Category();
    $categories = $category->getAll();
    return $categories;
  }

  public static function statsCart()
  {
    $stats = array(
      "count" => 0,
      "total" => 0
    );
    if (isset($_SESSION["cart"])) {
      $stats["count"] = count($_SESSION["cart"]);
      foreach ($_SESSION["cart"] as $product) {
        $stats["total"] += $product["price"] * $product["units"];
      }
    }
    return $stats;
  }

  public static function isIdentity()
  {
    if (!isset($_SESSION["user"])) {
      header("Location: ../../product/index");
      exit();
    }
  }

  public static function getState($value)
  {
    $states = array(
      "pendiente" => "Pendiente",
      "preparation" => "En preparación",
      "ready" => "Para enviar",
      "sended" => "Enviado"
    );
    return $states[$value];
  }
}
