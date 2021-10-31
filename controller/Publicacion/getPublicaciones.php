<?php
    
    include $_SERVER['DOCUMENT_ROOT']."/Geniat_API/inc/config.inc.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/Geniat_API/vendor/autoload.php";
    
    validateContent($json_decoded);

    $oAuthJWT = new AuthJWT();
    $bearerToken = getBearerToken();
    $nCode = 1;
    $sMessage = 'No se encontro la información del token';
    if($bearerToken != null){
        try{
            $dataToken = $oAuthJWT->getData($bearerToken);
            $sMessage = "No tienes permisos para consultar las publicaciones";
        }catch(Exception $e){
            $sMessage = "El Token es invalido o ya expiro"; 
        }

        if(in_array($dataToken->nIdRol , array(2,4,5))){
            $oPublicacion = new Dat_Publicacion();
            $oPublicacion->setORdb($oRdb);
           
            $oPublicacion->selectPublicacion();
            $oResponse = custom_utf8($oPublicacion->getOResponse());
            $nCode = $oPublicacion->getNCode();
            $sMessage = $oPublicacion->getSMessage();
            
            /*validate to execute*/
            if($nCode == 0){
                $sMessage = $oPublicacion->getNRecords() > 0 ? 'success' : 'No se encontraron registros para mostrar';        
            }
        }
    }
    
    
    $oResult = array('nCode'=>$nCode,
        'sMessage'=> $sMessage,
        'data' => $oResponse
    );
    echo json_encode($oResult);

?>