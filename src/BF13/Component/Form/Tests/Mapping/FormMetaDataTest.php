<?php
namespace BF13\Component\Form\Tests\Mapping;

use BF13\Component\Form\Loader\YamlFileLoader;

use BF13\Component\Form\Mapping\FormMetaData;

class FormMetaDataTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $file = __DIR__ . '/../fixtures/form_test.form.yml';

        $loader = new YamlFileLoader($file);

        $this->metaForm = new FormMetaData();

        $loader->loadFormMetadata($this->metaForm);
    }

    public function testFieldStructure()
    {
        $this->assertEquals("demo", $this->metaForm->getName());
        $this->assertEquals(0, sizeOf($this->metaForm->getOptions()));
        $this->assertEquals(3, sizeOf($this->metaForm->getFields()));
    }
}