<!-- Сообщаем браузеру как стоит обрабатывать эту страницу -->
<!DOCTYPE html>
<!-- Оболочка документа, указываем язык содержимого -->
<html lang="ru">
	<!-- Заголовок страницы, контейнер для других важных данных (не отображается) -->
	<head>
		<!-- Заголовок страницы в браузере -->
		<title>TestVK_login</title>
		<!-- Подключаем CSS -->
		<link rel="stylesheet" href="css/style_registrationPage.css" />
		<!-- Кодировка страницы -->
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	</head>
	<!-- Отображаемое тело страницы -->

<body>

	<h1>Добро пожаловать на сайт <span>TestVK</span></h1>
<!--# вместо нее адрес сервера-->
<div class="fform">
	<form id="form" class="form_body">
        <div>
            <label for="login">Логин:</label>
            <input id="formName" type="text" name="login" value="" class="form_input _required _login">
        </div>
<!--Добавление в поле class ключевого слова  _required для файла js, проверка на непустоту-->
        <div>
            <label for="password">Пароль:</label>
            <input id="formPassword" type="password" name="namepass" value="" class="form_input _required _password">
        </div>
		  <!-- Элемент для переключения между видимостью пароля <input type="checkbox" onclick="myPass()">Показать пароль-->

        <div>
            <label for="mail">Email:</label>
            <input id="formEmail"type="text" name="mail" value="" class="form_input _required _email">
        </div>

        <div class="container">
            <input type="checkbox" name="remember"> <span>Запомнить меня</span>
        </div>

        <div class="button">
            <p>Действия:</p>
            <button type="submit" name="submit" class="submit" >Отправить</button>
            <button type="reset" name="reset" class="reset">Очистить</button>
        </div>
    </form>
</div>
<script src="js/formHandler.js"></script>

</body>
</html>

