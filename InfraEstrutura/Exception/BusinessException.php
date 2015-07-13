<?php

namespace InfraEstrutura\Exception;

/**
 * Pacote de Exceções
 *
 * @package InfraEstrutura\Exception
 */
class BusinessException extends \Exception {

	/**
	 * * Inicialização dos paramentos da Exception
	 * @param $message 
	 * @param $code    
	 * @param $previous
	 */
    public function __construct($message, $code = NULL, $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Print da classe
     * @return string
     */
    public function __toString() {
        return parent::__toString();
    }
}
