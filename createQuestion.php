<?php

require 'core/init.php';

$user = new User();
$user->checkIsValidUser('teacher');

?>

<!doctype html>
<html class="no-js" lang="en">
<head>
  <title>LSAT | New Question</title>
  <?php include 'includes/templates/headTags.php' ?>
</head>

<body>

  <?php include 'includes/templates/header.php' ?>

  <section class="scroll-container" role="main">

    <div class="row">
      <?php include 'includes/templates/teacherSidebar.php' ?>  
      <div class="large-9 medium-8 columns">
        <h3>Pregunta</h3>
        <h4 class="subheader">Crear nueva pregunta</h4>
        <hr>  

        <form> 

          <div class="row"> 
            <div class="large-12 columns">
              <label>Texto de la pregunta 
                <textarea placeholder="" id="qtext"></textarea> 
              </label>
            </div>
          </div>

          <div class="row"> 
            <div class="large-12 columns"> 
              <label>Url media
                <input type="text" id="qurl" placeholder="URL de una imagen o video que ayude a explicar la pregunta" /> 
              </label> 
            </div> 
          </div> 

          <div class="row"> 
            <div class="large-6 columns"> 
              <label>Dificultad
                <select id="qgrade"> 
                  <option value="1">Baja</option> 
                  <option value="2">Media</option> 
                  <option value="3">Alta</option> 
                </select> 
              </label>
            </div>

            <div class="large-6 columns"> 
              <label>Tema
                <select id="qtopic"> 
                  <option value="1">1</option> 
                  <option value="2">2</option> 
                </select> 
              </label>
            </div>
          </div> 

          <hr>  

          <h4>Respuestas</h4>

          <div class="row correctAns"> 
            <div class="large-6 columns">
              <label>Respuesta 1 <textarea placeholder=""></textarea> </label>
            </div>

            <div class="large-6 columns">
              <label>Feedback <textarea placeholder=""></textarea> </label>
            </div>

            <div class="large-6 columns">
              <label>URL <input type="text" placeholder="URL de una imagen o video que complemente la respuesta" />  </label>
            </div>

            <div class="large-6 columns">
              <label>URL feedback <input type="text" placeholder="URL de una imagen o video que complemente el feedback" />  </label>
            </div>

          </div>

          <a href="#" onclick="createQuestion()" class="button round small right">Crear</a>

        </form>

      </div>
    </div>
  </section>


  <?php include 'includes/templates/footer.php' ?>


  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>
  <script>
    $(document).foundation();

    function createQuestion(){
      var qtopic  = $("#qtopic").val();
      var qgrade  = $("#qgrade").val();
      var qurl    = $("#qurl").val();
      var qtext   = $("#qtext").val();
      
      $.post( "controls/doAction.php", {  action: "createQuestion", 
                                          qtopic: qtopic,
                                          qgrade: qgrade,
                                          qurl: qurl,
                                          qtext: qtext})
      .done(function( data ) {

        data = JSON.parse(data);
        if(data.message == 'success'){
          alert("La pregunta fue creada");
        }else{
          alert("There was an error: " + data.message);
        }

      });
    }

  </script>
</body>
</html>
