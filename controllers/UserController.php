<?php

namespace Controllers;

use Models\User;

class UserController
{
  public function register()
  {
    require_once '../views/user/register.php';
  }

  public function save()
  {
    if (isset($_POST)) {
      $name = $_POST["name"] ?? false;
      $surnames = $_POST["surnames"] ?? false;
      $email = trim($_POST["email2"]) ?? false;
      $password = $_POST["password2"] ?? false;
      $errors = 0;
      if (empty($name) || preg_match("/[0-9]/", $name)) {
        $_SESSION["errors"]["register-name"] = "Nombre ingresado no es valido.";
        $errors++;
      }
      if (empty($surnames) || preg_match("/[0-9]/", $surnames)) {
        $_SESSION["errors"]["register-surnames"] = "Apellidos ingresados no son validos.";
        $errors++;
      }
      if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["errors"]["register-email"] = "Correo ingresado no es valido.";
        $errors++;
      }
      if (empty($password)) {
        $_SESSION["errors"]["register-password"] = "Contraseña ingresada no es valida";
        $errors++;
      }

      if ($errors == 0) {
        $user = new User();
        $user->setName($name);
        $user->setSurnames($surnames);
        $user->setEmail($email);
        $user->setPassword($password);
        $save = $user->save();
        if ($save) {
          $_SESSION['register'] = "complete";
        } else {
          $_SESSION['register'] = "failed";
        }
      }
    } else {
      $_SESSION['register'] = "failed";
    }
    header("Location: ../../user/register");
    exit();
  }

  public function login()
  {
    if (isset($_POST)) {
      $user = new User();
      $user->setEmail($_POST["email"]);
      $user->setPassword($_POST["password"]);
      $result = $user->login();
      if ($result != false) {
        $_SESSION["user"] = $result;
        if ($result["rol"] == "admin") {
          $_SESSION["admin"] = true;
        }
      } else {
        $_SESSION["errors"]["error-login"] = "Identificación fallida";
      }
    }
    header("Location: ../../");
    exit();
  }
  //iniciar sesion con google
  public function loginGoogle()
    {
        if (isset($_POST['id_token'])) {
            $client = new \Google_Client(['client_id' => '235345878168-h2h9pom5hn1hnfv5ep28c6mv5at9q01d.apps.googleusercontent.com']);
            $payload = $client->verifyIdToken($_POST['id_token']);

            if ($payload) {
              $email = $payload['email'];
              $name = $payload['name'];
              $surnames = $payload['family_name'] ?? "";
              $image = $payload['picture'] ?? "";
      
              $user = new User();
              $user->setEmail($email);
              $user->setName($name);
              $user->setSurnames($surnames);
              $user->setImage($image);
      
              $result = $user->loginWithGoogle($payload);
              if($result){
              $_SESSION['user'] = $result;
              $_SESSION['admin'] = ($result["rol"] === "admin");
                   }
             }
          }
          header("Location: ../../");
          exit();
        }
  // fin inicio de sesion con google
  public function logout()
  {
    unset($_SESSION["user"]);
    unset($_SESSION["admin"]);
    unset($_SESSION["cart"]);
    header("Location: ../../");
    exit();
  }
}
