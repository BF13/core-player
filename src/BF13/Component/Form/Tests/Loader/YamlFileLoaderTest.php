<?php
namespace BF13\Component\Form\Tests\Loader;

use BF13\Component\Form\Loader\LoaderInterface;

use BF13\Component\Form\Loader\YamlFileLoader;

use BF13\Component\Form\Mapping\FormMetaData;

use BF13\Component\Form\Exception\FormException;

class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessLoader()
    {
        $file = __DIR__ . '/../fixtures/form_test.form.yml';

        $loader = new YamlFileLoader($file);

        $this->assertTrue($loader instanceOf LoaderInterface);
    }

    public function testFileNotFoundLoader()
    {
        $this->setExpectedException('BF13\Component\Form\Exception\FormException');

        $file = __DIR__ . '/../Fixtures/no_existing_form_test.form.yml';

        $loader = new YamlFileLoader($file);
    }

    public function testFileStructureLoaded()
    {
        $this->setExpectedException('BF13\Component\Form\Exception\FormException');

        $file = __FILE__;

        $loader = new YamlFileLoader($file);

        $metaData = new FormMetaData();

        $loader->loadFormMetaData($metaData);
    }

    public function setUp()
    {
//         $formFactoryClass = 'Symfony\Component\Form\FormFactory';
//         $formRegistryClass = 'Symfony\Component\Form\FormRegistryInterface';
//         $kernel = 'Symfony\Component\HttpKernel\Kernel';
//         $em = 'Doctrine\ORM\EntityManager';

// //         $formFactory = $this->getMockBuilder($formFactoryClass)
// //             ->disableOriginalConstructor()
//         $formRegistry = $this
//             ->getMock($formRegistryClass);

//         $formFactory = $this
//             ->getMock($formFactoryClass, array($formRegistry))
//             ->disableOriginalConstructor()
//             ->expects($this->any())
//             ->method('add')
//             ->will($this->returnValue('toto'));


//         $this->generator = new FormGenerator($formFactory);
    }

    public function tearDown()
    {
    }
}