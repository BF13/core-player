<?php
namespace BF13\Component\Form;

use BF13\Component\Form\Exception\FormException;
use Symfony\Component\Form\FormFactory;

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

    public function __construct(FormFactory $formFactory = null, $loader_class)
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
        $opt = array_key_exists('metadata', $options) ? $options['metadata'] : array();
        
        unset($options['metadata']);
        
        if (!$metaData = $this->loadMetaData($file, $opt)) {

            throw new FormException('Métadonnées incorrecte !');
        }
        
        //$subforms = $metaData->getSubForms();

        $type = new Type\Form($metaData);

        $form = $this->formFactory->create($type, $data, $options);

        return $form;
    }

    protected function loadMetaData($file, $options = array())
    {
        $metaData = new Mapping\FormMetaData(null, $options);

        $loader_class = $this->loader_class;

        $loader = new $loader_class($file);

        if ($loader->loadFormMetaData($metaData)) {

            return $metaData;
        }

        return false;
    }
}
