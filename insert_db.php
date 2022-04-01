<?php

//Inclue conexão com banco de dados
  include ('conexao.php');


//recupera valores enviados pelo dispositivo na requisiÃ§Ã£o GET
//testa variÃ¡veis ID e PASSWORD se foram enviada por GET

    if (isset($_GET['ID']) and ($_GET['PASSWORD'])){
	
	
	//Evitando SQL Injection em aplicações PHP
	$id=preg_replace('/[^[:alnum:]_]/','',$_GET['ID']);
	$password=preg_replace('/[^[:alnum:]_]/','',$_GET['PASSWORD']);
	
	// consulta tabela de cadastro
    	$query = mysqli_query($con, "SELECT * FROM cadastro WHERE id_ref = '$id' AND password = '$password'") or die (mysqli_error($con));
	    
	    // Se resultado voltar 1 registro recupera demais variÃ¡veis e grava no banco de dados        
    if(mysqli_num_rows ($query) > 0 ){
		
		//recupera fator de correção de chuva tabela de cadastro
		while ($ft=mysqli_fetch_array($query)){
		$ftrain=$ft['ftrain'];
		
		}
	
		//Recupera outras variÃ¡veis 
		$temperatura=((($_GET['tempf'])-32) * (5/9));// conversÃ£o para Celsius
		$rssi=$_GET['rssi'];
		$rainin=(($_GET['rainin'])*25.4); //Converte...
		$humidade=$_GET['humidity'];
        $batteryVoltage=$_GET['batteryVoltage'];//recupera tensão da bateria se existir
	    $batteryCharge=$_GET['batteryCharge'];	//Teste variável de tensão da bateria
		$pressao=(($_GET['baromin'])/0.02953); //Converte para Hpa
		$vento=(($_GET['windspeedmph'])/0.6215); //Converte para Km/h
		$chuva=(($_GET['dailyrainin'])*25.4)*$ftrain; //Multiplica pelo fator de correção da chuva
        $pulverizar=$_GET['spray'];
        $pontoOrvalho=((($_GET['dewptf'])-32) * (5/9)); //Converte para Celsius
        $rajada=(($_GET['windgustmph'])/0.6215); //Converte para Km/h
        $dir=$_GET['winddir'];
			
			//Converte hora utc da estação em local
			$dt = date_create($_GET['dateutc'], timezone_open("UTC"));
			date_timezone_set($dt, timezone_open("America/Porto_Velho"));
			$dataTime=date_format($dt, "Y-m-d H:i:s");
			
			  
	
	
	//Salva informaÃ§Ãµes na tabela de leituras
				$query=mysqli_query($con,"INSERT INTO leituras (id_ref, tempf, humidity, baromin, windspeedmph, dailyrainin, spray, dewptf, windgustmph, winddir, rssi, rainin, dateutc, batteryVoltage, batteryCharge) VALUES ('$id', '$temperatura','$humidade','$pressao','$vento','$chuva','$pulverizar', '$pontoOrvalho','$rajada','$dir','$rssi','$rainin','$dataTime','$batteryVoltage', '$batteryCharge')") or die(mysqli_error($con));
		
		//Fecha conexÃ£o com banco de dados
		mysqli_close($con);
		
		// Código de Resposta Headers 200
		http_response_code(200);
		
					
	  }else {
			//Fecha cone~xao com banco de dados
			mysqli_close($con);
			
			// Código de Resposta Headers 401
			http_response_code(401);
	  }
		
		
		
	}



	

 
	    
	    
	    
   

?>
