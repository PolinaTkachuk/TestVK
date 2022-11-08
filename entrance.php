

 <!-- Сообщаем браузеру как стоит обрабатывать эту страницу -->
<!DOCTYPE html>
<!-- Оболочка документа, указываем язык содержимого -->
<html lang="ru">
	<!-- Заголовок страницы, контейнер для других важных данных (не отображается) -->
	<head>
		<!-- Заголовок страницы в браузере -->
		<title>TestVK</title>
		<!-- Подключаем CSS -->
		<link rel="stylesheet" href="css/style_entrance.css" />
		<!-- Кодировка страницы -->
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	</head>
	<!-- Отображаемое тело страницы -->

<body>
	<h1>Добро пожаловать на сайт <span>TestVK</span></h1>
	
	<div class="fform">
	<form action="entrance.php" method="post" enctype="multipart/form-data">
		<div>
			<label for="login">Логин:</label>
			<input type="text" name="login" value="">
		</div>

		<div>
			<label for="password">Пароль:</label>
			<input type="password" name="namepass" value="" >
		</div>
		 <p class="login-submit">
		 	<button type="submit" name="submit" class="submit">Войти</button>
		 </p>

		 <!--<p class="forgotpassword"><a href="index.html">Забыл пароль?</a></p>-->
		</form>
	</div>
	<?php
   session_start();//  вся процедура работает на сессиях.
	$server = "localhost"; // имя хоста ,если работаем на локальном сервере, то указываем localhost
	$username = "root"; // Имя пользователя БД
	$password = ""; // Пароль пользователя. Если у пользователя нету пароля то, оставляем пустое значение ""
	$database = "users"; // Имя базы данных, которую создали
	$database_table = "vkusers";

	  //connect с бд
	  $conn = mysqli_connect($server, $username, $password, $database);
					
	  // проверка коннекта
	   if($conn === false){
			die("ERROR: Could not connect." 
			.mysqli_connect_error());
		}
		if (isset($_POST['submit']))
		{
		
		if ( (empty($_POST['login'])) ||  (empty($_POST['namepass'])) )
				{
					 $error = "*" . "Please fill all the required fields";
					 echo $error;
					 exit("!");
				}
		else{

			$login=$_POST['login'];
			$password=$_POST['namepass'];
			$mail=$_POST['mail'];
//если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали
			$login = stripslashes($login);
			$login = htmlspecialchars($login);
			$password = stripslashes($password);
			$password = htmlspecialchars($password);
//удаляем лишние пробелы
			$login = trim($login);
			$password = trim($password);

			$hashed_password = password_hash($password, PASSWORD_DEFAULT);


//извлекаем из базы все данные о пользователе с введенным логином
		$result = mysqli_query($conn,"SELECT * FROM vkusers WHERE login='$login'");
		$myrow = mysqli_fetch_array($result);
		if (empty($myrow['password']))
		{
//если пользователя с введенным логином не существует
			exit ("Извините, введённый вами логин или пароль неверный!");
		}
		else {
//если существует, то сверяем пароли
			if ($myrow['password']=password_verify($_POST['password'],$myrow['password'])) {
//если пароли совпадают, то запускаем пользователю сессию
				$_SESSION['login']=$myrow['login']; 
				$_SESSION['id']=$myrow['id'];
				echo "Вы успешно вошли на сайт!";
				header("Location:sayt.php");exit();
				//<a href='index.php'>Главная страница</a>";
			}
			else {
//если пароли не сошлись
				exit ("Извините, введённый вами логин или пароль неверный!!");
			}
		}

		// закрываем подключение
		mysqli_close($conn);
	}
}
	?>

</body>
</html>