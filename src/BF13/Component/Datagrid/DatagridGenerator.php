<?php
namespace BF13\Component\Datagrid;

use Symfony\Component\Yaml\Yaml;

use Symfony\Component\Routing\Loader\YamlFileLoader;

use BF13\Component\Datagrid\datagridContainer;


/**
 * Service de chargement d'une grille de données
 *
 * @author FYAMANI
 *
 */
class DatagridGenerator
{
    protected $DomainRepository;

    public function __construct($conn, $kernel)
    {
        $this->DomainRepository = $conn;

        $this->kernel = $kernel;
    }

    /**
     * Construire une grille de données
     *
     * @param unknown_type $model
     * @param unknown_type $data
     * @return \BF13\Component\Datagrid\DatagridEntity
     */
    public function buildDatagrid($model)
    {
        $path = $this->getDatagridPath($model);

        $defaultConfig = Yaml::parse($path);

        list($bundle, $class) = explode(':', $model);

        $ns = $this->getNamespaceBundle($bundle);

        $class = sprintf('%s\Datagrid\%s', $ns, $class);

        if(class_exists($class)) {

            $Grid = new $class($defaultConfig);

        } else {

            $Grid = new datagridContainer($defaultConfig);
        }

        $type = array_key_exists('type', $defaultConfig['settings']) ? $defaultConfig['settings']['type'] : 'entity';

        $datagrid_model = sprintf('BF13\Component\Datagrid\Model\Datagrid%s', ucfirst($type));

        switch($type)
        {
            case 'object':
            case 'entity':
                break;

            default:

                if(!class_exists($type))
                {
                    throw new \Exception(sprintf('Unknow datagrid type "%s"', $type));
                }

                $datagrid_model = $type;
        }

        $datagrid = new $datagrid_model($Grid, $this->kernel);

        return $datagrid;
    }

    protected function getDatagridPath($model)
    {
        list($bundle, $file) = explode(':', $model);

        $form_file = sprintf('@%s/Resources/config/datagrid/%s.datagrid.yml', $bundle, $file);

        $path = $this->kernel->locateResource($form_file);

        return $path;
    }

    protected function getNamespaceBundle($bundle)
    {
        return $this->kernel->getBundle($bundle)->getNamespace();
    }
}
