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
            $sMessage = "No tienes permisos para registrar usuarios";
        }catch(Exception $e){
            $sMessage = "El Token es invalido o ya expiro"; 
        }
        if(in_array($dataToken->nIdRol , array(4,5))){
            $oPublicacion = new Dat_Publicacion();
            $oPublicacion->setOWdb($oWdb);

            $oPublicacion->setNIdUsuario($dataToken->nIdUsuario);
            $oPublicacion->setNIdRow($json_decoded['IdPublicacion']);
            $oPublicacion->setSTitulo(utf8_decode($json_decoded['Titulo']));
            $oPublicacion->setSDescripcion(utf8_decode($json_decoded['Descripcion']));
            $oPublicacion->updatePublicacion();
            $oResponse = custom_utf8($oPublicacion->getOResponse());
            $nCode = $oPublicacion->getNCode();
            $sMessage = $oPublicacion->getSMessage();
            
            /*validate to execute*/
            if($nCode == 0){
                $nCode = $oResponse['nCodigo'];
                $sMessage = $oResponse['sMensaje'];
                /*validate to db response*/
                $sMessage = $nCode == 0 ? 'success' : $sMessage;
                $oResponse = $nCode != 0 ? null: $oResponse; 
            }
        }
    }
    
    
    $oResult = array('nCode'=>$nCode,
        'sMessage'=> $sMessage,
        'data' => $oResponse
    );
    echo json_encode($oResult);

?>