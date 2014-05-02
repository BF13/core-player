<?php
namespace BF13\Component\Form\Mapping;

/**
 * Métadonnées d'un formulaire
 *
 * @author bitnami
 *
 */
class FormMetaData
{

    protected $name;

    protected $multiple;

    protected $options = array();

    protected $extended_metadata = array();

    protected $dataTransformer;

    protected $dataClass;

    protected $fields = array();

    protected $validators = array();

    protected $subForms = array();

    public function __construct($name = null, $extended_metadata = array())
    {
        if (! is_null($name)) {
            $this->setName($name);
        }

        $this->extended_metadata = $extended_metadata;
    }

    public function configure($metadata = array())
    {
        if (array_key_exists('name', $metadata)) {

            $this->setName($metadata['name']);
        }

        if (array_key_exists('multiple', $metadata)) {

            $this->setMultiple($metadata['multiple']);
        }

        if (array_key_exists('options', $metadata)) {

            $this->setOptions($metadata['options']);

            if (array_key_exists('data_class', $metadata['options'])) {

                $this->setDataClass($metadata['options']['data_class']);
            }
        }

        if (array_key_exists('fields', $metadata)) {

            $fields = array();

            foreach ($metadata['fields'] as $field => $meta_field) {

                if (! array_key_exists('widget', $meta_field)) {

                    continue;
                }

                switch(key($meta_field['widget']))
                {
                	case 'subform':

                	    $f = new self($field);

                	    $f->configure($meta_field['widget']['subform']);

                	    $fields[$field] = $f;

                	    break;

                	default:

                        $fields[$field] = $meta_field['widget'];
                }

            }

            $this->setFields($fields);
        }

        if (array_key_exists('subforms', $metadata)) {

            $subforms = $metadata['subforms'];

            $this->setSubForms($subforms);
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getDataTransformer()
    {
        return $this->dataTransformer;
    }

    public function setDataTransformer($dataTransformer)
    {
        $this->dataTransformer = $dataTransformer;
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        if (array_key_exists('fields', $this->extended_metadata)) {

            $fields = array_merge_recursive($fields, $this->extended_metadata['fields']);
        }

        $this->fields = $fields;

        return $this;
    }

    public function getValidators()
    {
        return $this->validators;
    }

    public function setValidators($validators)
    {
        $this->validators = $validators;
        return $this;
    }

    public function getSubForms()
    {
        return $this->subForms;
    }

    public function setSubForms($source)
    {
        $this->subForms = array();

        foreach ($source as $key => $metadata) {

            $ext_metadata = array();

            if (array_key_exists('subforms', $this->extended_metadata) && array_key_exists($key, $this->extended_metadata['subforms'])) {

                $ext_metadata = $this->extended_metadata['subforms'][$key];
            }

            $this->subForms[$key] = new self($key, $ext_metadata);

            $this->subForms[$key]->configure($metadata);
        }

        return $this;
    }

    public function getDataClass()
    {
        return $this->dataClass;
    }

    public function setDataClass($dataClass)
    {
        $this->dataClass = $dataClass;
        return $this;
    }

    public function getMultiple()
    {
        return $this->multiple;
    }

    public function isMultiple()
    {
        return true == $this->multiple;
    }

    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
        return $this;
    }
}
