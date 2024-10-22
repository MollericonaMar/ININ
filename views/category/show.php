
<?php if (isset($products) && isset($category)) : ?>
  <main class=" w-full">
    <div class="titulo">
      <h1> <?= $category->nombre ?></h1>
    </div>
    <?php if ($products->num_rows == 0) : ?>
      <div class="flex justify-center">
        <div class="alerta alerta-error my-5">No hay productos disponibles en esta categoría</div>
      </div>
    <?php else : ?>
      <div id="grid" class="grid grid-cols-2 lg:grid-cols-3">
        <?php while ($product =  $products->fetch_object()) : ?>
          <div class="product">
            <a href="../../product/show&id=<?= $product->id ?>" class="cursor-pointer hover:scale-95 transition-all">
              <div>
                <img src="../img/uploads/<?= $product->imagen ?>" alt="Imagen del producto">
              </div>
              <h2><?= $product->nombre ?></h2>
              <p>Bs.- <?= number_format($product->precio, 0, ',', '.') ?> </p>
            </a>
            <a href="../../cart/add&id=<?= $product->id ?>" class="boton">Comprar</a>
          </div>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </main>
<?php else : ?>
  <?php header("Location: ../../product/404") ?>
<?php endif; ?>