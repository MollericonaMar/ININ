<?php

use Helpers\Utils;

if ($recoger) : ?>
  <div class="tabla">
    <h1>Para recoger</h1>
    <table>
      <thead>
        <tr>
          <th>Id</th>
          <th>Nombre</th>
          <th>CI</th>
          <th>Sucursal</th>
          <th>Coste total</th>
          <th>Hora</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $recoger->fetch_object()) : ?>
          <tr>
            <td>
              <a href="../../recoger/show&id=<?= $row->id ?>">
                Recogida #<?= $row->id ?>
              </a>
            </td>
            <td>
              <?= htmlspecialchars($row->nombre) ?>
            </td>
            <td>
              <?= htmlspecialchars($row->ci) ?>
            </td>
            <td>
              <?= htmlspecialchars($row->sucursal) ?>
            </td>
            <td>
              <?= number_format($row->coste, 2, ',', '.') ?> bolivianos
            </td>
            <td>
              <?= $row->hora ?>
            </td>
            <td>
              <?= Utils::getState($row->estado) ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
<?php else : ?>
  <div class="tabla">
    <h1>Para recoger</h1>
    <p class="text-center">No tienes para recoger</p>
  </div>
<?php endif; ?>
