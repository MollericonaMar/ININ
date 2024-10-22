<?php



namespace Controllers;

class ErrorController
{
  public function index()
  {
    echo "<div class='error'>";
    echo "<p>Error 404</p>";
    echo "<span>La página que buscas no existe</span>";
    echo "</div>";
  }

}
