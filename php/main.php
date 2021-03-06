<?php
	session_start();
?>  	
<!DOCTYPE html>
<html>
<head>
	<title>Airlife</title>
	<meta charset="UTF-8"> 
	<link rel="shortcut icon" type="image/x-icon" href="../images/icon.png">
	<link rel="stylesheet" type="text/css" href="../css/style.css" media="all">		
	<script src="../javascript/jquery-1.11.3.min.js"></script>	
	<script type="text/javascript">
$(function(){
    var url = window.location.pathname.split("/");
    var menuItems = $('.menu ul li a');
    var activated = false;

    while(url.length) {
        u = url.join("/");
        menuItems.each(function() {
            if(u === this.pathname){
                $(this).parent().addClass('selected');
                activated = true;
            }
        });

        if (activated) {
            break;
        } else {
            url.pop(); // remove "" element
            url.pop();
            url.push(""); // add "" element
        }
    }
});
</script>
	<script type="text/javascript">				
	    function Date_toYMD(d)
		{
		    var year, month, day;
		    year = String(d.getFullYear());
		    month = String(d.getMonth() + 1);
		    if (month.length == 1) {
		        month = "0" + month;
		    }
		    day = String(d.getDate());
		    if (day.length == 1) {
		        day = "0" + day;
		    }
		    return year + "-" + month + "-" + day;
		}
	    function setDate()
	    {		    	
	    	var str = Date_toYMD(new Date());			
	    	document.getElementById("date_from").value=str;	    	
	    }
	</script>
