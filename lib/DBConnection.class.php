<?php 
     namespace DBConnection;
     class DBConnection 
     {
          private $oRdb;
          private $oWdb;

          function __construct(){}

          function getORdb()
          {
               return $this->oRdb;
          }

          function setORdb($oRdb)
          {
               $this->oRdb = $oRdb;
          }

          function getOWdb()
          {
               return $this->oWdb;
          }

          function setOWdb($oWdb)
          {
               $this->oWdb = $oWdb;
          } 
     }
?>