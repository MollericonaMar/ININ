<div class="tabla">
  <?php

  use Helpers\Utils;
  $today = date('Y-m-d'); // Obtener la fecha actual
  ?>
  <h1>Para farmacia</h1>
  <table>
    <thead>
      <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>CI</th>
        <th>Sucursal</th>
        <th>Hora</th>
        <th>Precio total</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
    <?php if (!$products) 
      echo "Error: No se pudo obtener los productos.";
             while ($product = $products->fetch_object()) : ?>
              <tr>
            <td>
            <a href="../../recoger/show&id=<?= $recoger->id ?>">
              Pedido #<?= $recoger->id ?>
            </a>
            </td>
            <td><?= htmlspecialchars($recoger->nombre) ?></td>
            <td><?= htmlspecialchars($recoger->ci) ?></td>
            <td><?= htmlspecialchars($recoger->sucursal) ?></td>
            <td><?= $recoger->hora ?></td>
            <td><?= number_format($recoger->coste, 2, ',', '.') ?> bolivianos</td>
            <td><?= Utils::getState($recoger->estado) ?></td>
          </tr>
      
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
