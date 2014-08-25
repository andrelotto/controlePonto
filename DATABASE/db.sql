-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.5.15 - MySQL Community Server (GPL)
-- OS do Servidor:               Win32
-- HeidiSQL Versão:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura do banco de dados para horas
CREATE DATABASE IF NOT EXISTS `horas` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `horas`;


-- Copiando estrutura para tabela horas.controlehoras
CREATE TABLE IF NOT EXISTS `controlehoras` (
  `codAtiv` int(11) NOT NULL AUTO_INCREMENT,
  `descAtiv` varchar(100) DEFAULT NULL,
  `dataAtiv` date DEFAULT NULL,
  `horaAtiv` time DEFAULT NULL,
  `tipoAtiv` enum('Entrada','Saida') DEFAULT NULL,
  `batidaAtiv` int(11) DEFAULT NULL,
  `tempoAtiv` time DEFAULT NULL,
  PRIMARY KEY (`codAtiv`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para procedure horas.sp_in_controlehoras
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_in_controlehoras`(IN `p_dsc_batida` VARCHAR(100), IN `p_dta_batida` DATE, IN `p_hra_batida` TIME, IN `p_tpo_batida` CHAR(50)
)
BEGIN

	/**  
	* @brief INSERE REGISTRO DE HORAS
	* @author ANDRE LUIS OTTO
	* @date 21/08/2014
	* @param p_dsc_batida DESCRICAO
	* @param p_dta_batida DATA
	* @param p_hra_batida HORA
	* @param p_tpo_batida TIPO | ENTRADA OU SAIDA
	* @param p_btd_batida SEQUENCIA BATIDAS
	* @param p_tmp_batida TEMPO CALCULADO ENTRE ENTRADA E SAIDA
	* @version 1.0
	* @excessao SERA TRATADA VIA PHP
	*/
	DECLARE v_tmp_batida TIME DEFAULT (SELECT TIMEDIFF(p_hra_batida,horaAtiv) FROM controlehoras	WHERE	dataAtiv = p_dta_batida	ORDER BY 	batidaAtiv DESC		LIMIT 1) ;
	DECLARE v_btd_batida INT DEFAULT (SELECT batidaAtiv +1 FROM controlehoras WHERE dataAtiv = p_dta_batida	ORDER BY 	batidaAtiv DESC		LIMIT 1) ;

	IF  v_btd_batida is null THEN 
		SET v_btd_batida = 1;
	END IF;

	START TRANSACTION ;

	INSERT INTO controlehoras 
	(
		codAtiv,
		descAtiv,
		dataAtiv,
		horaAtiv,
		tipoAtiv,
		batidaAtiv,
		tempoAtiv
	)
	VALUES
	(
		NULL,
		p_dsc_batida,
		p_dta_batida,
		p_hra_batida,
		p_tpo_batida,
		v_btd_batida,
		v_tmp_batida	
	);
END//
DELIMITER ;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
