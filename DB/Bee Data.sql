-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema beedata
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema beedata
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `beedata` DEFAULT CHARACTER SET utf8 ;
USE `beedata` ;

-- -----------------------------------------------------
-- Table `beedata`.`archivos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`archivos` (
  `idArchivos` INT(11) NOT NULL AUTO_INCREMENT,
  `NombreArchivo` VARCHAR(45) NULL DEFAULT NULL,
  `Direccion` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`idArchivos`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(249) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `username` VARCHAR(100) NULL DEFAULT NULL,
  `status` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
  `verified` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `resettable` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `roles_mask` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `registered` INT(10) UNSIGNED NOT NULL,
  `last_login` INT(10) UNSIGNED NULL DEFAULT NULL,
  `force_logout` MEDIUMINT(7) UNSIGNED NOT NULL DEFAULT '0',
  `role` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email` (`email` ASC),
  UNIQUE INDEX `username` (`username` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`administrador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`administrador` (
  `fkusers_id` INT(10) UNSIGNED NOT NULL,
  `Nombre` VARCHAR(45) NULL DEFAULT NULL,
  `ApPaterno` VARCHAR(45) NULL DEFAULT NULL,
  `ApMaterno` VARCHAR(45) NULL DEFAULT NULL,
  `Empresa` VARCHAR(80) NULL DEFAULT NULL,
  `Cargo` VARCHAR(45) NULL DEFAULT NULL,
  `Telefono` VARCHAR(20) NULL DEFAULT NULL,
  `fkidArchivos` INT(11) NULL,
  PRIMARY KEY (`fkusers_id`),
  INDEX `fk_admin_cuenta_archivos1_idx` (`fkidArchivos` ASC),
  CONSTRAINT `fk_admin_cuenta_archivos1`
    FOREIGN KEY (`fkidArchivos`)
    REFERENCES `beedata`.`archivos` (`idArchivos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_admin_cuenta_users1`
    FOREIGN KEY (`fkusers_id`)
    REFERENCES `beedata`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`giros_empresariales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`giros_empresariales` (
  `idGirosEmpresariales` INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`idGirosEmpresariales`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`unidad_negocio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`unidad_negocio` (
  `idUnidadNegocio` INT(11) NOT NULL AUTO_INCREMENT,
  `fkidGirosEmpresariales` INT(11) NOT NULL,
  `fkusers_id` INT(10) UNSIGNED NOT NULL,
  `fkidArchivos` INT(11) NULL,
  `Denominacion` VARCHAR(80) NULL DEFAULT NULL,
  `Estado` VARCHAR(45) NULL DEFAULT NULL,
  `Municipio` VARCHAR(45) NULL DEFAULT NULL,
  `Colonia` VARCHAR(45) NULL DEFAULT NULL,
  `CodigoPostal` INT(11) NULL DEFAULT NULL,
  `Calle` VARCHAR(80) NULL DEFAULT NULL,
  `NumeroInt` VARCHAR(6) NULL DEFAULT NULL,
  `NumeroExt` VARCHAR(6) NULL DEFAULT NULL,
  `Latitud` DECIMAL(10,0) NULL DEFAULT NULL,
  `Longitud` DECIMAL(10,0) NULL DEFAULT NULL,
  PRIMARY KEY (`idUnidadNegocio`, `fkidGirosEmpresariales`, `fkusers_id`),
  INDEX `fk_UnidadNegocio_GirosEmpresariales1_idx` (`fkidGirosEmpresariales` ASC),
  INDEX `fk_UnidadNegocio_Archivos1_idx` (`fkidArchivos` ASC),
  INDEX `fk_unidad_negocio_admin_cuenta1_idx` (`fkusers_id` ASC),
  CONSTRAINT `fk_UnidadNegocio_Archivos1`
    FOREIGN KEY (`fkidArchivos`)
    REFERENCES `beedata`.`archivos` (`idArchivos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_UnidadNegocio_GirosEmpresariales1`
    FOREIGN KEY (`fkidGirosEmpresariales`)
    REFERENCES `beedata`.`giros_empresariales` (`idGirosEmpresariales`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_unidad_negocio_admin_cuenta1`
    FOREIGN KEY (`fkusers_id`)
    REFERENCES `beedata`.`administrador` (`fkusers_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`campaña`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`campaña` (
  `idCampaña` INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(50) NULL DEFAULT NULL,
  `Tipo` ENUM('Correo', 'Presencial') NULL DEFAULT NULL,
  `Cantidad` INT(11) NULL DEFAULT NULL,
  `Estatus` ENUM('Inactiva', 'Activa') NULL DEFAULT NULL,
  `FechaInicio` DATE NULL DEFAULT NULL,
  `FechaFin` DATE NULL DEFAULT NULL,
  `kfidUnidadNegocio` INT(11) NOT NULL,
  PRIMARY KEY (`idCampaña`, `kfidUnidadNegocio`),
  INDEX `fk_Campaña_UnidadNegocio1_idx` (`kfidUnidadNegocio` ASC),
  CONSTRAINT `fk_Campaña_UnidadNegocio1`
    FOREIGN KEY (`kfidUnidadNegocio`)
    REFERENCES `beedata`.`unidad_negocio` (`idUnidadNegocio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`campaña_refill`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`campaña_refill` (
  `idCampañaRefill` INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(45) NULL DEFAULT NULL,
  `Fecha` DATE NULL DEFAULT NULL,
  `Mercado` INT(11) NULL DEFAULT NULL,
  `Estatus` ENUM('Sin Preparar', 'Preparar', 'Enviado') NULL DEFAULT NULL,
  PRIMARY KEY (`idCampañaRefill`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`frecuencia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`frecuencia` (
  `idFrecuencia` INT(11) NOT NULL AUTO_INCREMENT,
  `Frecuencia` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`idFrecuencia`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`analisis_campaña`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`analisis_campaña` (
  `idAnalisisCampaña` INT(11) NOT NULL AUTO_INCREMENT,
  `fkidCampaña` INT(11) NOT NULL,
  `Genero` ENUM('Masculino', 'Femenino') NULL DEFAULT NULL,
  `RangoInicial` INT(11) NULL DEFAULT NULL,
  `RangoFinal` INT(11) NULL DEFAULT NULL,
  `Happybirthday` INT(11) NULL DEFAULT NULL,
  `Estado` VARCHAR(45) NULL DEFAULT NULL,
  `Municipio` VARCHAR(45) NULL DEFAULT NULL,
  `Colonia` VARCHAR(45) NULL DEFAULT NULL,
  `fkidFrecuencia` INT(11) NOT NULL,
  `Valoracion` ENUM('Negativa', 'Positiva') NULL DEFAULT NULL,
  `fkidCampañaRefill` INT(11) NOT NULL,
  PRIMARY KEY (`idAnalisisCampaña`, `fkidCampaña`, `fkidFrecuencia`, `fkidCampañaRefill`),
  INDEX `fk_AnalisisCampaña_Frecuencia1_idx` (`fkidFrecuencia` ASC),
  INDEX `fk_AnalisisCampaña_CampañaRefill1_idx` (`fkidCampañaRefill` ASC),
  INDEX `fk_AnalisisCampaña_Campaña1` (`fkidCampaña` ASC),
  CONSTRAINT `fk_AnalisisCampaña_Campaña1`
    FOREIGN KEY (`fkidCampaña`)
    REFERENCES `beedata`.`campaña` (`idCampaña`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_AnalisisCampaña_CampañaRefill1`
    FOREIGN KEY (`fkidCampañaRefill`)
    REFERENCES `beedata`.`campaña_refill` (`idCampañaRefill`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_AnalisisCampaña_Frecuencia1`
    FOREIGN KEY (`fkidFrecuencia`)
    REFERENCES `beedata`.`frecuencia` (`idFrecuencia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`cliente` (
  `idCliente` INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(45) NULL DEFAULT NULL,
  `FechaNacimiento` DATE NULL DEFAULT NULL,
  `Genero` ENUM('Masculino', 'Femenino') NULL DEFAULT NULL,
  `Correo` VARCHAR(45) NULL DEFAULT NULL,
  `Celular` VARCHAR(20) NULL DEFAULT NULL,
  `Estado` VARCHAR(45) NULL DEFAULT NULL,
  `Municipio` VARCHAR(45) NULL DEFAULT NULL,
  `Colonia` VARCHAR(45) NULL DEFAULT NULL,
  `CodigoPostal` INT(11) NULL DEFAULT NULL,
  `fkidUnidadNegocio` INT(11) NOT NULL,
  `fkidFrecuencia` INT(11) NOT NULL,
  PRIMARY KEY (`idCliente`, `fkidUnidadNegocio`, `fkidFrecuencia`),
  INDEX `fk_Cliente_UnidadNegocio1_idx` (`fkidUnidadNegocio` ASC),
  INDEX `fk_Cliente_Frecuencia1_idx` (`fkidFrecuencia` ASC),
  CONSTRAINT `fk_Cliente_Frecuencia1`
    FOREIGN KEY (`fkidFrecuencia`)
    REFERENCES `beedata`.`frecuencia` (`idFrecuencia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cliente_UnidadNegocio1`
    FOREIGN KEY (`fkidUnidadNegocio`)
    REFERENCES `beedata`.`unidad_negocio` (`idUnidadNegocio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`analisis_procesado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`analisis_procesado` (
  `idAnalisisProcesado` INT(11) NOT NULL AUTO_INCREMENT,
  `fkidCampaña` INT(11) NOT NULL,
  `fkidCliente` INT(11) NOT NULL,
  PRIMARY KEY (`idAnalisisProcesado`, `fkidCampaña`, `fkidCliente`),
  INDEX `fk_AnalisisProcesado_AnalisisCampaña1_idx` (`fkidCampaña` ASC),
  INDEX `fk_AnalisisProcesado_Cliente1_idx` (`fkidCliente` ASC),
  CONSTRAINT `fk_AnalisisProcesado_AnalisisCampaña1`
    FOREIGN KEY (`fkidCampaña`)
    REFERENCES `beedata`.`analisis_campaña` (`fkidCampaña`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_AnalisisProcesado_Cliente1`
    FOREIGN KEY (`fkidCliente`)
    REFERENCES `beedata`.`cliente` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`catalogo_ciudad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`catalogo_ciudad` (
  `idCatalogoCiudad` INT(11) NOT NULL AUTO_INCREMENT,
  `Estado` VARCHAR(45) NULL DEFAULT NULL,
  `Municipio` VARCHAR(45) NULL DEFAULT NULL,
  `Colonia` VARCHAR(45) NULL DEFAULT NULL,
  `CodigoPostal` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idCatalogoCiudad`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`correo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`correo` (
  `idCorreo` INT(11) NOT NULL AUTO_INCREMENT,
  `logo` INT(11) NULL,
  `Titulo` VARCHAR(45) NULL DEFAULT NULL,
  `Contenido` TEXT NULL DEFAULT NULL,
  `Url` VARCHAR(45) NULL DEFAULT NULL,
  `Contacto` TEXT NULL DEFAULT NULL,
  `fkidCampañaRefill` INT(11) NOT NULL,
  PRIMARY KEY (`idCorreo`, `fkidCampañaRefill`),
  INDEX `fk_Correo_Archivos1_idx` (`logo` ASC),
  INDEX `fk_Correo_CampañaRefill1_idx` (`fkidCampañaRefill` ASC),
  CONSTRAINT `fk_Correo_Archivos1`
    FOREIGN KEY (`logo`)
    REFERENCES `beedata`.`archivos` (`idArchivos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Correo_CampañaRefill1`
    FOREIGN KEY (`fkidCampañaRefill`)
    REFERENCES `beedata`.`campaña_refill` (`idCampañaRefill`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`descuento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`descuento` (
  `idDescuento` INT(11) NOT NULL AUTO_INCREMENT,
  `Descuento` INT(11) NULL DEFAULT NULL,
  `Activas` INT(11) NULL DEFAULT NULL,
  `descuentocol` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`idDescuento`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`facturacion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`facturacion` (
  `Denominacion` VARCHAR(45) NULL DEFAULT NULL,
  `Rfc` VARCHAR(45) NULL DEFAULT NULL,
  `Estado` VARCHAR(45) NULL DEFAULT NULL,
  `Municipio` VARCHAR(45) NULL DEFAULT NULL,
  `Colonia` VARCHAR(45) NULL DEFAULT NULL,
  `CodigoPostal` INT(11) NULL DEFAULT NULL,
  `Calle` VARCHAR(80) NULL DEFAULT NULL,
  `NumeroInt` VARCHAR(6) NULL DEFAULT NULL,
  `NumeroExt` VARCHAR(6) NULL DEFAULT NULL,
  `fkidUnidadNegocio` INT(11) NOT NULL,
  PRIMARY KEY (`fkidUnidadNegocio`),
  INDEX `fk_Facturacion_UnidadNegocio1_idx` (`fkidUnidadNegocio` ASC),
  CONSTRAINT `fk_Facturacion_UnidadNegocio1`
    FOREIGN KEY (`fkidUnidadNegocio`)
    REFERENCES `beedata`.`unidad_negocio` (`idUnidadNegocio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`grupo_respuestas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`grupo_respuestas` (
  `idGrupoRespuestas` INT(11) NOT NULL AUTO_INCREMENT,
  `NombreGrupo` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`idGrupoRespuestas`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`planes_membresia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`planes_membresia` (
  `idPlanesMembresia` INT(11) NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(50) NULL DEFAULT NULL,
  `Monto` DECIMAL(10,2) NULL DEFAULT NULL,
  `PagoDiferido` INT(11) NULL DEFAULT NULL,
  `LimiteClientes` INT(11) NULL DEFAULT NULL,
  `LimiteEncuestas` INT NULL,
  `Duracion` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`idPlanesMembresia`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`membresia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`membresia` (
  `idMembresia` INT(11) NOT NULL AUTO_INCREMENT,
  `fkidUnidadNegocio` INT(11) NOT NULL,
  `fkidPlanesMembresia` INT(11) NOT NULL,
  `Descuento_idDescuento` INT(11) NULL DEFAULT NULL,
  `FechaInicio` DATE NULL DEFAULT NULL,
  `FechaFin` DATE NULL DEFAULT NULL,
  `Estatus` ENUM('No Activa', 'Comprobación de pago', 'Activa') NULL DEFAULT NULL,
  PRIMARY KEY (`idMembresia`, `fkidUnidadNegocio`),
  INDEX `fk_pago_PlanesMembresia1_idx` (`fkidPlanesMembresia` ASC),
  INDEX `fk_pago_Descuento1_idx` (`Descuento_idDescuento` ASC),
  INDEX `fk_membresia_unidad_negocio1_idx` (`fkidUnidadNegocio` ASC),
  CONSTRAINT `fk_Cobro_Descuento1`
    FOREIGN KEY (`Descuento_idDescuento`)
    REFERENCES `beedata`.`descuento` (`idDescuento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cobro_PlanesMembresia1`
    FOREIGN KEY (`fkidPlanesMembresia`)
    REFERENCES `beedata`.`planes_membresia` (`idPlanesMembresia`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_membresia_unidad_negocio1`
    FOREIGN KEY (`fkidUnidadNegocio`)
    REFERENCES `beedata`.`unidad_negocio` (`idUnidadNegocio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`plan_accion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`plan_accion` (
  `idPlanAccion` INT(11) NOT NULL AUTO_INCREMENT,
  `fkidCampaña` INT(11) NOT NULL,
  `fkidUnidadNegocio` INT(11) NOT NULL,
  `estatus` ENUM('Por programar', 'En proceso', 'Terminado') NULL DEFAULT NULL,
  PRIMARY KEY (`idPlanAccion`, `fkidCampaña`, `fkidUnidadNegocio`),
  INDEX `fk_PlanAccion_Campaña1_idx` (`fkidCampaña` ASC),
  INDEX `fk_PlanAccion_UnidadNegocio1_idx` (`fkidUnidadNegocio` ASC),
  CONSTRAINT `fk_PlanAccion_Campaña1`
    FOREIGN KEY (`fkidCampaña`)
    REFERENCES `beedata`.`campaña` (`idCampaña`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_PlanAccion_UnidadNegocio1`
    FOREIGN KEY (`fkidUnidadNegocio`)
    REFERENCES `beedata`.`unidad_negocio` (`idUnidadNegocio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`customer_journey_map`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`customer_journey_map` (
  `idCustomer_journey_map` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NULL,
  `fkidGirosEmpresariales` INT(11) NULL,
  PRIMARY KEY (`idCustomer_journey_map`),
  INDEX `fk_customer_journey_map_giros_empresariales1_idx` (`fkidGirosEmpresariales` ASC),
  CONSTRAINT `fk_customer_journey_map_giros_empresariales1`
    FOREIGN KEY (`fkidGirosEmpresariales`)
    REFERENCES `beedata`.`giros_empresariales` (`idGirosEmpresariales`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `beedata`.`momento_verdad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`momento_verdad` (
  `idMomento_verdad` INT(11) NOT NULL AUTO_INCREMENT,
  `fkidCoustomer_journey_map` INT NOT NULL,
  `fkidGirosEmpresariales` INT(11) NOT NULL,
  `Momento_verdad` VARCHAR(50) NULL,
  PRIMARY KEY (`idMomento_verdad`, `fkidCoustomer_journey_map`, `fkidGirosEmpresariales`),
  INDEX `fk_pregunta_momento_verdad1_idx` (`fkidCoustomer_journey_map` ASC),
  INDEX `fk_pregunta_giros_empresariales1_idx` (`fkidGirosEmpresariales` ASC),
  CONSTRAINT `fk_pregunta_momento_verdad1`
    FOREIGN KEY (`fkidCoustomer_journey_map`)
    REFERENCES `beedata`.`customer_journey_map` (`idCustomer_journey_map`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pregunta_giros_empresariales1`
    FOREIGN KEY (`fkidGirosEmpresariales`)
    REFERENCES `beedata`.`giros_empresariales` (`idGirosEmpresariales`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`respuestas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`respuestas` (
  `idRespuestas` INT NOT NULL AUTO_INCREMENT,
  `idGrupoRespuestas` INT(11) NOT NULL,
  `fkidArchivos` INT(11) NULL,
  `Respuesta` VARCHAR(45) NULL DEFAULT NULL,
  `Valor` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`idRespuestas`, `idGrupoRespuestas`),
  INDEX `fk_Respuestas_Archivos1_idx` (`fkidArchivos` ASC),
  CONSTRAINT `fk_Respuestas_Archivos1`
    FOREIGN KEY (`fkidArchivos`)
    REFERENCES `beedata`.`archivos` (`idArchivos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Respuestas_GrupoRespuestas1`
    FOREIGN KEY (`idGrupoRespuestas`)
    REFERENCES `beedata`.`grupo_respuestas` (`idGrupoRespuestas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`campaña_momento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`campaña_momento` (
  `idCampaña_momento` INT NOT NULL AUTO_INCREMENT,
  `fkidCampaña` INT(11) NOT NULL,
  `fkidCustomer_journey_map` INT NOT NULL,
  `fkidGrupoRespuestas` INT(11) NOT NULL,
  PRIMARY KEY (`idCampaña_momento`, `fkidCampaña`, `fkidCustomer_journey_map`, `fkidGrupoRespuestas`),
  INDEX `fk_campaña_momento_momento_verdad1_idx` (`fkidCustomer_journey_map` ASC),
  INDEX `fk_campaña_momento_grupo_respuestas1_idx` (`fkidGrupoRespuestas` ASC),
  CONSTRAINT `fk_campaña_momento_campaña1`
    FOREIGN KEY (`fkidCampaña`)
    REFERENCES `beedata`.`campaña` (`idCampaña`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_campaña_momento_momento_verdad1`
    FOREIGN KEY (`fkidCustomer_journey_map`)
    REFERENCES `beedata`.`customer_journey_map` (`idCustomer_journey_map`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_campaña_momento_grupo_respuestas1`
    FOREIGN KEY (`fkidGrupoRespuestas`)
    REFERENCES `beedata`.`grupo_respuestas` (`idGrupoRespuestas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `beedata`.`campaña_cjm_mv`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`campaña_cjm_mv` (
  `idCampaña_cjm_mv` INT NOT NULL AUTO_INCREMENT,
  `fkidCampaña_momento` INT NOT NULL,
  `fkidMomento_verdad` INT(11) NOT NULL,
  PRIMARY KEY (`idCampaña_cjm_mv`),
  INDEX `fk_campaña_cjm_mv_campaña_momento1_idx` (`fkidCampaña_momento` ASC),
  INDEX `fk_campaña_cjm_mv_momento_verdad1_idx` (`fkidMomento_verdad` ASC),
  CONSTRAINT `fk_campaña_cjm_mv_campaña_momento1`
    FOREIGN KEY (`fkidCampaña_momento`)
    REFERENCES `beedata`.`campaña_momento` (`idCampaña_momento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_campaña_cjm_mv_momento_verdad1`
    FOREIGN KEY (`fkidMomento_verdad`)
    REFERENCES `beedata`.`momento_verdad` (`idMomento_verdad`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `beedata`.`respuesta_cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`respuesta_cliente` (
  `idRespuestaCliente` INT(11) NOT NULL AUTO_INCREMENT,
  `fkidCliente` INT(11) NOT NULL,
  `fkidRespuestas` INT NOT NULL,
  `Fecha` DATE NULL DEFAULT NULL,
  `fkidCampaña_momento_pregunta` INT NOT NULL,
  PRIMARY KEY (`idRespuestaCliente`, `fkidCliente`, `fkidRespuestas`, `fkidCampaña_momento_pregunta`),
  INDEX `fk_RespuestaCliente_Cliente1_idx` (`fkidCliente` ASC),
  INDEX `fk_respuesta_cliente_respuestas1_idx` (`fkidRespuestas` ASC),
  INDEX `fk_respuesta_cliente_campaña_momento_pregunta1_idx` (`fkidCampaña_momento_pregunta` ASC),
  CONSTRAINT `fk_RespuestaCliente_Cliente1`
    FOREIGN KEY (`fkidCliente`)
    REFERENCES `beedata`.`cliente` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_respuesta_cliente_respuestas1`
    FOREIGN KEY (`fkidRespuestas`)
    REFERENCES `beedata`.`respuestas` (`idRespuestas`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_respuesta_cliente_campaña_momento_pregunta1`
    FOREIGN KEY (`fkidCampaña_momento_pregunta`)
    REFERENCES `beedata`.`campaña_cjm_mv` (`idCampaña_cjm_mv`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`semaforizacion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`semaforizacion` (
  `idSemaforo` INT(11) NOT NULL AUTO_INCREMENT,
  `Inicial` INT(11) NULL DEFAULT NULL,
  `Final` INT(11) NULL DEFAULT NULL,
  `Color` VARCHAR(7) NULL DEFAULT NULL,
  PRIMARY KEY (`idSemaforo`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`semaforizacion_cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`semaforizacion_cliente` (
  `idSemaforizacion` INT(11) NOT NULL AUTO_INCREMENT,
  `Inicial` INT(11) NULL DEFAULT NULL,
  `Final` INT(11) NULL DEFAULT NULL,
  `Color` VARCHAR(7) NULL DEFAULT NULL,
  `fkidUnidadNegocio` INT(11) NOT NULL,
  PRIMARY KEY (`idSemaforizacion`, `fkidUnidadNegocio`),
  INDEX `fk_SemaforizacionCliente_UnidadNegocio1_idx` (`fkidUnidadNegocio` ASC),
  CONSTRAINT `fk_SemaforizacionCliente_UnidadNegocio1`
    FOREIGN KEY (`fkidUnidadNegocio`)
    REFERENCES `beedata`.`unidad_negocio` (`idUnidadNegocio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`temas_a_mejora`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`temas_a_mejora` (
  `idPreguntaCapaña` INT(11) NOT NULL,
  `plan_accion_idPlanAccion` INT(11) NOT NULL,
  `fkidCampaña_momento_pregunta` INT NOT NULL,
  `fkidCustomer_journey_map` INT NOT NULL,
  INDEX `fk_plan_accion_has_RespuestaCliente_plan_accion1_idx` (`plan_accion_idPlanAccion` ASC),
  INDEX `fk_temas_a_mejora_preguntas_campaña1_idx` (`idPreguntaCapaña` ASC),
  INDEX `fk_temas_a_mejora_campaña_momento_pregunta1_idx` (`fkidCampaña_momento_pregunta` ASC),
  INDEX `fk_temas_a_mejora_momento_verdad1_idx` (`fkidCustomer_journey_map` ASC),
  CONSTRAINT `fk_plan_accion_has_RespuestaCliente_plan_accion1`
    FOREIGN KEY (`plan_accion_idPlanAccion`)
    REFERENCES `beedata`.`plan_accion` (`idPlanAccion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_temas_a_mejora_campaña_momento_pregunta1`
    FOREIGN KEY (`fkidCampaña_momento_pregunta`)
    REFERENCES `beedata`.`campaña_cjm_mv` (`idCampaña_cjm_mv`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_temas_a_mejora_momento_verdad1`
    FOREIGN KEY (`fkidCustomer_journey_map`)
    REFERENCES `beedata`.`customer_journey_map` (`idCustomer_journey_map`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`tarea`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`tarea` (
  `idTareas` INT(11) NOT NULL AUTO_INCREMENT,
  `fkidObjetivo` INT(11) NOT NULL,
  `fkidPreguntaCapaña` INT(11) NOT NULL,
  `Tarea` VARCHAR(100) NULL DEFAULT NULL,
  `FechaInicio` DATE NULL DEFAULT NULL,
  `FechaFin` DATE NULL DEFAULT NULL,
  `Responsable` VARCHAR(45) NULL DEFAULT NULL,
  `PorcentajeAvance` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`idTareas`, `fkidObjetivo`, `fkidPreguntaCapaña`),
  INDEX `fk_tarea_temas_a_mejora1_idx` (`fkidPreguntaCapaña` ASC),
  CONSTRAINT `fk_tarea_temas_a_mejora1`
    FOREIGN KEY (`fkidPreguntaCapaña`)
    REFERENCES `beedata`.`temas_a_mejora` (`idPreguntaCapaña`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`users_confirmations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`users_confirmations` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NOT NULL,
  `email` VARCHAR(249) NOT NULL,
  `selector` VARCHAR(16) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `selector` (`selector` ASC),
  INDEX `email_expires` (`email` ASC, `expires` ASC),
  INDEX `user_id` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`users_remembered`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`users_remembered` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` INT(10) UNSIGNED NOT NULL,
  `selector` VARCHAR(24) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `selector` (`selector` ASC),
  INDEX `user` (`user` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`users_resets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`users_resets` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` INT(10) UNSIGNED NOT NULL,
  `selector` VARCHAR(20) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `selector` (`selector` ASC),
  INDEX `user_expires` (`user` ASC, `expires` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `beedata`.`users_throttling`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`users_throttling` (
  `bucket` VARCHAR(44) NOT NULL,
  `tokens` FLOAT UNSIGNED NOT NULL,
  `replenished_at` INT(10) UNSIGNED NOT NULL,
  `expires_at` INT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`bucket`),
  INDEX `expires_at` (`expires_at` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

USE `beedata` ;

-- -----------------------------------------------------
-- Placeholder table for view `beedata`.`vcustomer_journey_map`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`vcustomer_journey_map` (`id` INT, `idG` INT, `NombreG` INT, `Nombre` INT);

-- -----------------------------------------------------
-- Placeholder table for view `beedata`.`vmomento_verdad`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`vmomento_verdad` (`id` INT, `idG` INT, `idCjm` INT, `NombreG` INT, `NombreCjm` INT, `Momento_verdad` INT);

-- -----------------------------------------------------
-- Placeholder table for view `beedata`.`vusuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `beedata`.`vusuario` (`id` INT, `email` INT, `Nombre` INT, `ApPaterno` INT, `ApMaterno` INT, `Empresa` INT, `Cargo` INT, `Telefono` INT, `url` INT);

-- -----------------------------------------------------
-- View `beedata`.`vcustomer_journey_map`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `beedata`.`vcustomer_journey_map`;
USE `beedata`;
CREATE  OR REPLACE VIEW `vcustomer_journey_map` AS
SELECT 
customer_journey_map.idCustomer_journey_map as id,
giros_empresariales.idGirosEmpresariales as idG,
giros_empresariales.Nombre as NombreG,
customer_journey_map.Nombre as Nombre
FROM customer_journey_map
INNER JOIN giros_empresariales ON idGirosEmpresariales = fkidGirosEmpresariales;

-- -----------------------------------------------------
-- View `beedata`.`vmomento_verdad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `beedata`.`vmomento_verdad`;
USE `beedata`;
CREATE  OR REPLACE VIEW `vmomento_verdad` AS
    SELECT 
        momento_verdad.idMomento_verdad AS id,
        giros_empresariales.idGirosEmpresariales AS idG,
        customer_journey_map.idCustomer_journey_map AS idCjm,
        giros_empresariales.Nombre AS NombreG,
        customer_journey_map.Nombre AS NombreCjm,
        momento_verdad.Momento_verdad
    FROM
        momento_verdad
            INNER JOIN
        customer_journey_map ON customer_journey_map.idCustomer_journey_map = momento_verdad.fkidCoustomer_journey_map
            INNER JOIN
        giros_empresariales ON giros_empresariales.idGirosEmpresariales = momento_verdad.fkidGirosEmpresariales;

-- -----------------------------------------------------
-- View `beedata`.`vusuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `beedata`.`vusuario`;
USE `beedata`;
CREATE  OR REPLACE VIEW `vusuario` AS
SELECT 
users.id,
users.email,
administrador.Nombre,
administrador.ApPaterno,
administrador.ApMaterno,
administrador.Empresa,
administrador.Cargo,
administrador.Telefono,
archivos.Direccion AS url
FROM users
INNER JOIN administrador ON administrador.fkusers_id = users.id 
INNER JOIN archivos ON archivos.idArchivos = administrador.fkidArchivos;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
