<?php
namespace BF13\Component\DomainConnect;

/**
 * 
 * @author FYAMANI
 *
 */
interface DomainManagerInterface
{
    public function retrieve($fqdn, $discriminator);
    
    public function retrieveNew($fqdn, \Closure $fn = null);
    
    public function delete($fqdn, $id);
    
    public function store($entity);
}