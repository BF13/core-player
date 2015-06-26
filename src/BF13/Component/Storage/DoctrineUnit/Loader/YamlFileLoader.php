<?php
namespace BF13\Component\Storage\DoctrineUnit\Loader;

use Symfony\Component\Yaml\Yaml;

use BF13\Component\Storage\Exception\StorageException;
use BF13\Component\Storage\DoctrineUnit\Schema;

/**
 * @author FYAMANI
 *
 */
class YamlFileLoader implements LoaderInterface
{
    protected $schema;

    protected $source;

    public function __construct($source)
    {
        $data = file_get_contents($source);

        $this->source = Yaml::parse($data);
    }

    public function loadSchemaData(Schema $schema)
    {
        $schema_name = key($this->source);

        $schema_data = current($this->source);

        $this->validSchema($schema_data);

        $schema->setName($schema_name);

        $schema->setAlias($schema_data['alias']);

        $schema->setProperties($schema_data['properties']);

        $schema->setConditions($schema_data['conditions']);
    }

    protected function validSchema($schema)
    {
        if (!array_key_exists('alias', $schema)) {

            $msg = 'alias field is required !';

            throw new StorageException($msg);
        }

        if (!array_key_exists('properties', $schema)) {

            $msg = 'properties field is required !';

            throw new StorageException($msg);
        }

        if (!array_key_exists('conditions', $schema)) {

            $msg = 'conditions field is required !';

            throw new StorageException($msg);
        }
    }
}
