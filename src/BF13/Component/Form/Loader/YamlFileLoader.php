<?php
namespace BF13\Component\Form\Loader;

use BF13\Component\Form\Mapping\FormMetaData;
use BF13\Component\Form\Exception\FormException;
use Symfony\Component\Yaml\Yaml;

class YamlFileLoader implements LoaderInterface
{

    protected $file;

    public function __construct($file)
    {
        if (! is_file($file)) {

            throw new FormException(sprintf('Fichier "%s" introuvable !', $file));
        }

        $this->file = $file;
    }

    public function loadFormMetadata(FormMetaData $formMetaData)
    {
        try {

            $data = file_get_contents($this->file);

            $data = Yaml::parse($data);

        } catch (\Exception $e) {

            throw new FormException(sprintf('Ficher "%s" illisible !', $this->file));
        }

        $this->setMetaData($formMetaData, $data['metadata']);

        return true;
    }

    protected function setMetaData($formMetaData, $data)
    {
        $data = $this->checkInheritance($data);

        $formMetaData->configure($data);
    }

    /**
     * héritage de formulaire
     *
     * @param unknown_type $value
     */
    protected function checkInheritance($data)
    {
        if (! array_key_exists('extends', $data)) {

            return $data;
        }

        $parent_file = dirname($this->file) . '/' . $data['extends'];

        $data = file_get_contents($parent_file);

        $parent_values = Yaml::parse($data);

        $parent_values = $parent_values['metadata'];

        foreach ($parent_values['fields'] as $key => $attributs) {

            foreach ($attributs['widget'] as $attr_name => $attr_value) {

                if (! array_key_exists($key, $data['fields'])) {

                    continue;
                }

                if (array_key_exists($attr_name, $data['fields'][$key]['widget'])) {

                    unset($parent_values['fields'][$key]['widget'][$attr_name]);
                }
            }
        }

        $values_fields = array_merge_recursive($parent_values['fields'], $data['fields']);

        $data['fields'] = $values_fields;

        if (array_key_exists('subforms', $data)) {

            foreach ($parent_values['subforms'] as $key => $subform) {
                if (array_key_exists($key, $data['subforms'])) {

                    unset($parent_values['subforms'][$key]);
                }
            }

            $values_subforms = array_merge_recursive($parent_values['subforms'], $data['subforms']);

            $data['subforms'] = $values_subforms;
        } else {

            $data['subforms'] = array_key_exists('subforms', $parent_values) ? $parent_values['subforms'] : array();
        }

        unset($data['extends']);

        return $data;
    }
}
