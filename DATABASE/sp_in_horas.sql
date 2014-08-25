DELIMITER $$

DROP PROCEDURE IF EXISTS sp_in_controlehoras $$

CREATE PROCEDURE sp_in_controlehoras(IN
	p_dsc_batida VARCHAR(100),
	p_dta_batida DATE,
	p_hra_batida TIME,
	p_tpo_batida CHAR(50)
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

	COMMIT;
END$$

DELIMITER ;