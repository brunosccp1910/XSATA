<?php


namespace XSADMIN\Facade;

use XSADMIN\InfraDatabase\GerenciadorConexao;
use XSADMIN\InfraDatabase\ArrayDatabaseConfig;
use InfraEstrutura\Exception\InfraEstruturaException;
use InfraEstrutura\Exception\FacadeException;
use InfraEstrutura\Exception\BusinessException;
use InfraEstrutura\Exception\MethodNotImplementException;

/**
 * Camada Facade
 *
 * @package SigUSeguranca\DAO
 */
abstract class AbstractFacade {
    
    /**
     * objeto do negocio
     * @var $negocio
     */
    private $business;

    /**
     * gerenciador de conexao
     * @var $gerenciadorConexao
     */
    private $gerenciadorConexao;
    
    /**
     * Construtor - instancia os objetos que serão usados internamente     
     */
    public function __construct() {
        $this->gerenciadorConexao = new GerenciadorConexao();
    } 
    
    /**
     * Chama uma função $name de um objeto $negocio (função setNegocio) passando $arguments
     * @param  $name nome da função
     * @param  $arguments argumentos da função
     * @return retorno da função $name chamada
     */
    public function __call($name, $arguments) {
        if (!method_exists($this->business, $name)) {
            throw new MethodNotImplementException('O método solicitado ao negócio não foi implementado.');
        }
                
        try {
            $this->gerenciadorConexao->abrirConexao(ArrayDatabaseConfig::obterDatabaseConfig());
        } catch (InfraEstruturaException $ex) {
            throw new FacadeException($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }

        try {
            $parameters = (count($arguments) == 0 ? NULL : $arguments);
            
            $this->gerenciadorConexao->abrirTransacao();

            $retorno = call_user_func_array(array($this->business, $name), $parameters);

            $this->gerenciadorConexao->comitarTransacao();
        } catch (InfraEstruturaException $ex) {
            $this->gerenciadorConexao->rollbackTransacao();
            throw new FacadeException($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        } catch (BusinessException $ex) {
            $this->gerenciadorConexao->rollbackTransacao();
            throw new FacadeException($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }

        $this->gerenciadorConexao->fecharConexao();

        return $retorno;
    }
    
    /**
     * Define o negocio para toda classe
     * @param $negocio object
     */
    public function setBusiness($business) {
        $this->business = $business;
    }
    
}
