<h1>Resultados de Búsqueda</h1>

<?php if (isset($product) && isset($category) && $category) : ?>
  <main class=" w-full">
    <div class="titulo">
      <h1><?= $product->nombre ?></h1>
    </div>
    <div class="product-show">
      <div>
        <img src="../img/uploads/<?= $product->imagen ?>" alt="Imagen del producto">
      </div>
      <div>
        <p class="my-5"><?= $product->descripcion ?></p>
        <p class="my-5 text-center"><span>Precio: </span><?= number_format($product->precio, 0, ',', '.') ?> bolivianos</p>
        <?php if ($product->stock > 0) : ?>
          <a href="../../cart/add&id=<?= $product->id ?>" class="boton text-center">Comprar</a>
        <?php endif; ?>
      </div>
  </main>
<?php else : ?>
  <?php header("Location: ../../product/404") ?>
<?php endif; ?>
