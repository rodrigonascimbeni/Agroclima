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
	    
	    
	
	//recupera usuário e senha do banco de dados
	while ($authorization=mysqli_fetch_array($query)){
		define('ADMIN_LOGIN',$authorization['id_ref']);        //define usuário para autenticação por Headers
		define('ADMIN_PASSWORD',$authorization['password']);   //define senha para autenticação por Headers
		
	}
	    
	    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) 
      || ($_SERVER['PHP_AUTH_USER'] != ADMIN_LOGIN) 
      || ($_SERVER['PHP_AUTH_PW'] != ADMIN_PASSWORD)) { 
    header('HTTP/1.1 401 Unauthorized'); 
    header('WWW-Authenticate: Basic realm="Password For Blog"'); 
    exit("Access Denied: Username and password required."); 
  } 
	    
	    
   
} 
?>
