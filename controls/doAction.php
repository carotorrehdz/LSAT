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
		$teacherId = $user->data()->idNumber;
		$groupname = Input::get('groupname');
		$students  = Input::get('students');

		try {

			/*Crear el nuevo grupo*/
			$group = new Groups();
			$group->create(array(
				'professor' => $teacherId,
				'name'  => $groupname,
				'term'  => ''
				));

			//Crear cada estudiante
			$studentIds = explode(',', $students);
			foreach ($studentIds as $idnumber){
				/*Debemos de crear una nueva cuenta para cada alumno y asignarle el nuevo grupo
				pero si el alumno ya existe solo le asignamos el grupo*/

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

				//studentsingroup - groupId studentId

			}

		} catch(Exception $e) {
			$response = array( "message" => "Error:005 ".$e->getMessage());
			die(json_encode($response));
		}
		$response = array( "message" => "success");
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