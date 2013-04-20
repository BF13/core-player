<?php
namespace BF13\Component\DomainConnect\Doctrine;

use Symfony\Component\Yaml\Yaml;

use BF13\Component\DomainConnect\DomainManagerInterface;

/**
 * Reader Service
 *
 * @author FYAMANI
 *
 */
class DoctrineManager implements DomainManagerInterface
{
    protected $_em;

    public function __construct(\Doctrine\ORM\EntityManager $em = null)
    {
        $this->_em = $em;
    }

    public function getRepository($fqdn)
    {
        return $this->_em->getRepository($fqdn);
    }

    public function retrieve($fqdn, $discriminator)
    {
        if (is_array($discriminator)) {

            $Item = $this->getRepository($fqdn)->findOneBy($discriminator);

        } else {

            $Item = $this->getRepository($fqdn)->find($discriminator);
        }

        if (!$Item) {
            throw new \Exception(sprintf('Unable to find "%s" entity "%s".', $fqdn, $discriminator));
        }

        return $Item;
    }

    public function retrieveNew($fqdn, \Closure $fn = null)
    {
        $class_name = $this->getRepository($fqdn)->getClassName();

        $Item = new $class_name;

        if ($fn) {
            $fn($Item);
        }

        return $Item;
    }

    public function make($factory)
    {
        $factory = new $factory;

        $item = $factory->make();

        return $item;
    }

    public function delete($fqdn, $id)
    {
        $entity = $this->retrieve($fqdn, $id);

        $this->_em->remove($entity);

        $this->_em->flush();
    }

    public function store($Entity)
    {
        $this->_em->persist($Entity);

        $this->_em->flush();
    }
}
