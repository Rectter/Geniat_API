<?php 
	use Response\Response;
	class Dat_Publicacion extends Response{

		private $nIdUsuario;
		private $sDescripcion;
		private $sTitulo;
		
		public function __construct(){
			parent::__construct();
		}

		public function selectPublicacion()
	    {
	        $this->getORdb()->setSStoredProcedure('sp_select_publicacion');
	        $oResult = $this->getORdb()->execute();
	        if (!$oResult) {
				$this->setsMessage($this->getORdb()->getSMessage());
				$this->setNCode($this->getORdb()->getNCode());
	            return $oResult;
	        }
	        $data = $this->getORdb()->fetchAll();
	        $this->getORdb()->closeStmt();
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($this->getORdb()->getNRecords());
	    } 

		public function insertPublicacion()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'P_nIdUsuario',
	                'value'   => $this->nIdUsuario,
	                'type'    => 'i'
	            ),	            
	            array(
	                'name'    => 'P_sTitulo',
	                'value'   =>$this->sTitulo,
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'P_sDescripcion',
	                'value'   => $this->sDescripcion,
	                'type'    => 's'
	            )
	        );
	        $this->getOWdb()->setParams($array_params);
	        $this->getOWdb()->setSStoredProcedure('sp_insert_publicacion');

	        $oResult = $this->getOWdb()->execute();	      
			if (!$oResult) {
				$this->setsMessage($this->getOWdb()->getSMessage());
				$this->setNCode($this->getOWdb()->getNCode());
	            return $oResult;
	        }
	        $data = $this->getOWdb()->fetchAll();
	        $this->getOWdb()->closeStmt();
			$data = COUNT($data) == 1 ? $data[0] : $data;
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($this->getOWdb()->getNRecords());
	    }

		public function updatePublicacion()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'CknIdPublicacion',
	                'value'   => $this->getNIdRow(),
	                'type'    => 'i'
	            ),
	            array(
	                'name'    => 'CknIdUsuario',
	                'value'   => $this->nIdUsuario,
	                'type'    => 'i'
	            ),            
	            array(
	                'name'    => 'CksTitulo',
	                'value'   => $this->sTitulo,
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'CksDescripcion',
	                'value'   => $this->sDescripcion,
	                'type'    => 's'
				),
				array(
	                'name'    => 'CkbEstatus',
	                'value'   => $this->getBEstatus(),
	                'type'    => 'i'
	            )
	        );

	        $this->getOWdb()->setParams($array_params);
	        $this->getOWdb()->setSStoredProcedure('sp_update_publicacion');
	        $oResult = $this->getOWdb()->execute();	      
			if (!$oResult) {
				$this->setsMessage($this->getOWdb()->getSMessage());
				$this->setNCode($this->getOWdb()->getNCode());
	            return $oResult;
	        }
	        $data = $this->getOWdb()->fetchAll();
	        $this->getOWdb()->closeStmt();
			$data = COUNT($data) == 1 ? $data[0] : $data;
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($this->getOWdb()->getNRecords());
	    }

		public function getNIdPublicacion()
		{
			return $this->nIdPublicacion;
		}

		public function setNIdPublicacion($nIdPublicacion)
		{
			$this->nIdPublicacion = $nIdPublicacion;
			return $this;
		}

		public function getNIdUsuario()
		{
			return $this->nIdUsuario;
		}

		public function setNIdUsuario($nIdUsuario)
		{
			$this->nIdUsuario = $nIdUsuario;
			return $this;
		}

		public function getSDescripcion()
		{
			return $this->sDescripcion;
		}

		public function setSDescripcion($sDescripcion)
		{
			$this->sDescripcion = $sDescripcion;
			return $this;
		}

		public function getSTitulo()
		{
			return $this->sTitulo;
		}

		public function setSTitulo($sTitulo)
		{
			$this->sTitulo = $sTitulo;
			return $this;
		}
	}

	
?>