</head>
<body>
<div class="page-wrapper">
	<?php 
		require_once "header.php";	
		require_once "menu.php";
	?>

	<section class="content">
		<div class="middle ">
			<div class="fon ">

	<?php 	
		require_once "functions.php";
		date_default_timezone_set("Europe/Helsinki"); 	

		echo "<div class='popular_flight'>";
		echo "<div><h3>Летишь сегодня - платишь меньше. Самые низкие цены на билеты!<h3></div>";
		
		$flight_date[0] = new DateTime(date("Y-m-d"));								
		$flight_date[0] = $flight_date[0]->format("Y-m-d");

		$flight_date[1] = new DateTime(date("Y-m-d"));
		$flight_date[1]->modify("+7 day");
		$flight_date[1] = $flight_date[1]->format("Y-m-d");

		$query = "SELECT flight_from, flight_to, MIN(IF(schedule.price_economy IS NULL, flights.price_economy, schedule.price_economy)) AS price FROM flights, schedule WHERE schedule.flight_date = '$flight_date[0]' AND schedule.flight_number = flights.flight_number GROUP BY flight_from, flight_to ORDER BY price";
		$result = queryMysql($query);
   		$num    = $result->num_rows;  

		//$result -> data_seek(0);

		for ($j = 0 ; $j < 8 ; ++$j)
		{		
			$row = $result->fetch_array(MYSQLI_ASSOC);      						
			$flight_from[0] = $row['flight_from'];
			$flight_from[1] = $row['flight_to'];
			$flight_to[0] = $row['flight_to'];
			$flight_to[1] = $row['flight_from'];
			$price_economy = $row['price'];

			echo<<<_END
				<div class="green_block">						
					<form method="POST" action="tickets.php">
						<button type="submit" onclick="setDate">
							<div class="green">							
								<h3>$flight_from[0]  &mdash; </h3>
								<h3>$flight_to[0]</h3>
								<p><span>от</span> $price_economy руб.</p>							
							</div>
						</button>											
						<input type="hidden" name="flight_from[0]" value="$flight_from[0]">									
						<input type="hidden" name="flight_to[0]" value="$flight_to[0]">						
						<input type="hidden" name="flight_date[0]" value="$flight_date[0]">
						<input type="hidden" name="flight_date[1]" value="$flight_date[1]">
						<input type="hidden" name="direction" value="1">
						<input type="hidden" name="count_peolpe" value="1">
						<input type="hidden" name="class" value="price_economy">
					</form>
				</div>		
_END;
		}
		echo "</div>";

		echo "<div class='clear'></div>";


		echo "<div class='popular_flight'>";
		echo "<div><h3>Популярные направления полетов<h3></div>";

		$query = "SELECT flight_from, flight_to, MAX(popular), MIN(price_economy) FROM flights GROUP BY flight_from, flight_to ORDER BY MAX(popular) DESC, MIN(price_economy)";
		$result = queryMysql($query);
   		$num    = $result->num_rows;  

		//$result -> data_seek(0);
		$flight_date[0] = new DateTime(date("Y-m-d"));						
		$flight_date[0] = $flight_date[0]->format("Y-m-d");

		$flight_date[1] = new DateTime(date("Y-m-d"));
		$flight_date[1]->modify("+7 day");
		$flight_date[1] = $flight_date[1]->format("Y-m-d");

		for ($j = 0 ; $j < 8 ; ++$j)
		{		
			$row = $result->fetch_array(MYSQLI_ASSOC);      						
			$flight_from[0] = $row['flight_from'];
			$flight_from[1] = $row['flight_to'];
			$flight_to[0] = $row['flight_to'];
			$flight_to[1] = $row['flight_from'];
			$price_economy = $row['MIN(price_economy)'];

			echo<<<_END
				<div class="green_block">						
					<form method="POST" action="tickets.php">
						<button type="submit" onclick="setDate">
							<div class="green">							
								<h3>$flight_from[0]  &mdash; </h3>
								<h3>$flight_to[0]</h3>
								<p><span>от</span> $price_economy руб.</p>							
							</div>
						</button>											
						<input type="hidden" name="flight_from[0]" value="$flight_from[0]">									
						<input type="hidden" name="flight_to[0]" value="$flight_to[0]">						
						<input type="hidden" name="flight_date[0]" value="$flight_date[0]">
						<input type="hidden" name="flight_date[1]" value="$flight_date[1]">
						<input type="hidden" name="direction" value="1">
						<input type="hidden" name="count_peolpe" value="1">
						<input type="hidden" name="class" value="price_economy">
					</form>
				</div>		
_END;
		}
		echo "</div>";

		echo "<div class='clear'></div>";



		echo "<div class='random_flight'>";
		echo "<div><h3>Другие направления полетов<h3></div>";

		$query  = "SELECT DISTINCT flight_from,flight_to FROM flights";
		$result = queryMysql($query);
   		$num    = $result->num_rows;   
		
		$outArray = array(); // хранилище для чисел
		$max = $num-1; // максимальное число
		$min = 0; // минимальное число
		$count =  8;///$num; // количество чисел
		$i = 0; // счетчик
		while($i<$count){
		    $chislo = mt_rand($min, $max); // генерим случайное число
		    if(!in_array($chislo, $outArray)){ // Проверяем уникальность числа.
		        $outArray[$i] = $chislo; // если уникальное, то заисываем его в массив
		        $i++;
		    }
		}
		    	 
		$query = "SELECT DISTINCT flight_from,flight_to, MIN(price_economy) FROM flights GROUP BY flight_from, flight_to";
		$result = queryMysql($query);
   		$num    = $result->num_rows;  

		//$result -> data_seek(0);
		$flight_date[0] = new DateTime(date("Y-m-d"));						
		$flight_date[0] = $flight_date[0]->format("Y-m-d");

		$flight_date[1] = new DateTime(date("Y-m-d"));
		$flight_date[1]->modify("+7 day");
		$flight_date[1] = $flight_date[1]->format("Y-m-d");
		
		for ($j = 0 ; $j < $count ; ++$j)
		{	
			$result -> data_seek($outArray[$j]);	
			$row = $result->fetch_array(MYSQLI_ASSOC);      						
			$flight_from[0] = $row['flight_from'];
			$flight_to[0] = $row['flight_to'];
			$price_economy = $row['MIN(price_economy)'];

			echo<<<_END
				<div class="green_block">						
					<form method="POST" action="tickets.php">
						<button type="submit" onclick="setDate">
							<div class="green">							
								<h3>$flight_from[0]  &mdash; </h3>
								<h3>$flight_to[0]</h3>
								<p><span>от</span> $price_economy руб.</p>							
							</div>
						</button>											
						<input type="hidden" name="flight_from[0]" value="$flight_from[0]">			
						<input type="hidden" name="flight_to[0]" value="$flight_to[0]">
						<input type="hidden" name="flight_date[0]" value="$flight_date[0]">
						<input type="hidden" name="flight_date[1]" value="$flight_date[1]">
						<input type="hidden" name="direction" value="1">
						<input type="hidden" name="count_peolpe" value="1">
						<input type="hidden" name="class" value="economy">
					</form>
				</div>		
_END;
		}
		echo "</div>";
	?>


				<div class="clear"></div>

			</div>
		</div>
	</section>

<div class="page-buffer"></div>
</div>
	<?php 
		require_once "footer.php";
	?>
</body>
</html>