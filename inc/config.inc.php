<?php

##########################control error##############################################

#0 -> disabled
#1 -> Enable
error_reporting(E_ALL);
ini_set('display_errors', 0);

######################## generic Routes ##############################################

$CARPETA_BASE		= 'Geniat_API';
$LIB		= $_SERVER['DOCUMENT_ROOT']."/".$CARPETA_BASE."/lib/*.class.php";
$MODEL		= $_SERVER['DOCUMENT_ROOT']."/".$CARPETA_BASE."/model/*.class.php";


################ Libraries and custom functions ##############################

foreach(glob($LIB) as $filename){ 
	include $filename;
}

foreach(glob($MODEL) as $filename){
    include $filename;
}

include "functions.inc.php";


##############################DB Conection #################################

$IP		= getIP();
$oLog	= new Log($IP, 'Geniat');


$db_config_read = array(
	'sHost'			=> '127.0.0.1',
	'sUser'			=> 'root',
	'sPassword'		=> '',
	'sDatabase'		=> 'db_geniat',
	'oLog'			=> $oLog,
	'sPort'			=> '3306'
);

$db_config_Write = array(
	'sHost'			=> '127.0.0.1',
	'sUser'			=> 'root',
	'sPassword'		=> '',
	'sDatabase'		=> 'db_geniat',
	'oLog'			=> $oLog,
	'sPort'			=> '3306'
);


$oRdb = new _MySQLi($db_config_read);
$oWdb = new _MySQLi($db_config_Write);

/*Comment or remove the next lines to not save the calls statement to database into log file*/
$oRdb->setBDebug(1);
$oWdb->setBDebug(1);

if(!$oRdb->getBStatusConnection() || !$oWdb->getBStatusConnection()){
	echo "database connection fail";exit();
}


?>
