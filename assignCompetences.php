<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

$teacherId = $user->data()->id;
$groups = new Groups();
$teacherGroups = $groups->getGroupsForTeacher($teacherId);
$competence = new Competence();
$teacherCompetences = $competence->getCompetencesForTeacher($teacherId);

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


        <?php
        foreach ($teacherGroups as $group) {
         echo "
         <div class='assignCompetence'>
         <div class='row grey1'> 
          <div class='large-12 columns'>
            <h5>Grupo $group->name</h5>
            <p>Competencias activas para el grupo</p>";
            foreach ($teacherCompetences as $competence) {
              echo "<label>$competence->name <input type='checkbox'> </label>";
            }
            echo "</div></div></div>";
          }
          ?>
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
