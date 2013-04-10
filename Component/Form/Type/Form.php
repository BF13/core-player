<?php
namespace BF13\Component\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use BF13\Component\Form\Mapping\FormMetaData;

/**
 * Construction du formulaire à partir des métadonnées
 *
 * @author FYAMANI
 *
 */
class Form extends AbstractType
{
    protected $metaData;

    public function __construct(FormMetaData $metadata = null)
    {
        $this->metaData = $metadata;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->metaData)
        {
            $this->makeFromMetaData($builder, $options);
        }

        $this->configure($builder, $options);
    }

    public function configure($builder, $options) {}

    public function getName()
    {
        return $this->metaData->getName();
    }

    protected function makeFromMetaData($builder, $options)
    {
        $this->addFields($builder, $options);

        $this->addEmbeddedForms($builder, $options);
    }

    protected function addFields($builder, $options)
    {
        foreach ($this->metaData->getFields() as $fieldname => $params) {

            foreach ($params as $type => $fieldOptions) {

                if (array_key_exists('disabled', $options)) {

                    if (true === $options['disabled']) {
                        $fieldOptions['disabled'] = 'disabled';
                    }

                    if (is_array($options['disabled']) && in_array($fieldname, $options['disabled'])) {
                        $fieldOptions['disabled'] = 'disabled';
                    }
                }

                if ($transformer = $this->metaData->getDataTransformer()) {

                    $field = $builder->create($fieldname, $type, $fieldOptions)->addModelTransformer($transformer);

                    $builder->add($field);

                } else {

                    $builder->add($fieldname, $type, (array) $fieldOptions);
                }

                //                 $this->addValidator($params, $key, $formBuilder);
            }
        }
    }

    /**
     *
     */
    protected function addEmbeddedForms($builder, $options)
    {
        foreach ($this->metaData->getSubForms() as $alias => $metaData) {

            $sub_options = $metaData->getOptions();

            $subform = new self($metaData);

            if ($metaData->isMultiple()) {

                $opt['type'] = $subform;

                $opt['options'] = $sub_options;

                $builder->add($alias, 'collection', $opt);

            } else {

                $builder->add($alias, $subform, $sub_options);
            }
        }
    }
}
