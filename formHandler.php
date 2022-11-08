<!DOCTYPE html>
<html>
  
<head>
    <title>Insert Page page</title>
</head>
  
<body>
    <center>
		<?php

			$server = "localhost"; // имя хоста ,если работаем на локальном сервере, то указываем localhost
			$username = "root"; // Имя пользователя БД
			$password = ""; // Пароль пользователя. Если у пользователя нет пароля то, оставляем пустое значение ""
			$database = "users"; // Имя базы данных, которую создали
			$database_table = "vkusers";

			$conn = mysqli_connect($server, $username, $password, $database);
					
			// Check connection
			if($conn === false){
				die("ERROR: Could not connect." 
					. mysqli_connect_error());
			}
				
				// if ( (empty($_POST['login'])) ||  (empty($_POST['namepass'])) || (empty($_POST['mail'])))
				// {
				// 	 $error = "*" . "Please fill all the required fields";
				// 	 echo $error;
				// 	 exit("!");
				// }
				//foreach($_POST as $key=>$value) if(strlen($value)==0) echo"Поле $key - пустое";
				// else
				// {
					$login=$_POST['login'];
					$namepass=$_POST['namepass'];
					$mail=$_POST['mail'];
					$remember="";
					if (isset($_POST['remember'])) {
						$remember="true";} 
					else { $remember="false"; }

					var_dump($login);

					// проверка на существование пользователя с таким же логином 
					//или на уже зарегистрированного пользователя
					$result = mysqli_query($conn,"SELECT login FROM vkusers WHERE login='$login'");
					$myrow = mysqli_fetch_array($result);
					$result1 = mysqli_query($conn,"SELECT email FROM vkusers WHERE email='$mail'");
					$myrow1= mysqli_fetch_array($result1);

					if (!empty($myrow['id'])) {
						$error1 = "Извините, введённый вами логин уже зарегистрирован.
							Введите другие данные или войдите на сайт";
						echo $error1;exit("!");
					}
						if(!empty($myrow1['email'])){

							exit ("Извините, введённая вами почта уже зарегистрирована.
								Введите другие данные или войдите на сайт");
						}

						//длинна обязательных полей от 3 до 20 включительно
						$lenpass = strlen($_POST['namepass']);
						if($lenpass < 3 || $lenpass>20 ){echo "длинна пароля должна быть в пределах: от 3 до 20 символов";exit();}
						$lenlog = strlen($_POST['login']);
						if($lenlog < 3 || $lenlog>20 ){echo "длинна логина должна быть в пределах: от 3 до 20 символов";exit();}
						$lenmail = strlen($_POST['mail']);
						if($lenmail < 3 || $lenmail>20 ){echo "длинна email должна быть в пределах: от 3 до 20 символов";exit();}

					$hashed_password = password_hash($password, PASSWORD_DEFAULT);

					$sql = "INSERT INTO $database_table(login, password, email, remember) VALUES ('$login', 
						'$hashed_password','$mail', '$remember')";

					if(mysqli_query($conn, $sql)){
						echo "<h3>данные успешно сохранены в базе данных.</h3>"; 

						echo nl2br("\n$login\n $hashed_password\n "
							. "$mail\n $remember\n");
					} 
					else{
						echo "ERROR: Hush! $sql. " 
							. mysqli_error($conn);
						}

					// Close connection
					mysqli_close($conn);
				//}
			//}
			header("Location:sayt.php", true);
			exit();
		?>
    </center>
	</body>
  
</html>





