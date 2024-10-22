<div class="order">
  <h1>Realizar el pedido</h1>

  <?php

  use Helpers\Utils; ?>
  <form action="../../order/add" method="POST">
    <a href="../../cart/index" class="boton w-52 text-center">Ver los productos</a>
    <h3 class="text-xl mb-5">Datos para el envio</h3>
    <?php if (isset($_SESSION["order"])) : ?>
      <div class="alerta alerta-error"><?= $_SESSION["order"] ?></div>
    <?php endif; ?>
    <label for="cellphone">Celular</label>
    <?php Utils::showError("order-cellphone") ?>
    <input type="text" name="cellphone" id="cellphone" required>

    <label for="direction">Dirección</label>
    <?php Utils::showError("order-direction") ?>
    <input type="text" name="direction" id="direction" required>

    <input type="submit" class="my-5 w-64" value="Confirmar pedido">
  </form>
  <?php Utils::deleteSession("errors") ?>
  <?php Utils::deleteSession("order") ?>
</div>