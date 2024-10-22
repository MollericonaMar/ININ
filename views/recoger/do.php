<div class="order">
  <h1>Realizar el pedido para recoger</h1>

  <?php

  use Helpers\Utils; ?>
  <form action="../../recoger/add" method="POST">
    <a href="../../cart/index" class="boton w-52 text-center">Ver los productos</a>
    <h3 class="text-xl mb-5">Datos para recoger</h3>
    <?php if (isset($_SESSION["recoger"])) : ?>
      <div class="alerta alerta-error"><?= $_SESSION["recoger"] ?></div>
    <?php endif; ?>

    <label for="nombre">Nombre</label>
    <?php Utils::showError("recoger-nombre") ?>
    <input type="text" name="nombre" id="nombre" required>

    <label for="ci">Cédula de Identidad (CI)</label>
    <?php Utils::showError("recoger-ci") ?>
    <input type="text" name="ci" id="ci" required>

    <label for="sucursal">Sucursal</label>
    <?php Utils::showError("recoger-sucursal") ?>
    <select name="sucursal" id="sucursal" required>
    <option value="" disabled selected>Selecciona una sucursal</option>
    <option value="Sucursal Central">Sucursal Central</option>
    <option value="Sucursal Norte">Sucursal Norte</option>
    <option value="Sucursal Sur">Sucursal Sur</option>
    <option value="Sucursal Este">Sucursal Este</option>
    <option value="Sucursal Oeste">Sucursal Oeste</option>
    <option value="Sucursal Centro">Sucursal Centro</option>
    </select>


    <input type="submit" class="my-5 w-64" value="Confirmar para recoger">
  </form>

  <?php Utils::deleteSession("errors") ?>
  <?php Utils::deleteSession("recoger") ?>
</div>
