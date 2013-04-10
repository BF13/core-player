<?php
namespace BF13\Component\Form;
use BF13\Component\Form\Exception\FormException;

/**
 *
 * Générateur de formulaire à partir d'un fichier de définition
 *
 * - support des sous formulaires
 *
 * @author FYAMANI
 *
 */
class FormGenerator
{
    private $formFactory;

    public function __construct($formFactory = null)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * Charge les métadonnées et les fournis au Builder
     *
     * @param unknown_type $file
     * @param unknown_type $format
     *
     * @return Symfony\Component\Form\FormBuilder
     */
    public function buildForm($file, $data = array(), $options = array(), $format = 'Yaml')
    {
        if (!$metaData = $this->loadMetaData($file, $format)) {

            throw new FormException('Métadonnées incorrecte !');
        }

        $type = new Type\Form($metaData);

        $form = $this->formFactory->create($type, $data, $metaData->getOptions());

        return $form;
    }

    protected function loadMetaData($file, $format)
    {
        $loader_class = sprintf('BF13\Component\Form\Loader\%sFileLoader', ucfirst(strtolower($format)));

        $formSettings = new $loader_class($file);

        $metaData = new Mapping\FormMetaData();

        if ($formSettings->loadFormMetaData($metaData)) {

            return $metaData;
        }

        return false;
    }
}
