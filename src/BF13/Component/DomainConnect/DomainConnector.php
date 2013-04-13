<?php
namespace BF13\Component\DomainConnect;

use Symfony\Component\Yaml\Yaml;
use BF13\Component\DomainConnect\Doctrine\DomainQuery;

class DomainConnector
{
    protected $manager;
    
    protected $kernel;

    public function __construct(DomainManagerInterface $manager, $kernel = null)
    {
        $this->manager = $manager;

        $this->kernel = $kernel;
    }

    public function getManager()
    {
        return $this->manager;
    }

    public function getQuerizer($serialname)
    {
        $repository = $this->manager->getRepository($serialname);

        if ($scheme = $this->getSchemePath($serialname)) {

            $repository->initDomainScheme($scheme);
        }

        return new DomainQuery($repository, $scheme);
    }

    public function getSchemePath($serialname, $pattern = '@%s/Resources/config/doctrine/%s.dql.yml')
    {
        list($bundle, $file) = explode(':', $serialname);

        $form_file = sprintf($pattern, $bundle, $file);

        if(! $path = $this->kernel->locateResource($form_file))
        {
            return;
        }

        $scheme = Yaml::parse($path);

        if (is_array($scheme)) {
            
            return $scheme = current($scheme);
        }
    }
}
