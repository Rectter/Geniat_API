<?php

	use Response\Response;

	class _MySQLi extends Response{

		private $sHost;
		private $sUser;
		private $sPassword;
		private $sDatabase;
		private $sPort;
		private $strConection;
		private $sStoredProcedure;
		private $oLog;
		private $stmt;
		private $result;
		private $bDebug = 0;
		private $bStatusConnection = false;
		private $arrParams = array();


		public function __construct($array_config){
			parent::__construct();
			$this->sHost = $array_config['sHost'];		
			$this->sUser = $array_config['sUser'];
			$this->sPassword = $array_config['sPassword'];
			$this->sDatabase = $array_config['sDatabase'];
			$this->sPort = $array_config['sPort'];
			$this->oLog = $array_config['oLog'];
			$this->_connectme();
		}

		private function _connectme(){
			try{
				$this->strConection = new mysqli($this->sHost, $this->sUser, $this->sPassword, $this->sDatabase, $this->sPort);
				mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
				$this->bStatusConnection = true;
			}
			catch(mysqli_sql_exception $e){
				$this->oLog->error('Connection error code-> ('.$e->getCode().') message ->'. $e->getMessage());
			}
		}

		public function execute(){
			$bResult = false;
			if($this->bDebug == 1){
				$sParams		= $this->_concatParams();
				$sDebugQuery	= "CALL `".$this->sDatabase."`.`".$this->sStoredProcedure."`(".$sParams.");";
				$this->oLog->error($sDebugQuery);
			}

			$paramsString	= $this->_getParamsString();
			$sQuery	= "CALL `".$this->sDatabase."`.`".$this->sStoredProcedure."`(".$paramsString.");";

			try{
				$this->stmt = $this->strConection->prepare($sQuery);
				$this->_bindParams();
				$this->stmt->execute();
				$this->result = $this->stmt->get_result();
				$this->setNRecords($this->result->num_rows);
				$bResult = true;
			}
			catch(mysqli_sql_exception $e){
				$sMessage = "Error al ejecutar ".$sQuery." (".$e->getCode().") : ".$e->getMessage()." L ".$e->getLine()." FILE ".$e->getFile();
				$this->oLog->error($sMessage);
				$this->setNCode($e->getCode());
				$this->setSMessage($sMessage);
			}
			return $bResult;
		}

		private function _getParamsString(){
			$paramsString	= "";
			for($i=0; $i < COUNT($this->arrParams); $i++){
				$paramsString .= "?,";
			}
			$paramsString = trim($paramsString, ',');

			return $paramsString;
		}

		private function _concatParams(){
			$arrParams = $this->arrParams;
			$sParams = "";
			if(count($arrParams) >= 1){
				for($i=0; $i < count($arrParams); $i++){
					$arrValues[] = $arrParams[$i]['value'];
				}
				$sParams	= implode("','", $arrValues);
			}

			return "'".$sParams."'";
		}

		/*
		* assign difined params use bind_param from mysqli.
		* string type params eg.('ssiidd')
		* get values from array params to new array whith only values.
		*/
		private function _bindParams(){
			$arrParams		= $this->arrParams;
			$length			= count($arrParams);

			if($length >= 1){
				$array_params	= array();
				$param_type		= "";

				for($i = 0; $i < $length; $i++) {
					$param_type .= $arrParams[$i]['type'];
				}

				$array_params[] =& $param_type;

				for($i = 0; $i < $length; $i++) {
					$array_params[] =& $arrParams[$i]['value'];
				}

				call_user_func_array(array($this->stmt, 'bind_param'), $array_params);
			}
		}

		public function lastInsertId(){
			$this->stmt->prepare("SELECT LAST_INSERT_ID() AS last_insert_id");
			$this->stmt->execute();
			$this->result = $this->stmt->get_result();
			$array = $this->fetchAll();

			return $array[0]['last_insert_id'];
		}

		public function foundRows(){
			$this->stmt = $this->strConection->prepare("SELECT FOUND_ROWS() AS found_rows");
			$this->stmt->execute();
			$this->result = $this->stmt->get_result();
			$array = $this->fetchAll();
			$nRecors = $array[0]['found_rows'] >= 0 ? $array[0]['found_rows'] : 0;
			$this->setNRecords($nRecors);
		}

		/* return array with rows found*/
		public function fetchAll(){
			$array = $this->result->fetch_all(MYSQLI_ASSOC);
			return $array;
		}

		public function closeConnection(){
			$this->strConection->close();
		}

		public function closeStmt(){
			$this->stmt->close();
		} 

		public function freeResult(){
			$this->result->free_result();
		} 

		public function setSHost($sHost){
			$this->sHost = $sHost;
		}

		public function getSHost(){
			return $this->sHost;
		}

		public function setSUser($sUser){
			$this->sUser = $sUser;
		}

		public function getSUser(){
			return $this->sUser;
		}

		public function setSPassword($sPassword){
			$this->sPassword = $sPassword;
		}

		public function getSPassword(){
			return $this->sPassword;
		}

		public function setSDatabase($sDatabase){
			$this->sDatabase = $sDatabase;
		}

		public function getSDatabase(){
			return $this->sDatabase;
		}

		public function setSPort($sPort){
			$this->sPort = $sPort;
		}

		public function getSPort(){
			return $this->sPort;
		}

		public function setLOG($oLog){
			$this->oLog = $oLog;
		}

		public function getLOG(){
			return $this->oLog;
		}

		public function setSStoredProcedure($sStoredProcedure){
			$this->sStoredProcedure = $sStoredProcedure;
		}

		public function getSStoredProcedure(){
			return $this->sStoredProcedure;
		}

		public function setParams($arrParams){
			$this->arrParams = $arrParams;
		}

		public function getParams(){
			return $this->arrParams;
		}

		public function setBDebug($bDebug){
			$this->bDebug = $bDebug;
		}

		public function getBDebug(){
			return $this->bDebug;
		}

		public function setResult($result){
			$this->result = $result;
		}

		public function getResult(){
			return $this->result;
		}

		public function getStrConection()
		{
			return $this->strConection;
		}

		public function setStrConection($strConection)
		{
			$this->strConection = $strConection;
			return $this;
		}

		public function getBStatusConnection()
		{
			return $this->bStatusConnection;
		}

		public function setBStatusConnection($bStatusConnection)
		{
			$this->bStatusConnection = $bStatusConnection;
			return $this;
		}
	}
?>