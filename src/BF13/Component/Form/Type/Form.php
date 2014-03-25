<?php
namespace BF13\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use BF13\Component\Form\Mapping\FormMetaData;

/**
 * Construction du formulaire à partir des métadonnées
 *
 * @author FYAMANI
 *
 */
class Form extends AbstractType implements FormInterface
{
    protected $metaData;

    protected $options;

    public function __construct(FormMetaData $metadata = null, $options = array())
    {
        $this->metaData = $metadata;

        $this->options = $options;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->metaData) {

            $this->buildFormMetaData($builder, $options);
        }

        $this->configure($builder, $options);
    }

    public function configure($builder, $options)
    {
    }

    public function getName()
    {
        return $this->metaData->getName();
    }

    public function buildFormMetaData($builder, $options)
    {
        $this->addFields($builder, $options);

        $this->addEmbeddedForms($builder);
    }

    protected function addFields($builder, $options)
    {
        foreach ($this->metaData->getFields() as $fieldname => $params) {

            $type = key($params);

            $fieldOptions = current($params);

            if (array_key_exists('disabled', $options)) {

                if (true === $options['disabled']) {
                    $fieldOptions['disabled'] = 'disabled';
                }

                if (is_array($options['disabled']) && in_array($fieldname, $options['disabled'])) {
                    $fieldOptions['disabled'] = 'disabled';
                }
            }

            if (is_array($fieldOptions) && array_key_exists('data_transformer', $fieldOptions)) {

                $transformer = $fieldOptions['data_transformer'];

                $transformer_options = array_key_exists('data_option_transformer', $fieldOptions) ? $fieldOptions['data_option_transformer'] : array();

                unset($fieldOptions['data_transformer'], $fieldOptions['data_option_transformer']);

                $transformer = new $transformer(array_merge($fieldOptions, $transformer_options));

                $field = $builder->create($fieldname, $type, $fieldOptions)->addModelTransformer($transformer);

                $builder->add($field);

            } else {

                $builder->add($fieldname, $type, (array) $fieldOptions);
            }
        }
    }

    /**
     *
     */
    protected function addEmbeddedForms($builder)
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
