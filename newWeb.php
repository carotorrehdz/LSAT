<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');
$teacherId = $user->data()->id;
?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | Nueva red</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
      <?php include 'includes/templates/teacherSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <h3>Nueva red</h3>
        <h4 class="subheader">Crear una nueva red de aprendizaje</h4>
        <hr>  

        <form id="newWeb"> 

          <div class="row"> 
            <div class="large-12 columns">
              <label>Nombre de la red <input type="text" name="name"/></label>
            </div>
          </div>

          <div class="row"> 
            <div class="large-4 columns">

              <div>
                <h6>Nivel 1</h6>
                <input type="text" name="lbl1"/>
              </div>

              <div>
                <h6>Nivel 2</h6>
                <input type="text" name="lbl1"/>
              </div>


            </div>

            <div class="large-8 columns">
              <h5>Preguntas disponibles</h5>
              <div id="filter">

               <div class="large-4 columns"> 
                <label>Tema
                  <select id="topic" name="topic"> 
                    <option value="1">1</option> 
                    <option value="2">2</option> 
                  </select> 
                </label>
              </div>

              <div class="large-4 columns"> 
                <label>Dificultad
                  <select id="difficulty" name="difficulty"> 
                    <option value="1">1</option> 
                    <option value="2">2</option> 
                  </select> 
                </label>
              </div>

              <a href="#" onclick="filterQuestions()" class="button round tiny right">Get</a>

            </div>
            <div id="container" class="large-8 columns grey1">
              Hello

            </div>
          </div>
        </div>

      </form>

    </div>
  </div>
</section>


<?php include 'includes/templates/footer.php' ?>

<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>

<script>
  $(document).foundation();

  function filterQuestions(){

    var topic  = $("#topic").val();
    var difficulty  = $("#difficulty").val();

    $.post( "controls/doAction.php", {  action: "filterQuestions", 
      topic: topic,
      difficulty: difficulty})

    .done(function( data ) {
      console.log(data);

      data = JSON.parse(data);
      if(data.message == 'error'){
        alert("Error: \n\n" + data.message);
      }else{
        //Llenar el contenedor con las preguntas
        console.log(data);
      }

    });
  }

</script>
</body>
</html>
