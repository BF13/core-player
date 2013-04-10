<?php
namespace BF13\Component\Form\Mapping;
use Symfony\Component\Form\FormView;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Yaml\Parser;

/**
 * Métadonnées d'un formulaire
 * @author bitnami
 *
 */
class FormMetaData
{
    protected $name;

    protected $multiple;

    protected $options = array();

    protected $dataTransformer;

    protected $dataClass;

    protected $fields = array();

    protected $validators = array();

    protected $subForms = array();

    public function __construct($name = null)
    {
        if (!is_null($name)) {
            $this->setName($name);
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

    public function setSubForms($subForms)
    {
        $this->subForms = $subForms;
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
