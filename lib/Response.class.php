<?php
namespace Response;
use DBConnection\DBConnection;

     class Response extends DBConnection{
          private $sMessage = '';
          private $nCode =1;
          private $oResponse;
          private $nRecords;
          private $nIdRow;
          private $bEstatus = 0;

          public function __construct(){
               parent::__construct();
          }

          function getSMessage() { 
               return $this->sMessage; 
          } 

          function setSMessage($sMessage) {  
               $this->sMessage = $sMessage; 
          }

          function getNCode()
          {
               return $this->nCode;
          }

          function setNCode($nCode)
          {
               $this->nCode = $nCode;
          } 
          
          function getNRecords() { 
                    return $this->nRecords; 
          } 
     
          function setNRecords($nRecords) {  
               $this->nRecords = $nRecords; 
          } 
          
          function getOResponse() { 
                return $this->oResponse; 
          } 
     
          function setOResponse($oResponse) {  
               $this->oResponse = $oResponse; 
          } 

          public function getNIdRow()
          {
                    return $this->nIdRow;
          }

          public function setNIdRow($nIdRow)
          {
                    $this->nIdRow = $nIdRow;
                    return $this;
          }

          public function getBEstatus()
          {
                return $this->bEstatus;
          }

          public function setBEstatus($bEstatus)
          {
               $this->bEstatus = $bEstatus;
               return $this;
          }
     }
?>