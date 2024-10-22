<?php

use Helpers\Utils; ?>
<div class="tabla">
  <h1>Tu pedido para recogida se ha confirmado</h1>
  <!-- <div class="flex justify-center">
    <div class="alerta alerta-exito">Se ha guardado el pedido</div>
  </div> -->
  <p class="px-16 text-center">
    Podrás recoger tu pedido en la sucursal que has seleccionado.
  </p>
  <h3 class="text-xl my-8">Datos del pedido para recoger</h3>

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
    <?php if (!$products) 
    echo "Error: No se pudo obtener los productos.";
       while ($product = $products->fetch_object()) : ?>
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
            <?= number_format($product->$price, 0, ',', '.') ?>
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
  <div class="my-5 flex flex-col items-center text-xl">
    <span>Número del pedido: <?= $recoger->id ?></span>
    <span>Sucursal: <?= $recoger->sucursal ?></span>
    <span>Total a pagar: <?= number_format($recoger->coste, 0, ',', '.')  ?></span>
  </div>
</div>
