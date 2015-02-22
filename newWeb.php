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
            <div class="weblevels">
              <ul class="">
                <li class="level1" onclick="changeLevel(1)"> <h5>Nivel 1</h5> <span id="ql1"> Vacio</span></li>
                <li class="level2" onclick="changeLevel(2)"> <h5>Nivel 2</h5> <span id="ql2"> Vacio</span></li>
                <li class="level3" onclick="changeLevel(3)"> <h5>Nivel 3</h5> <span id="ql3"> Vacio</span></li>
                <li class="addLevel" onclick="addLevel(3)"> <h5> + </h5> <span> Nuevo</span> </li>
              </ul>
            </div>

            <div id="questionFilter" class="questionFilter level1">

              <div id="filter">
                <div class="component">
                  Tema
                  <select id="topic" name="topic"> 
                    <option value="1">1</option> 
                    <option value="2">2</option> 
                  </select> 
                </div>

                <div class="component">
                  Dificultad
                  <select id="difficulty" name="difficulty"> 
                    <option value="1">1</option> 
                    <option value="2">2</option> 
                  </select> 
                </div>

                <a href="#" onclick="filterQuestions()" class="button round tiny right">Get</a>
              </div>

              <table class="results"> 
               <thead> 
                 <tr> 
                  <th width="700">Texto Pregunta</th> 
                  <th width="80">Agregar</th> 
                </tr> 
              </thead>

              <tbody> 

              </tbody>
            </table>

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

  var currentLevel = 1;
  var questionsForLevel = [[],[],[]];
  var usedQuestions = [];
  var questionFilter = $("#questionFilter");


  function changeLevel(level){
    questionFilter.removeClass("level1");
    questionFilter.removeClass("level2");
    questionFilter.removeClass("level3");

    questionFilter.addClass("level"+level);
    currentLevel = level;
  }

  function addQuestion(id){
    //if($.inArray(id, usedQuestions) == -1){
      questionsForLevel[currentLevel-1].push(id);
      usedQuestions.push(id);  
    //}
    refreshLi();
  }

  function refreshLi(){
    var t = "";

    t = questionsForLevel[0].join(",");
    t = t == "" ? "Vacio" : t;
    $("#ql1").text(t);    
    
    t = questionsForLevel[1].join(",");
    t = t == "" ? "Vacio" : t;
    $("#ql2").text(t);

    t = questionsForLevel[2].join(",");
    t = t == "" ? "Vacio" : t;
    $("#ql3").text(t);

  }


  function filterQuestions(){

    var topic  = $("#topic").val();
    var difficulty  = $("#difficulty").val();
    var template =  "<tr id='id'> <td> $text </td><td> <a onclick='addQuestion($id);' class='tiny button secondary'>Agregar</a> </td> </tr>";

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
        var i;
        var tbody = $("table tbody");
        for(i=0; i<data.length; i++){
          var t = template;
          console.log(t);
          t = t.replace("$text", data[i].text);
          t = t.replace("$id", data[i].id);
          tbody.append(t);

        }
      }

    });
  }

</script>
</body>
</html>
