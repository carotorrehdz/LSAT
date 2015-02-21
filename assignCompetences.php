<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Assign competences</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
      <?php include 'includes/templates/teacherSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <h3>Asignar competencias</h3>
        <h4 class="subheader">Asignar competencias a los grupos</h4>
        <hr>  


        <div class="row grey1"> 
          <div class="large-12 columns">
            <h5>Grupo TC01</h5>
            <p>Competencias activas para el grupo</p>
            <label>Repaso1 <input type="checkbox"> </label>
            <label>Repaso2 <input type="checkbox"> </label>
            <label>Repaso3 <input type="checkbox"> </label>
          </div>
        </div>
        <br/>
        <a href="#" onclick="save()" class="button round small right">Guardar cambios</a>

        
      </div>
    </div>
  </section>


  <?php include 'includes/templates/footer.php' ?>


  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>
  <script>
    $(document).foundation();

  </script>
</body>
</html>
