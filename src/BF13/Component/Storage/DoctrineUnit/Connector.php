<?php
namespace BF13\Component\Storage\DoctrineUnit;

use BF13\Component\Storage\StorageConnectorInterface;
use Doctrine\ORM\EntityManager;
use BF13\Component\Storage\Exception\StorageException;
use Symfony\Component\HttpKernel\Kernel;
use BF13\Component\Storage\DoctrineUnit\Loader\YamlFileLoader;

/**
 * @author FYAMANI
 *
 */
class Connector implements StorageConnectorInterface
{
    protected $em;

    /**
     *
     * @param EntityManager $em
     * @param Kernel $kernel
     */
    public function __construct(EntityManager $em, Kernel $kernel, $class_inspector)
    {
        $this->em = $em;

        $this->kernel = $kernel;

        if( is_string($class_inspector))
        {
            $this->Inspector = new $class_inspector;

        } else {

            $this->Inspector = $class_inspector;
        }
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

        $schema = $this->getSchema($serialname, $repository);

        $querizer->setSchema($schema);

        return $querizer;
    }

    protected function getSchema($serialname, $repository, $pattern = '@%s/Resources/config/doctrine/%s.dql.yml')
    {
        list($bundle, $file) = explode(':', $serialname);

        $schema = new Schema();

        $settings_file = sprintf($pattern, $bundle, $file);

        try {

            $path = $this->kernel->locateResource($settings_file);

            $loader = new YamlFileLoader($path);

            $loader->loadSchemaData($schema);

        } catch (\Exception $e) {

            $Inspected = $this->Inspector->inspect($repository->getClassName());

            $namespace = $repository->getClassName();

            $section = explode('\\', $namespace);

            $alias = strtolower(substr(end($section), 0, 1));

            $properties = array();

            foreach($Inspected->getProperties() as $p)
            {
                $properties[$p->getName()] = array(
                    'field' => $alias . '.' . $p->getName()
                );
            }

            $schema->setName($namespace);

            $schema->setAlias($alias);

            $schema->setProperties($properties);

            $schema->setConditions(array());
        }

        return $schema;
    }

    /**
     * (non-PHPdoc)
     * @see \BF13\Component\Storage\StorageRepositoryInterface::store()
     */
    public function store($item, $autoflush  = true)
    {
        $this->em->persist($item);

        if($autoflush)
        {
            $this->flush();
        }
    }

    public function flush($clear = false)
    {
        $this->em->flush();

        if($clear)
        {
            $this->em->clear();
        }
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
