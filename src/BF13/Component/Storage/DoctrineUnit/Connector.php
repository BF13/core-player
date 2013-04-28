<?php
namespace BF13\Component\Storage\DoctrineUnit;

use BF13\Component\Storage\StorageConnectorInterface;
use Doctrine\ORM\EntityManager;
use BF13\Component\Storage\Exception\StorageException;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

/**
 * @author FYAMANI
 *
 */
class Connector implements StorageConnectorInterface
{
    protected $em;

    /**
     * 
     * @todo delete kernel dependency
     * 
     * @param EntityManager $em
     * @param Kernel $kernel
     */
    public function __construct(EntityManager $em, Kernel $kernel)
    {
        $this->em = $em;

        $this->kernel = $kernel;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageConnectorInterface::getHandler()
     */
    public function getHandler($fqcn)
    {
        $repository = $this->em->getRepository($fqcn);

        $handler = new Handler($repository);

        return $handler;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageConnectorInterface::getQuerizer()
     */
    public function getQuerizer($serialname)
    {
        $builder = $this->em->createQueryBuilder();

        $repository = $this->em->getRepository($serialname);

        $querizer = new Querizer($repository, $builder);

        $schema = $this->getSchemaPath($serialname);

        $querizer->load($schema);

        return $querizer;
    }

    protected function getSchemaPath($serialname, $pattern = '@%s/Resources/config/doctrine/%s.dql.yml')
    {
        list($bundle, $file) = explode(':', $serialname);

        $form_file = sprintf($pattern, $bundle, $file);

        if (!$path = $this->kernel->locateResource($form_file)) {

            throw new StorageException('Schema not found !');
        }

        $schema = Yaml::parse($path);

        return $schema;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageRepositoryInterface::store()
     */
    public function store($item)
    {
        $this->em->persist($item);

        $this->em->flush();
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageRepositoryInterface::delete()
     */
    public function delete($item)
    {
        $this->em->remove($item);

        $this->em->flush();
    }
}
