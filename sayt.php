<!-- Сообщаем браузеру как стоит обрабатывать эту страницу -->
<!DOCTYPE html>
<!-- Оболочка документа, указываем язык содержимого -->
<html lang="ru">
	<!-- Заголовок страницы, контейнер для других важных данных (не отображается) -->
	<head>
     	 <script src="https://vk.com/js/api/openapi.js?169" type="text/javascript"></script>
		<!-- Заголовок страницы в браузере -->
		<title>TokenVK</title>
		<!-- Подключаем CSS -->
		<link rel="stylesheet" href="css/style_sayt.css" />
		<!-- Кодировка страницы -->
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
      
	</head>
	<!-- Отображаемое тело страницы -->
	<body>
		<center>
	<h1>Добро пожаловать на сайт <span>TestVK</span></h1>
	
	<form action="sayt.php" method="post" enctype="multipart/form-data">
		<div>
			<p>
				<label for="token" class="token" >Введите токен сообщества VK</label>
			</p></br>
			<p><input type="url" placeholder="URL" name="url" class="url" value="" style="text-align: center"></p>
      
    		<p><input type="submit" name="submit" class="button1"></p>
</div>

<?php    
  
      
$token = "53df416c53df416c53df416c7853a52e6a553df53df416c326bb3235b53509714b179d0"; //токен
?> 
<p class="tytle1">Общая информация:</p>
	
<div  id="All_info" class="info">    
<div id="All_info1"  class="info" >
  <?php
      
