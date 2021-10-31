-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.20-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.1.0.6116
-- --------------------------------------------------------

DROP DATABASE IF EXISTS db_geniat;
CREATE DATABASE IF NOT EXISTS db_geniat; 
USE db_geniat;

DROP TABLE IF EXISTS `cat_roles`;
CREATE TABLE IF NOT EXISTS `cat_roles` (
  `nIdRol` int(11) NOT NULL AUTO_INCREMENT,
  `sNombre` varchar(50) NOT NULL,
  `sDescripcion` tinytext NOT NULL,
  `dFechaRegistro` datetime NOT NULL COMMENT 'Fecha y hora de la creacion del registro',
  `dFechaActualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha y hora de la ultima actualización al registro',
  PRIMARY KEY (`nIdRol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla donde se identifican los roles';

INSERT INTO `cat_roles` (`nIdRol`, `sNombre`, `sDescripcion`, `dFechaRegistro`) VALUES (1, 'Rol básico', 'Permiso de acceso', NOW());
INSERT INTO `cat_roles` (`nIdRol`, `sNombre`, `sDescripcion`, `dFechaRegistro`) VALUES (2, 'Rol medio', 'Permiso de acceso y consulta', NOW());
INSERT INTO `cat_roles` (`nIdRol`, `sNombre`, `sDescripcion`, `dFechaRegistro`) VALUES (3, 'Rol medio alto', 'Permiso de de acceso y agregar', NOW());
INSERT INTO `cat_roles` (`nIdRol`, `sNombre`, `sDescripcion`, `dFechaRegistro`) VALUES (4, 'Rol alto medio', 'Permiso de acceso, consulta, agregar y actualizar', NOW());
INSERT INTO `cat_roles` (`nIdRol`, `sNombre`, `sDescripcion`, `dFechaRegistro`) VALUES (5, 'Rol alto', 'Permiso de acceso, consulta, agregar, actualizar y eliminar', NOW());


DROP TABLE IF EXISTS `data_usuario`;
CREATE TABLE IF NOT EXISTS `data_usuario` (
  `nIdUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `nIdRol` int(11) NOT NULL,
  `sNombre` varchar(30) NOT NULL,
  `sApellidoPaterno` varchar(30) NOT NULL,
  `sApellidoMaterno` varchar(30) NOT NULL,
  `sCorreo` varchar(30) NOT NULL,
  `sPassword` varchar(50) NOT NULL,
  `dFecRegistro` datetime NOT NULL,
  `dFecMovimiento` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIdUsuario`),
  KEY `FK1_cat_roles_nIdRol` (`nIdRol`),
  CONSTRAINT `FK1_cat_roles_nIdRol` FOREIGN KEY (`nIdRol`) REFERENCES `cat_roles` (`nIdRol`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena la informacion general de los usuarios';

INSERT INTO `data_usuario` (`nIdUsuario`, `nIdRol`, `sNombre`, `sApellidoPaterno`, `sApellidoMaterno`, `sCorreo`, `sPassword`, `dFecRegistro`) VALUES (1, 5, 'Rene', 'Cuamatzi', 'Briones', 'rcuamatzi@gmail.com', 'e25d6501c780dc8d921c6362e768e6a74e7fe7ea', NOW());
INSERT INTO `data_usuario` (`nIdUsuario`, `nIdRol`, `sNombre`, `sApellidoPaterno`, `sApellidoMaterno`, `sCorreo`, `sPassword`, `dFecRegistro`) VALUES (2, 1, 'Invitado', 'Usuario', 'Promedio', 'invitado@gmail.com', 'cbbd169f0a351c4e351d594425c90dabecbdab58', NOW());


DROP TABLE IF EXISTS `data_publicacion`;
CREATE TABLE IF NOT EXISTS `data_publicacion` (
  `nIdPublicacion` int(11) NOT NULL AUTO_INCREMENT,
  `nIdUsuario` int(11) NOT NULL,
  `nIdEstatus` tinyint(4) NOT NULL DEFAULT 0,
  `sTitulo` varchar(50) NOT NULL,
  `sDescripcion` varchar(50) NOT NULL,
  `dFecRegistro` datetime NOT NULL,
  `dFecMovimiento` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`nIdPublicacion`),
  KEY `FK1_dat_usuario_nIdUsuario` (`nIdUsuario`),
  CONSTRAINT `FK1_dat_usuario_nIdUsuario` FOREIGN KEY (`nIdUsuario`) REFERENCES `data_usuario` (`nIdUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Almacena las publicaciones de los usuarios';

INSERT INTO `data_publicacion` (`nIdPublicacion`, `nIdUsuario`, `nIdEstatus`, `sTitulo`, `sDescripcion`, `dFecRegistro`) VALUES (1, 1, 0, 'Los 4 acuerdos', 'Lectura de los toltecas', NOW());
INSERT INTO `data_publicacion` (`nIdPublicacion`, `nIdUsuario`, `nIdEstatus`, `sTitulo`, `sDescripcion`, `dFecRegistro`) VALUES (2, 1, 0, 'Aura', 'Lectura de Carlos fuentes', NOW());
INSERT INTO `data_publicacion` (`nIdPublicacion`, `nIdUsuario`, `nIdEstatus`, `sTitulo`, `sDescripcion`, `dFecRegistro`) VALUES (3, 1, 1, 'La historia del loco', 'Libro de  Jhon Katzenbach', NOW());


DROP PROCEDURE IF EXISTS `sp_delete_publicacion`;
DELIMITER //
     CREATE PROCEDURE IF NOT EXISTS `sp_delete_publicacion`(IN `P_nIdPublicacion` INT, IN `P_nIdUsuario` INT)
     BEGIN
          SET @sMensaje ='';
          SET @nCodigo = 1;
          SET @nIdPublicacion = 0;
          SET @nIdRegistro = 0;
          SET @next_step = 0; 
 
          SELECT nIdPublicacion INTO @nIdPublicacion
          FROM dat_publicacion
          WHERE nIdPublicacion = P_nIdPublicacion AND nIdUsuario = P_nIdUsuario;
               
          IF @nIdPublicacion > 0 THEN
               SET @next_step = 1;
          ELSE
          		SET @sMensaje = 'No se encontro información relacionada a la publicación';
          END IF;
 
          IF @next_step = 1 THEN
              DELETE FROM dat_publicacion 
				   	WHERE nIdPublicacion = CknIdPublicacion 
								AND nIdUsuario = P_nIdUsuario; 
               
               SET @nIdRegistro = ROW_COUNT();
          
               IF @nIdRegistro > 0 THEN
                    SET @nCodigo = 0;
                    SET @sMensaje = 'Publiación eliminada exitosamente';
               ELSE
                    SET @sMensaje = 'No se pudo eliminar la publicación del usuario, vuelva a intentarlo';
               END IF;
 
          END IF;
          
          SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje, @nIdPublicacion AS nIdPublicacion;
     END//
DELIMITER ;


DROP PROCEDURE IF EXISTS `sp_insert_publicacion`;
DELIMITER //
     CREATE PROCEDURE IF NOT EXISTS `sp_insert_publicacion`(
          IN `P_nIdUsuario` INT,
          IN `P_sTitulo` VARCHAR(50),
          IN `P_sDescripcion` TINYTEXT
     )
     BEGIN

          SET @sMensaje ='';
          SET @nCodigo = 1;
          SET @nIdUsuario = 0;
          SET @nIdRegistro = 0;
          SET @next_step = 0; 
 
          SELECT nIdUsuario INTO @nIdUsuario
          FROM data_usuario
          WHERE nIdUsuario = P_nIdUsuario;
               
          IF @nIdUsuario > 0 THEN
               SET @next_step = 1;
          ELSE
          		SET @sMensaje = 'No se encontro el usuario indicado';
          END IF;
 
          IF @next_step = 1 THEN
               INSERT INTO data_publicacion(`nIdUsuario`, `sTitulo`, `sDescripcion`, dFecRegistro)
               VALUES (P_nIdUsuario, P_sTitulo, P_sDescripcion, NOW());
               
               SET @nIdRegistro = LAST_INSERT_ID();
          
               IF @nIdRegistro > 0 THEN
                    SET @nCodigo = 0;
                    SET @sMensaje = 'Publicación registrada exitosamente';
               ELSE
                    SET @sMensaje = 'No se pudo registrar la publicación, vuelva a intentarlo';
               END IF;
 
          END IF;
          
          SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje, @nIdRegistro AS nIdPublicacion;
     END//
DELIMITER ;


DROP PROCEDURE IF EXISTS `sp_insert_usuario`;
DELIMITER //
     CREATE PROCEDURE IF NOT EXISTS `sp_insert_usuario`(
          IN `P_sNombre` VARCHAR(30),
          IN `P_ApellidoPaterno` VARCHAR(30),
          IN `P_ApellidoMaterno` VARCHAR(30),
          IN `P_sCorreo` VARCHAR(30),
          IN `P_sPassword` VARCHAR(30),
          IN `P_sRol` VARCHAR(30)
     )
     BEGIN
  
 
          SET @sMensaje ='';
          SET @nCodigo = 1;
          SET @nIdUsuario = 0;
          SET @nIdRol = 0;
          SET @nIdRegistro = 0;
          SET @next_step = 0; 
 
          SELECT nIdUsuario INTO @nIdUsuario
          FROM data_usuario
          WHERE sCorreo = P_sCorreo;
               
          IF @nIdUsuario > 0 THEN
               SET @sMensaje = 'El correo ya se encuentra registrado';
          ELSE
               SET @next_step = 1;
          END IF;
          
          IF @next_step = 1 THEN
		          SELECT nIdRol INTO @nIdRol
		          FROM cat_roles
		          WHERE nIdRol = P_sRol;
		               
		          IF @nIdRol > 0 THEN
		               SET @next_step = 2;
		          ELSE
		               SET @sMensaje = 'No se encontro información para el Rol indicado' ;
		          END IF;
          END IF;
 
          IF @next_step = 2 THEN
               INSERT INTO data_usuario(`sNombre`, `sApellidoPaterno`, `sApellidoMaterno`, `sCorreo`, `sPassword`, `nIdRol`, dFecRegistro)
               VALUES (P_sNombre, P_ApellidoPaterno, P_ApellidoMaterno, P_sCorreo , P_sPassword, @nIdRol, NOW());
               
               SET @nIdRegistro = LAST_INSERT_ID();
          
               IF @nIdRegistro > 0 THEN
                    SET @nCodigo = 0;
                    SET @sMensaje = 'Usuario registrado exitosamente';
               ELSE
                    SET @sMensaje = 'No se pudo registrar el usuario, vuelva a intentarlo';
               END IF;
 
          END IF;
          
          SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje;
 
     END//
DELIMITER ;

DROP PROCEDURE IF EXISTS `sp_select_publicacion`;
DELIMITER //
     CREATE PROCEDURE IF NOT EXISTS `sp_select_publicacion`()
     BEGIN
          SELECT dp.sTitulo,
                         dp.sDescripcion,
                         dp.dFecRegistro,
                         CONCAT (du.sNombre, ' ', du.sApellidoPaterno, ' ', du.sApellidoMaterno) AS sUsuarioNombreCompleto,
                         cr.sNombre AS sNombreRol
          FROM data_usuario AS du
          INNER JOIN data_publicacion AS dp 
               ON dp.nIdUsuario = du.nIdUsuario AND dp.nIdEstatus = 0
          INNER JOIN cat_roles AS cr ON cr.nIdRol = du.nIdRol;

     
               
     END//
DELIMITER ;


DROP PROCEDURE IF EXISTS `sp_select_usuario`;
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS `sp_select_usuario`(
	IN `CksCorreo` CHAR(30),
	IN `CksPassword` TEXT
)
BEGIN

     SET @sMensaje ='No se encontro información usuario';
     SET @nCodigo = 1;
     SET @nIdUsuario = 0;
     SET @next_step = 0; 
	
	SELECT nIdUsuario,
			 nIdRol,
			 sNombre
			INTO @nIdUsuario,
				  @nIdRol,
				  @sNombre
	FROM data_usuario 
	WHERE sCorreo= CksCorreo AND sPassword=CksPassword;
	
	IF(@nIdUsuario > 0)THEN
		SET @nCodigo = 0;
		SET @sMensaje = CONCAT('Bienvenido ', @sNombre);
	END IF;	
	SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje, @nIdRol AS nIdRol, @nIdUsuario AS nIdUsuario, @sNombre AS sNombre;
	
END//
DELIMITER ;


DROP PROCEDURE IF EXISTS `sp_update_publicacion`;
DELIMITER //
     CREATE PROCEDURE IF NOT EXISTS `sp_update_publicacion`(
          IN `P_nIdPublicacion` INT,
          IN `P_nIdUsuario` INT,
          IN `P_sTitulo` VARCHAR(50),
          IN `P_sDescripcion` TINYTEXT,
          IN `P_nIdEstatus` TINYINT
     )
     BEGIN
     
          SET @sMensaje ='';
          SET @nCodigo = 1;
          SET @nIdPublicacion = 0;
          SET @nIdRegistro = 0;
          SET @next_step = 0; 
 
          SELECT nIdPublicacion, sTitulo, sDescripcion, nIdEstatus
			 INTO @nIdPublicacion, @sTitulo, @sDescripcion, @nIdEstatus
          FROM data_publicacion
          WHERE nIdPublicacion = P_nIdPublicacion AND nIdUsuario = P_nIdUsuario;
               
          IF @nIdPublicacion > 0 THEN
               SET @next_step = 1;
          ELSE
          		SET @sMensaje = 'No se encontro información relacionada a la publicación';
          END IF;
 
          IF @next_step = 1 THEN
         	IF @nIdEstatus = 0  THEN
              UPDATE data_publicacion 
				  			SET sDescripcion = IF(P_nIdEstatus > 0 , @sDescripcion, P_sDescripcion),
				   			 sTitulo = IF(P_nIdEstatus > 0 , @sTitulo, P_sTitulo),
				   			 nIdEstatus = P_nIdEstatus
				   		WHERE nIdPublicacion = P_nIdPublicacion AND nIdUsuario = P_nIdUsuario; 
               
               SET @nIdRegistro = ROW_COUNT();
          
               IF @nIdRegistro > 0 THEN
                    SET @nCodigo = 0;
                    SET @sMensaje = IF(P_nIdEstatus > 0 ,'La publicación se elimino correctamente', 'Publiación actualizada exitosamente');
               ELSE
                    SET @sMensaje = IF(P_nIdEstatus > 0 ,'No se pudo eliminar la publicación', 'No se pudo actualizar la publicación del usuario, vuelva a intentarlo');
               END IF;
            ELSE
                 SET @sMensaje = 'Esta publicación ha sido eliminada previamente';
            END IF;
 
          END IF;
          
          SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje, @nIdRegistro AS nIdPublicacion;
     END//
DELIMITER ;
