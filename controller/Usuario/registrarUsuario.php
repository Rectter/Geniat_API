<?php
    
    include $_SERVER['DOCUMENT_ROOT']."/Geniat_API/inc/config.inc.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/Geniat_API/vendor/autoload.php";
    
    validateContent('POST',$json_decoded);
    
    
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
        if(in_array($dataToken->nIdRol , array(3,4,5))){
            
            $oUsuario = new Dat_Usuario();
            $oUsuario->setOWdb($oWdb);
            $oUsuario->setSCorreo($json_decoded['Correo']);
            $oUsuario->setSPassword(SHA1($json_decoded['Correo'].$json_decoded['Password']));
            $oUsuario->setSNombre(utf8_decode($json_decoded['Nombre']));
            $oUsuario->setSApellidoPaterno(utf8_decode($json_decoded['ApellidoPaterno']));
            $oUsuario->setSApellidoMaterno(utf8_decode($json_decoded['ApellidoMaterno']));
            $oUsuario->setSRol($json_decoded['Rol']);
            $oUsuario->insertUsuario();
            $oResponse = custom_utf8($oUsuario->getOResponse());
            $nCode = $oUsuario->getNCode();
            $sMessage = $oUsuario->getSMessage();
            
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