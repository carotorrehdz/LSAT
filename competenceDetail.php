<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

$c = new Competence();
$competenceId =  Input::get("competence");

if ($competenceId != ''){
  $competence = $c->getCompetence($competenceId);

  if ($competence == null) {
    Redirect::to('competences.php');
  }
}else{
  Redirect::to('competences.php');
}

$websInCompetence = $c->getWebsInCompetence($competenceId);

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Detalle de Competencia</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">

    <?php include 'includes/templates/teacherSidebar.php' ?>
      <div class="large-9 medium-8 columns">

        <h3>
          <?php echo $competence->name; ?>
        </h3>

        <table>
         <thead>
           <tr>
             <th width="300">Red</th>
             <th width="300">Ponderar</th>
           </tr>
         </thead>

         <tbody>
           <?php
           foreach ($websInCompetence as $web) {

              echo "<tr id='$web->id'>
                    <td> $web->name </td>
                    <td> <a href=\"gradingWeb.php?web=$web->webId\" class='tiny button secondary'>Ponderar</a> </td>
                    </tr>";
         }

         ?>

       </tbody>
     </table>
     <a href="#" onclick="publishCompetence()" class="button round small right alerta">Publicar</a>
     </div>
   </div>
 </section>

<?php include 'includes/templates/footer.php' ?>


<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
  $(document).foundation();

  function publishCompetence(){
    
  }

</script>
</body>
</html>
