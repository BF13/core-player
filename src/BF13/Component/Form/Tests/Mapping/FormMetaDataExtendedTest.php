<?php
namespace BF13\Component\Form\Tests\Mapping;

use BF13\Component\Form\Loader\YamlFileLoader;

use BF13\Component\Form\Mapping\FormMetaData;

class FormMetaDataExtendedTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $file = __DIR__ . '/../fixtures/form_test.form.yml';

        $options = array();

        $options['fields']['field2']['choice']['label'] = 'myLabel';
        $options['fields']['field2']['choice']['choices'] = array('aa');

        $options['fields']['field3']['subform']['metadata']['fields']['format_numero']['value_list'] = array(
            'label' => 'add_demo',
            'source' => 'override_numero_source'
        );

        $loader = new YamlFileLoader($file);

        $this->metaForm = new FormMetaData(null, $options);

        $loader->loadFormMetadata($this->metaForm);
    }

    public function testUpdateFieldResponse()
    {
        $fields = $this->metaForm->getFields();

        $this->assertEquals('aa', $fields['field2']['choice']['choices'][0]);
    }

    public function testAddFieldResponse()
    {
        $fields = $this->metaForm->getFields();

        $this->assertEquals('myLabel', $fields['field2']['choice']['label']);
    }

    public function testUpdateSubFormFieldResponse()
    {
        $fields = $this->metaForm->getFields();

        $subfields = $fields['field3']->getFields();

        $this->assertEquals('override_numero_source', $subfields['format_numero']['value_list']['source']);
        $this->assertEquals(false, $subfields['format_numero']['value_list']['expanded']);
    }

    public function testAddSubFormFieldResponse()
    {
        $fields = $this->metaForm->getFields();

        $subfields = $fields['field3']->getFields();

        $this->assertEquals('add_demo', $subfields['format_numero']['value_list']['label']);
    }
}