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
        if($extended = $formMetaData->getExtended())
        {
            $data['extends'] = $extended;
        }

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

        if(! file_exists($parent_file))
        {
            throw new \Exception(sprintf('File "%s" not found !', $parent_file));
        }

        $parentdata = file_get_contents($parent_file);

        $parent_values = Yaml::parse($parentdata);

        $parent_values = $parent_values['metadata'];

        foreach ($parent_values['fields'] as $key => $field) {

            foreach ($field['widget'] as $attr_name => $attr_value) {

                if (! array_key_exists($key, $data['fields'])) {

                    continue;
                }

                if ('subform' === $attr_name) {

                    $field['widget']['subform'] = $this->mergeSubformData($data['fields'][$key]['widget']['subform'], $field['widget']['subform']);

                    continue;
                }

                if (array_key_exists($attr_name, $data['fields'][$key]['widget'])) {

                    unset($parent_values['fields'][$key]['widget'][$attr_name]);
                }
            }

            $data['fields'][$key] = $field;
        }

        unset($data['extends']);

        return $data;

        $values_fields = array_merge_recursive($parent_values['fields'], $data['fields']);

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

    protected function mergeSubformData($data, $parentfield)
    {
        foreach ($parentfield['metadata']['fields'] as $key => $property) {

            if(! isset($data['metadata']['fields'][$key]))
            {
                $data['metadata']['fields'][$key] = $property;

                continue;
            }

            foreach ($property['widget'] as $p => $v) {

                $data['metadata']['fields'][$key]['widget'][$p] = array_merge($v, $data['metadata']['fields'][$key]['widget'][$p]);
            }
        }

        return $data;
    }
}
