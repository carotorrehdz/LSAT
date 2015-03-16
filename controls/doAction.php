<?php
require '../core/init.php';

if(Input::exists()) {

	$action = Input::get('action');

	switch ($action) {

		case "updateSettings":
		$username = Input::get('username');
		$mail     = Input::get('mail');
		$password = trim(Input::get('password'));
		$salt = Hash::salt(32);
		$user = new User();

		try {
			if(strlen($password) != 0 ){
				$user->update(array(
					'username'  => $username,
					'mail'      => $mail,
					'password' 	=> Hash::make($password, $salt),
					'salt'		=> $salt,
					), $user->data()->id);
			}
			else{
				$user->update(array(
					'username'  => $username,
					'mail'      => $mail
					), $user->data()->id);
			}

		} catch(Exception $e) {
			$response = array( "message" => "Error:003 ".$e->getMessage());
			die(json_encode($response));
		}

		$response = array( "message" => "success");
		echo json_encode($response);
		break;

		case "registerTeacher":
		$user = new User();
		$salt = Hash::salt(32);

		$username = Input::get('username');
		$mail     = Input::get('mail');
		$idnumber = Input::get('idnumber');

		try {

			$user->create(array(
				'mail' 	=> $mail,
				'password' 	=> Hash::make("123", $salt),
				'salt'		=> $salt,
				'username'  => $username,
				'idnumber'  => $idnumber,
				'role'      =>'teacher'
				));

		} catch(Exception $e) {
			$response = array( "message" => "Error:004 ".$e->getMessage());
			die(json_encode($response));
		}
		$response = array( "message" => "success");
		echo json_encode($response);
		break;

		case "createGroup":

		//Necesitamos la matricula del profesor, que es el usuario logueado
		$user = new User();
		$teacherId = $user->data()->id;
		$groupname = Input::get('groupname');
		$students  = Input::get('students');

		try {

			$db = DB::getInstance();

			// Crear el nuevo grupo
			$group = new Groups();
			if($group->getGroupByName($groupname)){
				$response = array( "message" => "El grupo ya existe");
				echo json_encode($response);
				return;
			}

			$group->create(array(
				'professor' => intval($teacherId),
				'name'  => $groupname,
				'term'  => '1'
				));

			// Obtener el id que se le asigno en la BD
			$groupId = $group->getGroupByName($groupname)->id;

			//Crear cada estudiante
			$studentIds = explode(',', $students);
			foreach ($studentIds as $idnumber){
				/*Debemos de crear una nueva cuenta para cada alumno y asignarle el nuevo grupo
				pero si el alumno ya existe solo le asignamos el grupo*/
				$studentId = 0;
				$student = $user->getByIdNumber($idnumber);
				if($student == false){
					$salt = Hash::salt(32);
					$mail = $idnumber . "@itesm.mx";
					$username = "Estudiante - " . $idnumber;

					$user->create(array(
						'mail' 	=> $mail,
						'password' 	=> Hash::make("123", $salt),
						'salt'		=> $salt,
						'username'  => $username,
						'idnumber'  => $idnumber,
						'role'      =>'student'
						));

					$studentId = $user->getByIdNumber($idnumber)->id;

				}else{
					$studentId = $student->id;
				}

				//studentsingroup - groupId studentId
				$fields = array(
					'groupId' 	=> intval($groupId),
					'studentId' => intval($studentId),
					'active' => 1);
				if(!$db->insert('studentsingroup', $fields)) {
					throw new Exception('There was a problem assigning the student to the group.');
				}

			}

		} catch(Exception $e) {
			$response = array( "message" => "Error:005 ".$e->getMessage());
			die(json_encode($response));
		}
		$response = array( "message" => "success");
		echo json_encode($response);
		break;


		case "createQuestion":

		$user = new User();
		if($user->data()->role != 'teacher'){
			return; /*Solo un maestro puede crear preguntas*/
		}

		//Necesitamos el id del profesor, que es el usuario logueado para ligar las preguntas con el
		$teacherId = $user->data()->id;

		try {
			//Esto lo usamos para convertir el objeto que le mandamos con toda la informacion
            //De esta forma nos queda como un hash map en el que podemos accesar a todos los valores facilmente
			$data = json_decode(stripslashes($_POST['data']),true);

			//Datos de la pregunta
			$text = $data['text'];
			$url = $data['url'];
			$grade = $data['grade'];
			$topic = $data['topic'];

			$db = DB::getInstance();

			$options = array(4);

			//Crear las 4 respuestas
			$ans = new Answer();
			for ($i = 1; $i <= 4; $i++) {

				//Crear la respuesta
				$ans->create(array(
					'text' => $data['ans'.$i],
					'textFeedback' => $data['feed'.$i],
					'urlImage' => $data['urla'.$i],
					'imageFeedback' => $data['urlf'.$i],
    			'correct' => ($i==1)? true : false,  //La primera respuesta siempre sera la correcta
    		));

    			//Obtener el id de la respuesta
				$answerId = intval($db->lastInsertId());
				$options[$i-1] = $answerId;
			}

			// Crear la pregunta
			$question = new Question();
			$question->create(array(
				'professor' => intval($teacherId),
				'topic' => intval($topic),
				'difficulty' => intval($grade),
				'urlImage' => $url,
				'text' => $text,
				'optionA' => $options[0],
				'optionB' => $options[1],
				'optionC' => $options[2],
				'optionD' => $options[3]
				));

			// Obtener el id que se le asigno en la BD
			$questionId = intval($db->lastInsertId());


		} catch(Exception $e) {
			$response = array( "message" => "Error:005 ".$e->getMessage());
			die(json_encode($response));
		}
		$response = array( "message" => "success");
		echo json_encode($response);
		break;

		case "filterQuestions":

		$difficulty = Input::get('difficulty');
		$topic      = Input::get('topic');

		$question = new Question();
		$filteredQuestions = $question->getFilteredQuestions($topic, $difficulty);

		echo json_encode($filteredQuestions);

		break;

		case "getQuestion":

		$id = Input::get('id');
		$response = array();
		$question = new Question();
		$answer = new Answer();

		$q = $question->getQuestion($id);

		$response[] = $q[0]->text;
		$answersIds = array($q[0]->optionA, $q[0]->optionB, $q[0]->optionC, $q[0]->optionD);

		foreach ($answersIds as $item){
			$answersText = $answer->getAnswer($item);
			$response[] = $answersText[0];
		}

		echo json_encode($response);

		break;

		case "createWeb":
		$user = new User();
		if($user->data()->role != 'teacher'){
			return; /*Solo un maestro puede crear preguntas*/
		}

		//Necesitamos el id del profesor, que es el usuario logueado para ligar las preguntas con el
		$teacherId = $user->data()->id;

		try{

			$name = Input::get('name');
			$questionsForLevel = Input::get('questionsForLevel');

			$web = new Web();
			$web->create(array(
				'professor' => intval($teacherId),
				'name' => $name
				));
			$db = DB::getInstance();
			$webId = intval($db->lastInsertId());

			/* Este es el formato en el que nos llegan las preguntas por nivel, el primer indice corresponde al nivel y
				El segundo arreglo son las preguntas
				 0 =>
				    array
				      0 => string '5' (length=1)
				      1 => string '6' (length=1)
				  1 =>
				    array
				      0 => string '7' (length=1)
			*/

		    $currentLevel = 1;
		    foreach ($questionsForLevel as $key => $value) {
		    	//$key es el nivel - 1, debido a que los indices empiezan desde 0, *genius*
		      	//Ahora hay que meter todos estas relaciones de nivel con pregunta a la BD
		      	//var_dump($value);  //Value es el arreglo que contiene las preguntas de ese nivel
		      	foreach ($value as $key => $questionId) {
		      		$web->addQuestionInWeb($questionId, $webId, $currentLevel);
		      	}

		      	$currentLevel += 1;
		      }

			}catch(Exception $e) {
				$response = array( "message" => "Error:006 ".$e->getMessage());
				die(json_encode($response));
			}
			$response = array( "message" => $webId);
			echo json_encode($response);


		break;

		case "createCompetence":
		$user = new User();
		if($user->data()->role != 'teacher'){
			return; /*Solo un maestro puede crear preguntas*/
		}

		//Necesitamos el id del profesor, que es el usuario logueado para ligar las preguntas con el
		$teacherId = $user->data()->id;

		try{

			$name = Input::get('name');
			$webIds = Input::get('webIds');

			$competence = new Comptence();
			$competence->createNewCompetence($name, $teacherId, $webIds);
			
			$db = DB::getInstance();
			$webId = intval($db->lastInsertId());

			/* Este es el formato en el que nos llegan las preguntas por nivel, el primer indice corresponde al nivel y
				El segundo arreglo son las preguntas
				 0 =>
				    array
				      0 => string '5' (length=1)
				      1 => string '6' (length=1)
				  1 =>
				    array
				      0 => string '7' (length=1)
			*/

		    $currentLevel = 1;
		    foreach ($questionsForLevel as $key => $value) {
		    	//$key es el nivel - 1, debido a que los indices empiezan desde 0, *genius*
		      	//Ahora hay que meter todos estas relaciones de nivel con pregunta a la BD
		      	//var_dump($value);  //Value es el arreglo que contiene las preguntas de ese nivel
		      	foreach ($value as $key => $questionId) {
		      		$web->addQuestionInWeb($questionId, $webId, $currentLevel);
		      	}

		      	$currentLevel += 1;
		      }

			}catch(Exception $e) {
				$response = array( "message" => "Error:006 ".$e->getMessage());
				die(json_encode($response));
			}
			$response = array( "message" => $webId);
			echo json_encode($response);


		break;

		default:
		echo "Error: 002";
		break;

	}

}else{
	echo "Error: 001";
}


function objectToArray($d) {
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
	/*
	* Return array converted to object
	* Using __FUNCTION__ (Magic constant)
	* for recursive call
	*/
	return array_map(__FUNCTION__, $d);
}
else {
		// Return array
	return $d;
}
}

?>
