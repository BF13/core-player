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
    protected $formFactory;
    
    protected $loader_class;

    public function __construct($formFactory = null, $loader_class)
    {
        $this->formFactory = $formFactory;
        
        $this->loader_class = $loader_class;
    }

    /**
     * Charge les métadonnées et les passe au formulaire
     *
     * @param unknown_type $file
     * @param unknown_type $format
     *
     * @return Symfony\Component\Form\FormBuilder
     */
    public function buildForm($file, $data = array(), $options = array())
    {
        if (!$metaData = $this->loadMetaData($file)) {

            throw new FormException('Métadonnées incorrecte !');
        }

        $type = new Type\Form($metaData);
        
        $options = array_merge($metaData->getOptions(), $options);

        $form = $this->formFactory->create($type, $data, $metaData->getOptions());

        return $form;
    }

    protected function loadMetaData($file)
    {
        $metaData = new Mapping\FormMetaData();

        $loader_class = $this->loader_class;

        $loader = new $loader_class($file);

        if ($loader->loadFormMetaData($metaData)) {

            return $metaData;
        }

        return false;
    }
}
