<?php
namespace BF13\Component\ValueList\FormType;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValueListType extends AbstractType
{
    public function __construct($valueList)
    {
        $this->valueList = $valueList;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $v = $this->valueList;
        
        $resolver->setDefaults(array(
                'choices' => function (Options $options) use($v) {
                    
                    $choices = $v->getListValues($options['source']);
            
                    return $choices;
                },
                'source' => null,
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }
 
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'value_list';
    }
}