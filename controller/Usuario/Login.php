<?php
    
    include $_SERVER['DOCUMENT_ROOT']."/Geniat_API/inc/config.inc.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/Geniat_API/vendor/autoload.php";
    
    validateContent($json_decoded);
    
    $oUsuario = new Dat_Usuario();
    $oUsuario->setORdb($oRdb);
   
    $oUsuario->setSCorreo($json_decoded['Correo']);
    $oUsuario->setSPassword(SHA1($json_decoded['Correo'].$json_decoded['Password']));
    $oUsuario->selectUsuario();
    $oResponse = custom_utf8($oUsuario->getOResponse());
    $nCode = $oUsuario->getNCode();
    $sMessage = $oUsuario->getSMessage();
    
    /*validate to execute*/
    if($nCode == 0){
        $nCode = $oResponse['nCodigo'];
        $sMessage = $oResponse['sMensaje'];
        /*validate to db response*/
        if($nCode == 0){
            $oAuthJWT = new AuthJWT();
            $sToken = $oAuthJWT->Sign([
                'nIdUsuario' => $oResponse['nIdUsuario'],
                'sNombre' => $oResponse['sNombre'],
                'nIdRol' => $oResponse['nIdRol']
            ]);
        }
    }
    
    $oResult = array('nCode'=>$nCode,
        'sMessage'=> $sMessage,
        'sToken'=> $sToken    
    );
    echo json_encode($oResult);

?>