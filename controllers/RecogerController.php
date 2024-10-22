<?php

namespace Controllers;

use Models\Recoger;
use Models\User;
use Helpers\Utils;
use Models\Recoger_has_product;
use Models\Product;


class RecogerController
{
    public function do()
    {
        Utils::isIdentity();
        require_once "../views/recoger/do.php";
    }

    public function add()
    {
        Utils::isIdentity();
        $count = Utils::statsCart()["count"];
        if (!isset($_POST)) {
            header("Location: ../../recoger/do");
            exit();
        }
        if ($count == 0) {
          $_SESSION["recoger"] = "No hay productos en el carrito";
          header("Location: ../../recoger/do");
          exit();
        }
        $nombre = $_POST["nombre"] ?? false;
        $ci = $_POST["ci"] ?? false;
        $sucursal = $_POST["sucursal"] ?? false;
        $errors = 0;

        if (empty($nombre)) {
            $_SESSION["errors"]["recoger-nombre"] = "Nombre ingresado no es válido.";
            $errors++;
        }

        if (empty($ci) || !preg_match("/^[0-9]{1,20}$/", $ci)) {
            $_SESSION["errors"]["recoger-ci"] = "CI ingresado no es válido.";
            $errors++;
        }

        $sucursales_validas = ["Sucursal Central", "Sucursal Norte", "Sucursal Sur", "Sucursal Este", "Sucursal Oeste", "Sucursal Centro"];

        if (!in_array($sucursal, $sucursales_validas)) {
            $_SESSION["errors"]["recoger-sucursal"] = "Sucursal ingresada no es válida.";
            $errors++;
        }

        if ($errors > 0) {
            header("Location: ../../recoger/do");
            exit();
        }
        if (!isset($_SESSION["cart"])) {
            $_SESSION["recoger"] = "No hay productos en el carrito";
            header("Location: ../../recoger/do");
            exit();
          }
        $cart = $_SESSION["cart"];
        $pasa = false;
        foreach ($cart as  $index => $product) {
          $product_id = $product["product"]->id;
          $units = $product["units"];
    
          $tmp = new Product();
          $tmp->setId($product_id);
          $product = $tmp->getOne();
          if ($units > $product->stock) {
            $_SESSION["recoger"] = "No hay suficiente stock";
            header("Location: ../../recoger/do");
            exit();
          }
          $pasa = true;
        }
        if (!$pasa) {
          $_SESSION["recoger"] = "No hay productos en el carrito";
          header("Location: ../../recoger/do");
          exit();
        }

        $user_id = $_SESSION["user"]["id"];
        $price = Utils::statsCart()["total"]; 
        $estado = "pendiente";
        $recoger = new Recoger();
        $recoger->setUsuarioId($user_id);
        $recoger->setNombre($nombre);
        $recoger->setCi($ci);
        $recoger->setSucursal($sucursal);
        $recoger->setCoste($price);
        $recoger->setEstado($estado);
        $result = $recoger->save();

        if (!$result) {
            $_SESSION["recoger"] = "Recoger fallido";
            header("Location: ../../recoger/do");
            exit();
        }
 
        $recoger_id = $recoger->getId();
    foreach ($cart as  $index => $product) {
      $productos_id = $product["product"]->id;
      $units = $product["units"];
      $actual = new Recoger_has_product();
      $actual->setRecogerId($recoger_id);
      $actual->setProductosId($productos_id);

      $tmp = new Product();
      $tmp->setId($productos_id);
      $product = $tmp->getOne();
      $tmp->setName($product->nombre);
      $tmp->setDescription($product->descripcion);
      $tmp->setPrice($product->$price);
      $stock = $product->stock - $units;
      $tmp->setStock($stock);
      $tmp->setCategoryId($product->categoria_id);
      $tmp->setImage($product->imagen);
      $result = $tmp->update();

      $actual->setUnidades($units);
      $result = $actual->save();
    }


        header("Location: ../../recoger/confirm");
        exit();
    }

    public function confirm()
    {
        Utils::isIdentity();
        $recoger = new Recoger();
        $recoger->setUsuarioId($_SESSION["user"]["id"]);
        $recoger = $recoger->getOneByUserId();
        $temporal = new Recoger();
        $temporal->setId($recoger->id);
        $products = $temporal -> getProducts();
        if (!is_object($recoger) || $recoger == false) {
            header("Location: ../../");
            exit();
        }
        require_once "../views/recoger/confirm.php";
    }

    public function myRecoger()
    {
        Utils::isIdentity();
        $recoger = new Recoger();
        $recoger->setUsuarioId($_SESSION["user"]["id"]);
        $recogers = $recoger->getAllByUserId();
        require_once "../views/recoger/myRecoger.php";
    }

    public function show()
    {
        Utils::isIdentity();
        if (!isset($_GET["id"])) {
            header("Location: ../../");
            exit();
        }
        $recoger = new Recoger();
        $recoger->setId($_GET["id"]);
        $products = $recoger->getProducts();
        $recoger = $recoger->getOne();
        $user = new User();
        $user->setId($recoger->usuario_id);
         $user = $user->getOne();
         if (($_SESSION["user"]["id"] != $recoger->usuario_id && !isset($_SESSION["admin"])) || !is_object($recoger) || $recoger == false || $user == false) {
            header("Location: ../../");
            exit();
          }
        require_once "../views/recoger/show.php";
    }

    public function management()
    {
        Utils::isAdmin();
        $recoger = new Recoger();
        $recogers = $recoger->getAll();
        require_once "../views/recoger/management.php";
    }

    public function state()
    {
        Utils::isAdmin();
        if (!isset($_POST)) {
            header("Location: ../../");
            exit();
        }
        $recoger_id = $_POST["recoger_id"] ?? false;
        $estado = $_POST["estado"] ?? false;
        if (!$recoger_id || !$estado) {
            header("Location: ../../");
            exit();
        }
        $recoger = new Recoger();
        $recoger->setId($recoger_id);
        $recoger->setEstado($estado);
        $result = $recoger->changeState();
        if (!$result) {
            header("Location: ../../");
            exit();
        } else {
            header("Location: ../../recoger/show&id=$recoger_id");
            exit();
        }
    }
}
