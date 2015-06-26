<?php
namespace BF13\Bundle\BusinessApplicationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;

use Symfony\Component\Yaml\Yaml;

use Symfony\Component\Finder\Finder;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadValueList extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    protected $manager;

    protected $key = array();

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->loadSource(__DIR__ . '/LabelList/%s.fixture.yml');

        $this->loadSource(__DIR__ . '/ValueList/%s.fixture.yml');
    }

    public function loadSource($path_pattern)
    {
        $files = array('list', 'value');

        foreach ($files as $filename) {

            $file = sprintf($path_pattern, $filename);

            $this->generateEntities($filename, $file);
        }
    }

    protected function generateEntities($filename, $file)
    {
        $data = file_get_contents($file);

        $data = Yaml::parse($data);

        if(! is_array($data)) {

            return;
        }

        foreach ($data as $entity_name => $inputs) {

            $associationNames = $this->manager->getClassMetadata($entity_name)->getAssociationNames();

            if(! sizeOf($inputs)) {

                continue;
            }

            foreach ($inputs as $key => $properties) {

                $entity = new $entity_name;

                foreach ($properties as $property => $value) {

                    $setter = sprintf('set%s', $this->camelize($property));

                    if (in_array($property, $associationNames)) {

                        $value = $this->key[$value];
                    }

                    $entity->$setter($value);
                }

                $this->manager->persist($entity);

                $this->key[$key] = $entity;
            }
        }

        $this->manager->flush();
    }

    protected function camelize($property)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match)
        {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $property);
    }

    public function getOrder()
    {
        return 1;
    }
}
