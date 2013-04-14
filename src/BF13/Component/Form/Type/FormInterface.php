<?php
namespace BF13\Component\Form\Type;

interface FormInterface
{
    public function buildFormMetaData($builder, $options);
    
    public function configure($builder, $options);
}