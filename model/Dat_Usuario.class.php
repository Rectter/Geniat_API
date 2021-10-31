<?php 
    use Response\Response;
	class Dat_Usuario extends Response{

		private $sNombre;
	    private $sApellidoPaterno;
	    private $sApellidoMaterno;
	    private $sCorreo;
	    private $sPassword;
	    private $sRol;
		
		public function __construct(){
			parent::__construct();
		}

        public function selectUsuario()
	    {
            $array_params = array(
	            array(
	                'name'    => 'P_sCorreo',
	                'value'   => $this->sCorreo,
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'P_sPassword',
	                'value'   =>$this->sPassword,
	                'type'    => 's'
	            )
            );
            $this->getORdb()->setParams($array_params);
	        $this->getORdb()->setSStoredProcedure('sp_select_usuario');
	        $oResult = $this->getORdb()->execute();
	        if (!$oResult) {
				$this->setsMessage($this->getORdb()->getSMessage());
				$this->setNCode($this->getORdb()->getNCode());
	            return $oResult;
	        }
	        $data = $this->getORdb()->fetchAll();
            $data = COUNT($data) == 1 ? $data[0] : $data;
	        $this->getORdb()->closeStmt();
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($this->getORdb()->getNRecords());
	    } 

		public function insertUsuario()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'P_sNombre',
	                'value'   => $this->sNombre,
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'P_sApellidoPaterno',
	                'value'   =>$this->sApellidoPaterno,
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'P_sApellidoMaterno',
	                'value'   => $this->sApellidoMaterno,
	                'type'    => 's'
                ),	            
	            array(
	                'name'    => 'P_sCorreo',
	                'value'   => $this->sCorreo,
	                'type'    => 's'
                ),
	            array(
	                'name'    => 'P_sPassword',
	                'value'   => $this->sPassword,
	                'type'    => 's'
                ),
	            array(
	                'name'    => 'P_sRol',
	                'value'   => $this->sRol,
	                'type'    => 's'
	            )
	        );
            
	        $this->getOWdb()->setParams($array_params);
	        $this->getOWdb()->setSStoredProcedure('sp_insert_usuario');

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

		public function updateUsuario()
	    {
	        $array_params = array(
	            array(
	                'name'    => 'P_nIdUsuario',
	                'value'   => $this->getNIdRow(),
	                'type'    => 's'
	            ),	
                array(
	                'name'    => 'P_sNombre',
	                'value'   => $this->sNombre,
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'P_sApellidoPaterno',
	                'value'   =>$this->sApellidoPaterno,
	                'type'    => 's'
	            ),	            
	            array(
	                'name'    => 'P_sApellidoMaterno',
	                'value'   => $this->sApellidoMaterno,
	                'type'    => 's'
                ),	            
	            array(
	                'name'    => 'P_sCorreo',
	                'value'   => $this->sCorreo,
	                'type'    => 's'
                ),
	            array(
	                'name'    => 'P_sPassword',
	                'value'   => $this->sPassword,
	                'type'    => 's'
                ),
	            array(
	                'name'    => 'P_sRol',
	                'value'   => $this->sRol,
	                'type'    => 's'
                ),
                array(
	                'name'    => 'P_bEstatus',
	                'value'   => $this->bEstatus,
	                'type'    => 'i'
	            )
	        );

	        $this->oRdb->setParams($array_params);
	        $this->oRdb->setSStoredProcedure('sp_update_usuario');
	        $oResult = $this->oWdb->execute();	      
			if (!$oResult) {
				$this->setsMessage($this->oWdb->getSMessage());
				$this->setNCode($this->oWdb->getNCode());
	            return $oResult;
	        }
	        $data = $this->oWdb->fetchAll();
	        $this->oWdb->closeStmt();
			$data = COUNT($data) == 1 ? $data[0] : $data;
	        $this->setNCode(0);
	        $this->setOResponse($data);
	        $this->setNRecords($this->oWdb->getNRecords());
	    }

		public function getSNombre()
		{
			return $this->sNombre;
		}

		public function setSNombre($sNombre)
		{
            $this->sNombre = $sNombre;
            return $this;
		}

	    public function getSApellidoPaterno()
	    {
            return $this->sApellidoPaterno;
	    }

	    public function setSApellidoPaterno($sApellidoPaterno)
	    {
            $this->sApellidoPaterno = $sApellidoPaterno;
            return $this;
	    }

	    public function getSApellidoMaterno()
	    {
            return $this->sApellidoMaterno;
	    }

	    public function setSApellidoMaterno($sApellidoMaterno)
	    {
            $this->sApellidoMaterno = $sApellidoMaterno;
            return $this;
	    }

	    public function getSCorreo()
	    {
            return $this->sCorreo;
	    }

	    public function setSCorreo($sCorreo)
	    {
            $this->sCorreo = $sCorreo;
            return $this;
	    }

	    public function getSPassword()
	    {
            return $this->sPassword;
	    }

	    public function setSPassword($sPassword)
	    {
            $this->sPassword = $sPassword;
            return $this;
	    }

	    public function getSRol()
	    {
            return $this->sRol;
	    }

	    public function setSRol($sRol)
	    {
            $this->sRol = $sRol;
            return $this;
	    }
    }
?>