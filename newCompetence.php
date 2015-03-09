<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');
$teacherId = $user->data()->id;
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Nueva competencia</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
      <?php include 'includes/templates/teacherSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <br/>
        <h3>Nueva competencia</h3>
        <h4 class="subheader">Crear una nueva competencia reuniendo varias redes de aprendizaje</h4>
        <hr>  

        <form id="newWeb"> 

          <div class="row"> 
            <label>Nombre de la competencia<input type="text" name="name"/></label>
          </div>

        </form>

        <a href="#" onclick="createCompetence()" class="button round small right">Crear</a>

      </div>
    </div>
  </section>



  <?php include 'includes/templates/footer.php' ?>

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>

  <script>
    $(document).foundation();

 

    function createWeb(){
      var name = $("input#name").val();

      $.post( "controls/doAction.php", {  action: "createWeb", name: name, questionsForLevel:questionsForLevel})
      .done(function( data ) {

        data = JSON.parse(data);
        if(data.message == 'error'){
          alert("Error: \n\n" + data.message);
        }else{
          //Llenar el contenedor con los datos de la pregunta
          console.log(data);
      }
    });

  </script>
</body>
</html>
