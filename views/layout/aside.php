
<section class="mx-6 flex flex-col md:flex-row md:min-h-[24rem]">
  <?php

  use Helpers\Utils;

  $stats = Utils::statsCart(); ?>
  <!-- Aside -->
  <aside class="w-full md:max-w-md md:w-[20%] ">
    <div id="login">
      <div class=" inline w-64 h-1"></div>
      <?php if (isset($_SESSION["user"]) || isset($_SESSION["google_user"])) : ?>
    <h2 class="w-full text-center text-xl font-medium my-8">
        Bienvenido, 
        <?php 
        // Verificar si el inicio de sesión es con el sistema interno o Google
        if (isset($_SESSION["user"])) {
            echo $_SESSION["user"]["nombre"] . " " . $_SESSION["user"]["apellidos"];
        } elseif (isset($_SESSION["google_user"])) {
            echo $_SESSION["google_user"]["nombre"]; // Mostrar el nombre del perfil de Google
        }
        ?>
    </h2>
<?php else : ?>
    <form class="form" action="../../user/login" method="POST">
        <h2>Ingresa</h2>
        <!-- inicio de sesión con Google -->
        <script src="https://accounts.google.com/gsi/client" async defer></script>

<div id="g_id_onload"
     data-client_id="235345878168-h2h9pom5hn1hnfv5ep28c6mv5at9q01d.apps.googleusercontent.com"
     data-callback="handleCredentialResponse"
     data-auto_prompt="false">
</div>

<div class="g_id_signin"
     data-type="standard"
     data-shape="rectangular"
     data-theme="outline"
     data-text="sign_in_with"
     data-size="large"
     data-logo_alignment="left">
</div>

<script>
  function handleCredentialResponse(response) {
    // El token JWT devuelto por Google
    const token = response.credential;

    // Enviar el token al servidor para la validación e inicio de sesión
    fetch('UserGoogleController.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `token=${token}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Redirigir al dashboard o a la página que desees
        window.location.href = '/dashboard';
      } else {
        console.log('Error al autenticar');
      }
    })
    .catch(error => console.error('Error:', error));
  }
</script>

        <!-- fin inicio de sesión con Google -->
        
        <?php Utils::showError("error-login") ?>
        
        <!-- Inicio de sesión con correo desde la base de datos -->
        <label for="email">Correo electrónico</label>
        <input type="email" name="email" id="email">
        
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password">
        
        <input type="submit" value="Iniciar sesión">
    </form>
<?php endif; ?>

      <ul class="actions">
        <?php if (isset($_SESSION["user"])) : ?>
          <h2 class="font-semibold text-xl mb-5">Carrito de compra</h2>
          <li>
            <a href="../../cart/index">Mi carrito</a>
          </li>
          <li>
            <a href="../../cart/index">Productos (<?= $stats["count"] ?>)</a>
          </li>
          <li>
            <a href="../../cart/index">Total: <?= number_format($stats["total"], 0, ',', '.') ?> bolivianos</a>
          </li>
          <h2 class="font-semibold text-xl my-5">Otras opciones</h2>
          <li>
            <a href="../../order/myOrders">Mis pedidos</a>
          </li>
        <?php else : ?>
          <li>
            <a href="../../user/register">Registrate</a>
          </li>
        <?php endif; ?>
        <?php if (isset($_SESSION["admin"])) : ?>
          <li>
            <a href="../../category/index">Gestionar categorías</a>
          </li>
          <li>
            <a href="../../product/management">Gestionar productos</a>
          </li>
          <li>
            <a href="../../order/management">Gestionar pedidos</a>
          </li>
          <li>
            <a href="../../recoger/management">Para recoger</a>
          </li>
        <?php endif; ?>
        <?php if (isset($_SESSION["user"])) : ?>
          <li>
            <a href="../../user/logout">Cerrar sesión</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
    <?php unset($_SESSION["errors"]["error-login"]) ?>
  </aside>