<?php if (isset($products) && !empty($products)) : ?>
    <main class="w-full">
        <div class="titulo">
            <h1>Resultados de Búsqueda</h1>
        </div>
        <div class="productos-grid">
            <?php foreach ($products as $product) : ?>
                <div class="producto-card">
                    <div>
                        <img src="../img/uploads/<?= htmlspecialchars($product->imagen) ?>" alt="Imagen del producto <?= htmlspecialchars($product->nombre) ?>">
                    </div>
                    <div>
                        <h2><?= htmlspecialchars($product->nombre) ?></h2>
                        <p class="my-5"><?= htmlspecialchars($product->descripcion) ?></p>
                        <p class="my-5 text-center">
                            <span>Precio: </span><?= number_format($product->precio, 0, ',', '.') ?> bolivianos
                        </p>
                        <?php if ($product->stock > 0) : ?>
                            <a href="../../cart/add&id=<?= $product->id ?>" class="boton text-center">Comprar</a>
                        <?php else : ?>
                            <p class="text-center text-red-500">Agotado</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
<?php else : ?>
    <main class="w-full">
        <div class="titulo">
            <h1>No se encontraron productos</h1>
        </div>
        <p class="text-center">Intenta con otra búsqueda.</p>
        <a href="../../product/index" class="boton">Volver a la lista de productos</a>
    </main>
<?php endif; ?>
