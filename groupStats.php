<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');
$teacherId = $user->data()->id;
$groups = new Groups();
$s = new Statistics();
$teacherGroups = $groups->getGroupsForTeacher($teacherId);

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Estadisticas</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
      <?php include 'includes/templates/teacherSidebar.php' ?>  
      <div class="large-9 medium-8 columns stats">
        <h3>Estadisticas</h3>
        <h4 class="subheader">Avance detallado de alumnos por grupo</h4>
        <hr>  


        <?php
        foreach ($teacherGroups as $group) {

          $results = $s->getGroupProgress($group->id);
          $activeCompNumber = $results["activeCompNumber"];
          $studentsProgress = $results["students"];

          echo "<div id='$group->id'> 
          <h5> $group->name </h5>
          <h6> Numero de competencias activas: $activeCompNumber</h6>
          <table> 
           <thead> 
             <tr> 
               <th width='300'>Matricula</th> 
               <th width='300'>Nombre</th> 
               <th width='300'>Competencias</th> 
             </tr> 
           </thead>
           <tbody> ";

          foreach ($studentsProgress as $studentId => $data) {
            $studentInfo = $data[0];
            $competencesDetails = $data[1];

            echo "<tr sid='$studentId'> 
            <td> $studentInfo->idNumber </td>
            <td> $studentInfo->username </td>
            <td class='progress'><ul>";
              foreach ($competencesDetails as $competenceId => $cdata) {
                $status = $cdata[0];
                $competenceName = $cdata[1];
                switch ($status) {
                  case 0:
                  echo "<li id='$competenceId' title='$competenceName' class='notStarted'> $status  </li>";
                  break;
                  case 1:
                  echo "<li id='$competenceId' title='$competenceName' class='started'> $status  </li>";
                  break;
                  case 2:
                  echo "<li id='$competenceId' title='$competenceName' class='finished'> $status  </li>";
                  break;
                  case -1:
                  echo "<li id='$competenceId' title='$competenceName' class='blocked'> $status  </li>";
                  break;
                }

              }

              echo"</ul></td> </tr>";
            }

            echo "</tbody> </table> </div>";

          }
          ?>

        </tbody>
      </table>

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
