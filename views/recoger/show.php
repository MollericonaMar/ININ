<?php

use Helpers\Utils; ?>
<div class="tabla">
  <h1>Recogida #<?= $recoger->id ?></h1>
  <h3 class="text-lg">Datos del usuario</h3>
  <div class="mb-5 flex flex-col items-center">
    <p>Nombre: <?= htmlspecialchars($recoger->nombre) ?></p>
    <p>CI: <?= htmlspecialchars($recoger->ci) ?></p>
    <p>Sucursal: <?= htmlspecialchars($recoger->sucursal) ?></p>
  </div>
  
  <?php if (isset($_SESSION["admin"])) : ?>
    <div class="state mb-2">
      <form action="../../recoger/state" method="POST">
        <input type="hidden" name="recoger_id" value="<?= $recoger->id ?>">
        <label for="state">Cambiar estado</label>
        <?php $states = array("pendiente", "preparation", "ready", "sended") ?>
        <select name="state" id="state">
          <?php foreach ($states as $state) : ?>
            <?php if ($state == $recoger->estado) : ?>
              <option selected value="<?= $state ?>"><?= Utils::getState($state) ?></option>
            <?php else : ?>
              <option value="<?= $state ?>"><?= Utils::getState($state) ?></option>
            <?php endif; ?>
          <?php endforeach; ?>
        </select>
        <input type="submit" value="Cambiar estado">
      </form>
    </div>
  <?php endif; ?>

  <!-- TABLA DE PRODUCTOS -->
  <?php if ($products->num_rows === 0) : ?>
    <p>No hay productos para mostrar.</p>
  <?php else : ?>
    <table>
      <thead>
        <tr>
          <th>Imagen</th>
          <th>Producto</th>
          <th>Precio (bolivianos)</th>
          <th>Cantidad</th>
          <th>Precio total</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($product = $products->fetch_object()) : ?>
          <tr>
            <td>
              <img src="../img/uploads/<?= $product->imagen ?>" alt="<?= $product->nombre ?>" class="w-32">
            </td>
            <td>
              <a href="../../product/show&id=<?= $product->id ?>">
                <?= $product->nombre ?>
              </a>
            </td>
            <td>
              <?= number_format($product->precio, 0, ',', '.') ?>
            </td>
            <td>
              <?= $product->unidades ?>
            </td>
            <td>
              <?= number_format($product->precio * $product->unidades, 0, ',', '.') ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
  <!-- FIN TABLA DE PRODUCTOS -->
  
  <div class="my-5 flex flex-col items-center text-lg">
    <span>Total a pagar: <?= number_format($recoger->coste, 0, ',', '.')  ?></span>
  </div>

  <div class="my-5 flex flex-col items-center text-lg">
    <p>Hora registrada: <?= $recoger->hora ?></p>
    <span>Total a pagar: <?= number_format($recoger->coste, 2, ',', '.')  ?> bolivianos</span>
  </div>
</div>
