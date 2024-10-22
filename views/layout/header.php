
<style>

</style>
<body class="bg-[url('../img/bg.png')] flex justify-center">
  <div class="container bg-white">
    <!-- Header -->
    <header class="flex justify-center">
      <div id="logo" class="flex items-center">
        <div class="w-24 m-2 h-24 bg-[url('../img/logo.jpg')] bg-contain bg-no-repeat">
        </div>
        <a href="../../" class="font-bold text-2xl">
          Ecofarma
        </a>
      </div>
    </header>
    <!-- Navbar -->
    <nav class="navbar">
      <ul>
        <li>
          <a href="../../">Inicio</a>
        </li>
        <?php

        use Helpers\Utils;

        $categories = Utils::showCategories();
        ?>
        <?php while ($category = $categories->fetch_object()) : ?>
          <li>
            <a href="../../category/show&id=<?= $category->id ?>"><?= $category->nombre ?></a>
          </li>
        <?php endwhile; ?>
      </ul>
      <!-- form  y accion-->
      <form action="../search/show.php" method="get" style="display: flex; justify-content: center; align-items: center; margin: 20px;">
            <input type="text" name="query" placeholder="Buscar productos" required style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
            <button type="submit" style="padding: 10px; background-color: #000; color: white; border: none; border-radius: 4px; cursor: pointer;">Buscar</button>
        </form>
        



    </nav>
    <?php
    
    
    