if (isset($_POST['submit'])) {  //считываем введенный адрес, если пуст-неверная ссылка
	if  (empty($_POST['url'])) {
		$error = "Неправильная ссылка: ";
		echo $error;
      	echo $_POST['url'];
      	exit("!");//не обрабатываем дальше, пользователь не ввел ссылку
	}
	
	else{ //иначе , если ссылку ввели
        $token=$_POST['url'];
        $error = "Введен токен: ";
        echo $error; //выводим данную ссылку на страничку
        echo $_POST['url'];
      	echo ' <br/>';
    }
	 
  	$screen_name = substr($_POST['url'], 15); //что ввел пользователь
  	//используем методы АПИВК 
  	//запрос на получение ссылки
    $VkObject = file_get_contents("https://api.vk.com/method/utils.resolveScreenName?screen_name={$screen_name}&v=5.131&access_token=53df416c53df416c53df416c7853a52e6a553df53df416c326bb3235b53509714b179d0"); 
  	$obj = json_decode($VkObject); //декодируем результат запроса
	$id = $obj->response->object_id; // получаем id группы
  	echo "Короткое имя сообщества: $screen_name <br/>"; //выводим короткое имя
  	echo "ID сообщества: $id <br/><br/>"; //и id
  
	//используем методы АПИВК 
	//получение данных о подписчиках группы
  	$VkMembers = file_get_contents("https://api.vk.com/method/groups.getMembers?group_id={$id}&count=1000&v=5.131&fields=online&access_token=53df416c53df416c53df416c7853a52e6a553df53df416c326bb3235b53509714b179d0");
  	$Members = json_decode($VkMembers); //декодируем 
  	$CountMembers = $Members->response->count; //Общее кол-во участников
	  ?>

	  </div id="All_info1">

	  <div id="All_info2"  class="info" >
		  <?php
  	echo "Колличество участников $CountMembers <br/>";
	
  	$nowOnline = 0;
  	$men = 0;
  	$women = 0;
  	$count = 1;
	$offset = 0;
  	$city =  [[]];
  	
  	while ($count > $offset) {
      
      	$code = urlencode(' 
            var groupId = '.$id.';
            var offset = '.$offset.';

            // API call limit
            var _acl = 23; 													//лимит вызовов api
            var members = API.groups.getMembers({ "group_id" : groupId });  //первый раз вызываем чтобы посмотреть кол-во участников
            _acl = _acl - 1; 												//уменьшаем тк вызвали один раз
            var count = members.count;
            var users = [];

            while( _acl > 1 && offset < count) {
                var _members = API.groups.getMembers({ "group_id" : groupId, "offset" : offset, "fields" : "online, city, sex" }); //вызываем метод передавая все параметры
                users = users + _members.items; //массив результатов
                offset = offset + 1000;
                _acl = _acl - 1;
            }
            var result = { //формируем результат
                count	: count,
                offset	: offset,
                users	: users
            };
            return result;
        ');
      //универсальный метод execute, который позволяет вызывать множество методов за один запрос
        $query = file_get_contents("https://api.vk.com/method/execute?code=".$code."&v=5.131&access_token=0b65f9830b65f9830b65f983620b1e560800b650b65f9836957173073946d3869e9154d");
        $result = json_decode($query);

        $count = $result->response->count; //переписываем кол-во людей
        $offset = $result->response->offset; //переписываем смещение

        for ($i = 0; $i < $result->response->offset; $i++) { //считаем всёёё (онлайн сколько сейчас, пол...)
			if (isset($result->response->users[$i]->online) && ( ($result->response->users[$i]->online)== 1)) $nowOnline++;
			//   if ($result->response->users[$i]->online == 1) $nowOnline++;
         	if (isset($result->response->users[$i]->sex)&& ( ($result->response->users[$i]->sex)== 1))  $women++;
          	if (isset($result->response->users[$i]->sex) && ( ($result->response->users[$i]->sex)== 2)) $men++;

				
				 //блок с городами, Но он не работает. НЕ хочет обрабатывать многомерный массив
				 /*
				if(isset($result->response->users[$i]->city->id)) 
			{	
				if (array_key_exists($result->response->users[$i]->city->id,$city) )
				{
					$city[$result->response->users[$i]->city->id][0]++;
					$city[$result->response->users[$i]->city->id][1] = $result->response->users[$i]->city->title;
				}
			}
			*/ 
            // $city[$result->response->users[$i]->city->id][0]++;
          	//$city[$result->response->users[$i]->city->id][1] = $result->response->users[$i]->city->title;
        }
    
    }
  	
   //сортируем города, где SORT_DESC для сортировки по убыванию, а SORT_REGULAR - обычное сравнение элементов (без изменения типов)
	//array_multisort($city, SORT_DESC, SORT_REGULAR);

	//Топ записей

	// Количество записей, которое нам нужно получить.
		$countPost = 5000;
	// Топ из скольки записей показать
	$count_selection = 5;
	// По какому параметру сортируем
	$sorting = "лайки";
	// ID нашего сообщества или страницы вконтакте
	$wall_id = "-$id";
	// Токен
	$token_bot = "53df416c53df416c53df416c7853a52e6a553df53df416c326bb3235b53509714b179d0";

	// Получаем информацию, подставив все данные выше. Метод АПИ wall.get
	$api = file_get_contents("https://api.vk.com/method/wall.get?owner_id={$wall_id}&count=100&v=5.131&access_token=53df416c53df416c53df416c7853a52e6a553df53df416c326bb3235b53509714b179d0");
	// Преобразуем JSON-строку в массив
	$wall = json_decode($api);
	//print_r ($wall);

	//проверка есть ли доступ к сообществу разным кодам ошибок
	if (isset($wall->error->error_code) == true) {
			echo "$wall->error->error_code <br/><br/><br/>";
		}

	// если все хорошо получаем массив
	$wall = $wall->response->items;
	
	//print_r ($wall);

	if (count($wall) >= 100 && $countPost > 100) { //условие и цикл для получения большего кол-ва записей
		for ($j = 100; $j < $countPost - 1; $j = $j + 100) {
			$api_tmp = file_get_contents("https://api.vk.com/api.php?oauth=1&method=wall.get&owner_id={$wall_id}&count=100&offset={$j}&v=5.131&access_token={$token_bot}");
		$wall_tmp = json_decode($api_tmp);  
			$wall_tmp = $wall_tmp->response->items;

			for ($i = 0; $i < count($wall_tmp); $i++) {
			array_push($wall, $wall_tmp[$i]);
				}
			}
		}

	if ($sorting == "лайки") { //сортировка по параметру лайки, считаем их кол-во
			for ($i = 0; $i < count($wall); $i++) {
				for ($j = 0; $j < count($wall) - 1; $j++) {
					if ($wall[$j]->likes->count < $wall[$j + 1]->likes->count) {
						$tmp = $wall[$j + 1]; // создали дополнительную переменную
						$wall[$j + 1] = $wall[$j]; // меняем местами
						$wall[$j] = $tmp; // значения элементов
					}
				}
			}
		}

	if ($sorting == "комментарии") { //считаем кол-во комментов
			for ($i = 0; $i < count($wall); $i++) {
				for ($j = 0; $j < count($wall) - 1; $j++) {
					if ($wall[$j]->comments->count < $wall[$j + 1]->comments->count) {
						$tmp = $wall[$j + 1]; // создали дополнительную переменную
						$wall[$j + 1] = $wall[$j]; // меняем местами
						$wall[$j] = $tmp; // значения элементов
					}
				}
			}
		}

	
	 echo "Количество участников онлайн $nowOnline <br/>";  
	 echo "Количество мальчиков $men <br/>";
	 echo "Количество девочек $women <br/><br/>"; 
	 ?>
	 </div id="All_info2">
 </div id="All_info">

<div class="tytle2" id="duag">Диаграмма: </div id="duag">
 <?php
 //вывод столбчатой диаграммы полов
	 $content = '<div id="columnchart_material" style="width: 500px; height: 350px;"></div>'; 
	 ?>
	 <div>
	 	 <?php
	 echo $content;
	 ?>
	 </div>
<?php
 /*
	// echo "Большие города: <br/>";
	 //Вывод многомерного массива город+сколько человек
//1 способ
	 if (isset($city)&& (isset($city[$i][0]))) { 							
	 			for ($i = 0; $i < 10; $i++) {
      	echo "{$city[$i][1]} {$city[$i][0]} <br/>"; 
      	 }
      }
//2 способ
      $j=0;
      if (isset($city)&& (isset($city[$i][0]))) { 
      	for ($i = 0; $i < 10; $i++)
      	{
      		 foreach($city as $city[$i] => $massiv)
      	  {
      	  	foreach($massiv  as  $inner_key => $j)
      	  		{
      	  			echo "[$city[$i]][$inner_key] = $value";
      	  		}
      	  		$j=1;
      	  }
      	}						
}

/*
//ВЫвод который ничего не выводит 
$j=0;
foreach($city as $i=>$j)
{
	foreach($j as $keyy=>$val)//вывод название города и сколько человек
	{
		echo"[$i][$key]=$val";
		$key++;
		echo"[$i][$key]=$val";
	}
	//$j=1;
}
*/
/*
	 $content = '<div id="chart_div"></div>'; //выводим js код с диаграммой круговой
  echo $content;
 */
?>
</br>
	 <div class="tytle3">Топ популярных записей в сообществе:</div>
	 <?php
	 for ($i = 1; $i < $count_selection + 1; $i++) { //i-1 для вывода мест и цикл с 1
		 $wall[$i-1]->date = date("d-m-Y H:i:s", $wall[$i-1]->date); //дата

		 echo "<br/>{$i} место https://vk.com/wall-{$id}_{$wall[$i-1]->id}<br/>";
		 echo "Дата (МСК): {$wall[$i-1]->date}<br/>";
		 echo "Количество лайков: {$wall[$i-1]->likes->count}<br/>";
	  echo "Количество комментариев: {$wall[$i-1]->comments->count}<br/><br/>";
	}
 
	//echo "offset $offset <br/>"; 
	 //print_r ($result);
	  
}
	  
  ?>
  
	
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
	  /*
  //круговая диаграмма ГОРОДА
	  
	var jArray = <?php echo json_encode($city ); ?>; //передаем массив городов из php
	
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		 var data = new google.visualization.DataTable();
		 data.addColumn('string', 'Topping');
		 data.addColumn('number', 'Slices');

		 for (var i = 0; i < 10; i++) {
			  data.addRows([
			  [jArray[i][1], jArray[i][0]]
			  ]);
		 }

		 var options = {'title':'Большие городааа',
		 'width':500,
		 'height':350};
		 var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
		 chart.draw(data, options);
	}
		 */
	</script>
	  
	  
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
	  
	//Столбчатая диаграмма ГЕНДЕР
	  
	var jMen = <?php echo json_encode($men ); ?>; //передаем мальчиков из php
	var jWomen = <?php echo json_encode($women ); ?>; //передаем девочек из php$CountMembers
	var jCountMembers = <?php echo json_encode($CountMembers ); ?>;
	  
	google.charts.load('current', {'packages':['bar']});
	google.charts.setOnLoadCallback(drawChart);

	function drawChart() {
		 var data2 = google.visualization.arrayToDataTable([
			['Members', 'All', 'Mens', 'Womens'],
			['Members', jCountMembers, jMen, jWomen],
		 ]);

		 var options2 = {
			  chart: {
			  title: 'Мальчики и девочки',
			  }
		 };

		 var chart2 = new google.charts.Bar(document.getElementById('columnchart_material'));

		 chart2.draw(data2, google.charts.Bar.convertOptions(options2));
	}
	</script>
	  	</center>
      
</body>
</html>

