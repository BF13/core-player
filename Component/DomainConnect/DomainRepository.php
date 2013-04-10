<?php
namespace BF13\Component\DomainConnect;

use BF13\Component\DomainConnect\EntityQuerizer;


/**
 * Reader Service
 *
 * @author FYAMANI
 *
 */
use Symfony\Component\Yaml\Yaml;

class DomainRepository
{
    protected $_em;

    protected $_bundle;

    public function __construct(\Doctrine\ORM\EntityManager $em = null, $kernel)
    {
        $this->_em = $em;

        $this->kernel = $kernel;
    }

    public function getQuerizer($fqdn)
    {
        $repository = $this->_em->getRepository($fqdn);

//         $class = get_class($repository);

//         if(! $repository instanceOf BF13\Component\DomainRepository\DomainEntityRepository)
//         {
//              throw new \Exception(sprintf('Le type de depot "%s" est incorrecte !', $class));
//         }

        if($path = $this->getSchemePath($fqdn)) {

            $scheme = Yaml::parse($path);

            $scheme = current($scheme);

            $repository->initDomainScheme($fqdn, $scheme);
        }

        $Querizer = new EntityQuerizer($repository);

        return $Querizer;
    }

    public function retrieve($fqdn, $discriminator)
    {
        if(is_array($discriminator)) {

            $Item = $this->_em->getRepository($fqdn)->findOneBy($discriminator);

        } else {

            $Item = $this->_em->getRepository($fqdn)->find($discriminator);
        }

        if (!$Item) {
            throw  new \Exception(
                            sprintf(
                                    'Unable to find "%s" entity "%s".',
                                    $fqdn, $discriminator));
        }

        return $Item;
    }

    public function retrieveNew($fqdn, \Closure $fn = null)
    {
        $class_name = $this->_em->getRepository($fqdn)->getClassName();

        $Item = new $class_name;

        if($fn)
        {
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

    public function getSchemePath($model)
    {
        list($bundle, $file) = explode(':', $model);

        $form_file = sprintf('@%s/Resources/config/doctrine/%s.dql.yml', $bundle, $file);

        $path = $this->kernel->locateResource($form_file);

        return $path;
    }
}
