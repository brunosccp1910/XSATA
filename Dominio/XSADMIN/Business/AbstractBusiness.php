<?php

namespace XSADMIN\Business;

use InfraEstrutura\Exception\DAOException;

/**
 * Absctract Business
 *
 * @package XSADMIN\Business
 */
abstract class AbstractBusiness {

    protected $entityBusiness;
    protected $dao;

    function getEntityBusiness() {
        return $this->entityBusiness;
    }

    function getDAO() {
        return $this->dao;
    }

    function setEntityBusiness($entityBusiness) {
        $this->entityBusiness = $entityBusiness;
    }

    function setDAO($dao) {
        $this->dao = $dao;
    }

    /**
     * Exclui um objeto
     * 
     * @param  $object
     * @return boolean
     * @throws BusinessException
     */
    public function delete($object) {
        $object = $this->findById($object->getId());
        try {
            $this->dao->delete($object);
        } catch (DAOException $ex) {
            throw new \Exception($ex->getMessage());
        }

        return TRUE;
    }

    /**
     * Retorna todos
     * @return ArrayCollection
     * @throws BusinessException
     */
    public function findAll() {

        try {
            $retorno = $this->dao->findAll(get_class($this->entityBusiness));
        } catch (DAOException $ex) {
            throw new \Exception($ex->getMessage());
        }

        return $retorno;
    }

    /**
     * Retorna uma Objeto por um ID especifico
     * @param Integer $id
     * @return Object
     * @throws BusinessException
     */
    public function findById($id) {

        if (!is_numeric($id)) {
            throw new \BusinessException("Id deve ser numérico!");
        }

        try {
            $retorno = $this->dao->findById(get_class($this->entityBusiness), $id);
        } catch (DAOException $exc) {
            throw new \Exception($exc->getMessage());
        }

        return $retorno;
    }
    
    /**
     * Retorna uma Objeto por parâmetro
     * @param Integer $id
     * @return Object
     * @throws BusinessException
     */
    public function findOneBy($params) {
        try {
            $retorno = $this->dao->findOneBy(get_class($this->entityBusiness), $params);
        } catch (DAOException $exc) {
            throw new \Exception($exc->getMessage());
        }
        return $retorno;
    }

    /**
     * Salva um Objeto
     * @param Object
     * @return boolean
     * @throws BusinessException
     */
    public function save($object) {
        $object = $this->attachEntity($object);
        $this->validarObjeto($object);

        try {
            $this->dao->save($object);
        } catch (DAOException $exc) {
            throw new \Exception($exc->getMessage());
        }
        return $object;
    }

    /**
     * Atualiza um Objeto
     * @param CategoriaCurso $object
     * @return boolean
     * @throws BusinessException
     */
    public function update($object) {
        $object = $this->attachEntity($object);
        if (!is_numeric($object->getId())) {
            throw new \Exception("Id deve ser numérico!");
        }

        $this->validarObjeto($object);

        try {
           return  $this->dao->update($object);
        } catch (DAOException $exc) {
            throw new \Exception($exc->getMessage());
        }
    }
    
    /**
     * Função utilizada para testes
     */
    public function findLast(){
        $last = NULL;
        $arrayObjects = $this->findAll();
        
        if($arrayObjects != NULL){
            $last = end($arrayObjects);
        }
        return $last;
        
    }

    /**
     * ValidaObjeto
     */
    abstract function validarObjeto($object);

    /**
     * Sicroniza com o EntityFramework
     */
    abstract function attachEntity($object);
}
