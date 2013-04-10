<?php
namespace BF13\Component\Form\Loader;

use BF13\Component\Form\Mapping\FormMetaData;

interface LoaderInterface
{
    public function loadFormMetadata(FormMetaData $formMetaData);
}