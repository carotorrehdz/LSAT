<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

$teacherId = $user->data()->id;
$groups = new Groups();
$teacherGroups = $groups->getGroupsForTeacher($teacherId);
$competence = new Competence();
$teacherCompetences = $competence->getCompetencesForTeacher($teacherId);
$groupCompetences = $competence->getCompetencesByGroupOfTeacher($teacherId);

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
          echo "<div class='assignCompetence'><h6 class='panel-title'>Grupo $group->name</h6> <div class='body'><ul>";
            foreach ($groupCompetences as $competence) {
              if ($competence->groupId == $group->id){
                echo "<li>$competence->name <input type='checkbox'> </li>";
              }

            }
            echo "</ul><a href='#' onclick='showAvailableCompetences($group->id)'>+</a></div></div>";
          }
          ?>


          <div  class="availableCompetences reveal-modal small" id="acModal" style="display: none" data-reveal>
            <h3>Competencias disponibles</h3>
            <ul>
              <?php
                foreach ($teacherCompetences as $competence) {
                  echo "<li>$competence->name <a href='#' onclick='addCompetence($competence->id)'>+</a> </li>";
                }
              ?>
            </ul>
          </div>

        </div>
      </div>
    </section>


    <?php include 'includes/templates/footer.php' ?>


    <script src="js/vendor/jquery.js"></script>
    <script src="js/foundation.min.js"></script>
    <script>
      $(document).foundation();

      var currentGroup = 0;

      function showAvailableCompetences(groupId) {
        currentGroup = groupId;

        $('#acModal').foundation('reveal', 'open');
        //WAA Filtrar lis de competencias ya usadas
      }

      function addCompetence(competenceId){
        $.post( "controls/doAction.php", {  action: "addCompetenceToGroup", competenceId: competenceId, groupId: currentGroup})
        .done(function( data ) {
          data = JSON.parse(data);
          if(data.message == 'error'){
            alert("Error: \n\n" + data.message);
          }else{
            window.location.reload();
          }

        });
      }

    </script>
  </body>
  </html>
