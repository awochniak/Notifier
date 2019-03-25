<?php
	
	include 'functions.php';
	require __DIR__.'/vendor/autoload.php';
	use Kreait\Firebase\Factory;
	use Kreait\Firebase\ServiceAccount;
	
	// połączenie z bazą do kontrolek	
	$connection = connectDB();

	// połączenie z bazą mobilek
	$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/notifier-f4e22-firebase-adminsdk-00co7-f67baecfd5.json');
	$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->withDatabaseUri('https://notifier-f4e22.firebaseio.com/')
    ->create();
	
	//daty do linków z logstasha
	$dateToLogstashCurrent = date("Y.m.d");
	$dateToLogstashWeekAgo = date("Y.m.d",strtotime('-7 days'));

	
	// do kontrolki allegro
 	$urlAllegroVerifyCurrent = "https://kibana.p24.org.pl/elasticsearch/logstash-".$dateToLogstashCurrent."/_search?q=\%2256500\%22%20AND%20feature%20:%20\%22RestTransactionVerification\%22%20AND%20message%20:%20\%22RestTransactionVerification%20input\%22%20AND%20%20@timestamp:[now-1h%20TO%20now]";
	$urlAllegroRegisterCurrent = "https://kibana.p24.org.pl/elasticsearch/logstash-".$dateToLogstashCurrent."/_search?q=\%2256500\%22%20AND%20feature%20:%20\%22RestTransactionRegister\%22%20AND%20message%20:%20\%22RestTransactionRegister%20input\%22%20AND%20%20@timestamp:[now-1h%20TO%20now]";
	$urlAllegroVerifyWeekAgo = "https://kibana.p24.org.pl/elasticsearch/logstash-".$dateToLogstashWeekAgo."/_search?q=\%2256500\%22%20AND%20feature%20:%20\%22RestTransactionVerification\%22%20AND%20message%20:%20\%22RestTransactionVerification%20input\%22%20AND%20%20@timestamp:[now-169h%20TO%20now-168h]";
	$urlAllegroRegisterWeekAgo ="https://kibana.p24.org.pl/elasticsearch/logstash-".$dateToLogstashWeekAgo."/_search?q=\%2256500\%22%20AND%20feature%20:%20\%22RestTransactionRegister\%22%20AND%20message%20:%20\%22RestTransactionRegister%20input\%22%20AND%20%20@timestamp:[now-169h%20TO%20now-168h]";
	$urlBlikExec = "https://kibana.p24.org.pl/elasticsearch/logstash-".$dateToLogstashCurrent."/_search?q=s_blikScriptTime\%22%20AND%20chargeBlikByCode_Rest.i_executionTime%20:%3E1.0%20AND%20%20@timestamp:[now-1h%20TO%20now]";

	// tablica z danymi allegro
	$urls = array();

	$urls[0] = $urlAllegroRegisterCurrent;
	$urls[1] = $urlAllegroVerifyCurrent;
	$urls[2] = $urlAllegroRegisterWeekAgo;
	$urls[3] = $urlAllegroVerifyWeekAgo;
	$urls[4] = $urlBlikExec;

	// do kontrolki DB
	$urlDb = "https://kibana.p24.org.pl/elasticsearch/logstash-".$dateToLogstashCurrent."/_search?q=%22Cannot%20connect%20to%20database%22AND%20%20@timestamp:[now-1h%20TO%20now]";
	
	// tablica z danymi DB
	$urlsDb = array();
	$urlsDb[0] = $urlDb;
	
	//zadania 1 stopnia
	$urlAktywacja='http://mike.zeus/monitor/replication.php';
	$urlRate='https://secure.przelewy24.pl/skrypty/monitor_trn_rate.php';
	$urlKolejki='https://secure.przelewy24.pl/skrypty/rabbit_monitor.php';
	$urlSentinel='brak';
	$urlRESTSOAP='http://192.168.23.55/wsmonit/api.php?time=0';
	$urlTrnSaldo='https://secure.przelewy24.pl/skrypty/monitor_trn_balance.php';
	$urlPaczkiSaldo='https://secure.przelewy24.pl/skrypty/monitor_withdraw_balance.php';
	$urlSecureStatic='https://secure.przelewy24.pl/skrypty/monitor_static.php';
	$urlSentinelStats='brak';
	$urlRedisSesja='https://secure.przelewy24.pl/skrypty/monitor_redis_session.php?param=check';
	$urlSerwery='http://192.168.23.55/wsmonit/api4.php?time=0';
	$url56500='https://secure.przelewy24.pl/skrypty/monitor_merchant_efficiency.php?sp_id=56500';
	$urlRabbitSSL='https://secure.przelewy24.pl/skrypty/monitor_rabbitmq.php?option=ssl';
	$urlRabbitSecure='https://secure.przelewy24.pl/skrypty/monitor_rabbitmq.php?option=secure';
	$url73999='brak';
	$urlJavaMicroService='http://192.168.50.13:8001/webmonitor/control/JavaMicroService';
	$urlOrlenStatus='http://192.168.50.13:8001/webmonitor/control/OrlenStatus';
	
	//zadania 2 stopnia
	$urlZwroty = 'brak';
	$urlFaktury = 'brak';
	$urlZwrotyBlik = 'brak';
	$urlRollback = 'zly endpoint';
	$urlMiejsceNaDysku = 'zly endpoint';
	$urlTrnErr04='https://secure.przelewy24.pl/skrypty/monitor_trn_err04.php';
	$urlRateCC = 'brak';
	$urlSessionStep='https://secure.przelewy24.pl/skrypty/monitor_session_step.php';

	//zadania 3 stopnia
	$urlWyciagi='https://secure.przelewy24.pl/skrypty/bank_statement_monitor.php';

	// tablica do odczytania przez decode JSON
	$toJSON = array(
		'trnSaldo' => $urlTrnSaldo,
		'paczkiSaldo' => $urlPaczkiSaldo,
		'secureStatic' => $urlSecureStatic,
		'redisSesja' => $urlRedisSesja,
		'rabbit-ssl' => $urlRabbitSSL,
		'rabbit-secure' => $urlRabbitSecure,
		'javaMicroService' => $urlJavaMicroService,
		'orlenStatus' => $urlOrlenStatus,
		'trnErr04' => $urlTrnErr04,
		'sessionStep' => $urlSessionStep,
		'wyciągi' => $urlWyciagi
	);
			1551998100 
	// tablica do odczytania przez decode String
	$toString = array(
		'REST&SOAP' => $urlRESTSOAP,
		'serwery' => $urlSerwery,	
		'kolejki' => $urlKolejki,	
	);
	
	// sprawdzenie kontrolek JSON
	foreach($toJSON as $title=>$url){
		
		$result = decodeJSON($url);			
		$querySelect = "SELECT MAX(timestamp) FROM `kontrolki` WHERE name = '".$result[0]."'"; 
		$maxTimestamp = selectDB($querySelect,$connection);
		$timestamp = time()-$maxTimestamp;
	
		if($result != 0){
			$queryInsert = "INSERT INTO `kontrolki` (`id`,`name`, `message`, `timestamp`) 
			VALUES (NULL,'".$result[0]."','".$result[1]."', ".time().")";
			insertDB($queryInsert, $connection);
	
			if(checkFalse($firebase, $title) != 0){	
				retrieveFirebase($title, false, $result[1], $firebase);		
				sendPush($result[0], $result[1]);
			}
			
		} else if ($timestamp>180) {
			retrieveFirebase($title, true, 'brak problemów', $firebase);		
		}
	};
	
	// sprawdzenie kontrolek String
	foreach($toString as $title=>$url){
		
		$result = decodeString($url);
		$querySelect = "SELECT MAX(timestamp) FROM `kontrolki` WHERE name = '".$result[0]."'"; 
		$maxTimestamp = selectDB($querySelect,$connection);
		$timestamp = time()-$maxTimestamp;
	
		if($result != 0){
			$queryInsert = "INSERT INTO `kontrolki` (`id`,`name`, `message`, `timestamp`) 
			VALUES (NULL,'".$result[0]."','".$result[1]."', ".time().")";
			insertDB($queryInsert, $connection);
		
			if(checkFalse($firebase, $title) != 0) {
				retrieveFirebase($title, false, $result[1], $firebase);	
				sendPush($result[0], $result[1]);
			}
			
		} else if($timestamp>180){
			retrieveFirebase($title, true, 'brak problemów', $firebase);		
		}
	};
	
	// podział kontrolki mike.zeus - kontrolki replikacja, zwroty i zwroty blik, można wyjąć więcej
		$dividedEnpoints = divideEnpoint($urlAktywacja);
		$dividedEnpointtoRate = divideEnpointtoRate($urlAktywacja);
		$rateMessage = $dividedEnpointtoRate[4]['message'];
		$rateTitle = $dividedEnpointtoRate[4]['title'];
		$rateStatus = $dividedEnpointtoRate[4]['status'];

	// replikacja
		$result = checkEndpoint($dividedEnpoints,"replikacja");
		$querySelect = "SELECT MAX(timestamp) FROM `kontrolki` WHERE name = '".$dividedEnpoints["replikacja"]["title"]."'"; 
		$maxTimestamp = selectDB($querySelect,$connection);
		$timestamp = time()-$maxTimestamp;
		
	if($result != true){
			$queryInsert = "INSERT INTO `kontrolki` (`id`,`name`, `message`, `timestamp`) 
			VALUES (NULL,'".$dividedEnpoints["replikacja"]["title"]."','".$dividedEnpoints["replikacja"]["message"]."', ".time().")";
			insertDB($queryInsert, $connection);
		
		if(checkFalse($firebase, $dividedEnpoints["replikacja"]["title"]) != 0){
				sendPush($dividedEnpoints["replikacja"]["title"], $dividedEnpoints["replikacja"]["message"]);
				retrieveFirebase($dividedEnpoints["replikacja"]["title"], false, $dividedEnpoints["replikacja"]["message"], $firebase);					
		}
		
	} else if($timestamp>180){
			retrieveFirebase($dividedEnpoints["replikacja"]["title"], true, 'brak problemów', $firebase);		
	}
	
	// zwroty P24
		$result = checkEndpoint($dividedEnpoints,"zwroty (P24)");
		$querySelect = "SELECT MAX(timestamp) FROM `kontrolki` WHERE name = '".$dividedEnpoints["zwroty (P24)"]["title"]."'"; 
		$maxTimestamp = selectDB($querySelect,$connection);
		$timestamp = time()-$maxTimestamp;
		

	if($result != true){
			$queryInsert = "INSERT INTO `kontrolki` (`id`,`name`, `message`, `timestamp`) 
			VALUES (NULL,'".$dividedEnpoints["zwroty (P24)"]["title"]."','".$dividedEnpoints["zwroty (P24)"]["message"]."', ".time().")";
			insertDB($queryInsert, $connection);
			
		if(checkFalse($firebase, $dividedEnpoints["zwroty (P24)"]["title"]) != 0){
				sendPush($dividedEnpoints["zwroty (P24)"]["title"], $dividedEnpoints["zwroty (P24)"]["message"]);
				retrieveFirebase($dividedEnpoints["zwroty (P24)"]["title"], false, $dividedEnpoints["zwroty (P24)"]["message"], $firebase);					
		}	
		
	} else if($timestamp>180){
		retrieveFirebase($dividedEnpoints["zwroty (P24)"]["title"], true, 'brak problemów', $firebase);		
	}
		
	// zwroty blik
		$result = checkEndpoint($dividedEnpoints,"zwroty Blik");
		$querySelect = "SELECT MAX(timestamp) FROM `kontrolki` WHERE name = '".$dividedEnpoints["zwroty Blik"]["title"]."'"; 
		$maxTimestamp = selectDB($querySelect,$connection);
		$timestamp = time()-$maxTimestamp;
		

	if($result != true){
			$queryInsert = "INSERT INTO `kontrolki` (`id`,`name`, `message`, `timestamp`) 
			VALUES (NULL,'".$dividedEnpoints["zwroty Blik"]["title"]."','".$dividedEnpoints["zwroty Blik"]["message"]."', ".time().")";
			insertDB($queryInsert, $connection);
			
		if(checkFalse($firebase, $dividedEnpoints["zwroty Blik"]["title"]) != 0){
			sendPush($dividedEnpoints["zwroty Blik"]["title"], $dividedEnpoints["zwroty Blik"]["message"]);
			retrieveFirebase($dividedEnpoints["zwroty Blik"]["title"], false, $dividedEnpoints["zwroty Blik"]["message"], $firebase);					
		} 
		
	} else if($timestamp>180){
		retrieveFirebase($dividedEnpoints["zwroty Blik"]["title"], true, 'brak problemów', $firebase);		
	}
		
	// database connection
	$resultOutputDb = getData($urlsDb);
	$problemWithDb = $resultOutputDb[0];
	
	if($problemWithDb > 0 ){
			$queryInsert = "INSERT INTO `kontrolki` (`id`,`name`, `message`, `timestamp`) 
			VALUES (NULL,'database problem','problem z bazą danych', ".time().")";
			insertDB($queryInsert, $connection);
			
		if(checkFalse($firebase, 'database problem') != 0){
			sendPush("database problem", "Odnotowano ".$problemWithDb." problemów z połączeniem z bazą.");	
			retrieveFirebase("database problem", false, "Odnotowano ".$problemWithDb." problemów z połączeniem z bazą.", $firebase);
		}
		
	} else if($timestamp>180){
		retrieveFirebase('database problem', true, 'brak problemów', $firebase);		
	}
	
	// do kontrolek allegro
	$result_output = getData($urls);
	$allegroRateRegister = getTrend($result_output[0], $result_output[2], "Rejestracja");
	$allegroRateVerification = getTrend($result_output[1], $result_output[3], "Weryfikacja");
	$allegroTrend = $allegroRateRegister.$allegroRateVerification;
	$trendResult = checkTrend($allegroTrend);
	$errorBlik = $result_output[4];
	
	$bliks = getBlik($urlBlikExec);
	$roudedBlikTimes = getAverageValueBlik($bliks);


		$querySelect = "SELECT MAX(timestamp) FROM `kontrolki` WHERE name = 'Allegro Trend'"; 
		$maxTimestamp = selectDB($querySelect,$connection);
		$timestamp = time()-$maxTimestamp;
	
	//trend
	if($trendResult != NULL ){
			$queryInsert = "INSERT INTO `kontrolki` (`id`,`name`, `message`, `timestamp`) 
			VALUES (NULL,'Allegro Trend','Problemy', ".time().")";
			insertDB($queryInsert, $connection);
			
		if(checkFalse($firebase, "Allegro Trend") != 0){	
				sendPush("Allegro Trend", $allegroTrend);	
				retrieveFirebase("Allegro Trend", false, $allegroTrend, $firebase);
		}
		
	} else if($timestamp>180) {
		retrieveFirebase("Allegro Trend", true, 'brak problemów', $firebase);		
	}		
	
	//blik
		$querySelect = "SELECT MAX(timestamp) FROM `kontrolki` WHERE name = 'Allegro Blik'"; 
		$maxTimestamp = selectDB($querySelect,$connection);
		$timestamp = time()-$maxTimestamp;
		
	if ($errorBlik >= 15){	
			$queryInsert = "INSERT INTO `kontrolki` (`id`,`name`, `message`, `timestamp`) 
			VALUES (NULL,'Allegro Blik','Problemy', ".time().")";
			insertDB($queryInsert, $connection);
	
		if (checkFalse($firebase, "Allegro Blik") != 0){
				sendPush("Allegro Blik", 
				"W ciągu ostatnich 15 minut odnotowano ".$errorBlik." problematycznych Blików - link do sprawdzenia: https://kibana.p24.org.pl/index.html#dashboard/temp/AWkP1bkm0oHX8I9KFWIV. Średni i_serviceTime = ".$roudedBlikTimes[0].", średni i_saveIdempotentTime = ".$roudedBlikTimes[1].".");
				retrieveFirebase("Allegro Blik", false, "W ciągu ostatnich 15 minut odnotowano ".$errorBlik." problematycznych Blików - link do sprawdzenia: https://kibana.p24.org.pl/index.html#dashboard/temp/AWkP1bkm0oHX8I9KFWIV. Średni i_serviceTime = ".$roudedBlikTimes[0].", średni i_saveIdempotentTime = ".$roudedBlikTimes[1].".", $firebase);
		}
		
	} else if($timestamp>180){
		retrieveFirebase("Allegro Blik", true, 'brak problemów', $firebase);		
	}
	//zamknięcie połączenia z bazą
	closeDB($connection);
	
?>