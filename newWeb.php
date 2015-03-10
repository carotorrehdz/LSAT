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
        <br/>
        <h3>Nueva red</h3>
        <h4 class="subheader">Crear una nueva red de aprendizaje</h4>
        <hr>

        <form id="newWeb">

          <div class="row">
            <label>Nombre de la red <input type="text" name="name"/></label>

            <div id="weblevels" class="weblevels">
              <ul class="">
                <li class="level1" onclick="changeLevel(1)"> <h5>Nivel 1</h5> </li>
                <li class="level2" onclick="changeLevel(2)"> <h5>2</h5> </li>
                <li class="level3" onclick="changeLevel(3)"> <h5>3</h5> </li>
                <li class="addLevel level10" onclick="addLevel()"> <h5> + </h5> </li>
              </ul>
            </div>

            <div id="webStructure" class="webStructure level1">

              <div id="questionFilter" class="questionFilter">

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

                  <a href="#" onclick="filterQuestions()" class="button tiny btn">Get</a>
                </div>

                <div id="questionsForLevel">
                  <div>
                    <h6>Preguntas seleccionadas</h6>
                    <ul>
                    </ul>
                  </div>
                </div>

              </div>

              <div id="searchResults" class="searchResults">

                <div id="noQuestions" class="noQuestions">
                  No has buscado ninguna pregunta
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

                <div id="questionModal" class="reveal-modal small" data-reveal>
                  <div id="questionDetail" class="panel">
                    <!-- Default panel contents -->
                    <h4 id="text"></h4>

                    <!-- Table -->
                    <table class="questionDetails">
                      <thead>
                        <tr>
                          <th width="250">Respuesta</th>
                          <th width="250">Feedback</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>

                  </div>
                  <a class="close-reveal-modal">&#215;</a>
                </div>

              </div>

              <div style="clear: both;"></div>
            </div>
          </div>

        </form>

        <a href="#" onclick="createWeb()" class="button round small right">Crear</a>

      </div>
    </div>
  </section>



  <?php include 'includes/templates/footer.php' ?>

  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>

  <script>
    $(document).foundation();

    var currentLevel = 1;
    var maxLevels = 10;
    var nextLevel = 4;
    var questionsForLevel = [[],[],[],[],[],[],[],[],[],[]]; //Maximo de 10 niveles
    var usedQuestions = [];
    var webStructure = $("#webStructure");
    var weblevels = $("#weblevels ul");
    var addNewLi = $(".addLevel");
    var noQustions = $(".noQuestions");
    var resultsTable = $("table.results");
    var questionsForLevelUl = $("#questionsForLevel ul");
    var questionLiTemplate = "<li> <a class='delete' onclick='deleteQuestion($id)'> X </a> <a class='number' onclick='showQuestion($id)'>$number</a></li>";

    var questionModal = $("#questionModal");
    var qtitle = $("#questionModal #text");

    function addLevel(){
      if(nextLevel > maxLevels) return;
      var li = "<li class='level"+nextLevel
      +"' onclick='changeLevel("+nextLevel+")'> <h5>Nivel "+nextLevel+"</h5></li>";
      addNewLi.before(li);
      nextLevel++;
    }

    function changeLevel(level){
      var i;
      for(var i=1; i<=maxLevels; i++){
        var l = "level"+i;
        webStructure.removeClass(l);
      }

      webStructure.addClass("level"+level);
      currentLevel = level;
      refreshLis();
    }

    function addQuestion(id){
      if($.inArray(id, usedQuestions) == -1){
        //console.log(id);
        questionsForLevel[currentLevel-1].push(id);
        usedQuestions.push(id);
        addQuestionLi(id);
      }
      else{
        alert("La pregunta ya fue usada en otro nivel");
      }
    }

    function addQuestionLi(id){
      var t = questionLiTemplate;
      var li = t.replace('$id', id);
      li = li.replace('$id', id);
      li = li.replace("$number", questionsForLevel[currentLevel-1].length);
      questionsForLevelUl.append(li);
    }

    function refreshLis(){
      var t = questionLiTemplate;
      var len = questionsForLevel[currentLevel-1].length;
      console.log("len" + len);
      questionsForLevelUl.empty();

      for(var i=0; i<len; i++){
        console.log(i);
        var id = questionsForLevel[currentLevel-1][i];
        var li = t.replace('$id', id);
        li = li.replace('$id', id);
        li = li.replace("$number", i+1);
        questionsForLevelUl.append(li);

      }
    }

    function showQuestion(id){
      var template =  "<tr> <td> $text </td><td> $feedback </td> </tr>";
      $.post( "controls/doAction.php", {  action: "getQuestion", id: id})
      .done(function( data ) {

        data = JSON.parse(data);
        if(data.message == 'error'){
          alert("Error: \n\n" + data.message);
        }else{
        //Llenar el contenedor con los datos de la pregunta
        qtitle.html(data[0]);

        var tbody = $("table.questionDetails tbody");
        tbody.empty();

        for(i=1; i<5; i++){
          var t = template;
          t = t.replace("$text", data[i].text);
          t = t.replace("$feedback", data[i].textFeedback);
          tbody.append(t);
        }

        /*qtext.html(data[0]);
        var answers = [];
        answers[0] = data[1].text;
        answers[1] = data[2].text;
        answers[2] = data[3].text;
        answers[3] = data[4].text;*/

        //qanswers.html(answers.join(","));
        $('#questionModal').foundation('reveal', 'open');

        }
      });
    }

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
    }

    function deleteQuestion(id){
      console.log("deleteQuestion"+id);
      var arr = questionsForLevel[currentLevel-1];
      //Le quita al arreglo el id de la pregunta que queremos eliminar
      arr = $.grep(arr, function(value) {
        return value != id;
      });

      questionsForLevel[currentLevel-1] = arr;

      //Eliminarla tambien de la lista de preguntas usadas
      usedQuestions = $.grep(usedQuestions, function(value){
        return value != id;
      });

      console.log(arr);
      console.log(questionsForLevel[currentLevel-1]);

      refreshLis();
    }

    function filterQuestions(){

      var topic  = $("#topic").val();
      var difficulty  = $("#difficulty").val();
      var template =  "<tr id='id'> <td> $text </td><td> <a onclick='addQuestion($id);' class='tiny button secondary'>Agregar</a> </td> </tr>";

      $.post( "controls/doAction.php", {  action: "filterQuestions",
        topic: topic,
        difficulty: difficulty})

      .done(function( data ) {
      //console.log(data);

      data = JSON.parse(data);
      if(data.message == 'error'){
        alert("Error: \n\n" + data.message);
      }else{
        //Llenar el contenedor con las preguntas
        //console.log(data);

        noQustions.hide();
        resultsTable.show();

        var i;
        var tbody = $("table.results tbody");
        tbody.empty();
        for(i=0; i<data.length; i++){
          var t = template;
          //console.log(t);
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
