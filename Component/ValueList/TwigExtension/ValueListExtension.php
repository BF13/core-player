<?php
namespace BF13\Component\ValueList\TwigExtension;

use Twig_Extension;

class ValueListExtension extends \Twig_Extension
{
    public function __construct($valueListService)
    {
        $this->valueListService = $valueListService;
    }

    public function getFilters()
    {
        return array(
                'value_list' => new \Twig_Filter_Method($this, 'valueListFilter'),
        );
    }

    public function valueListFilter($index, $list)
    {
        if (is_null($index)) {

            return $index;
        }

        $valuelist = $this->valueListService->getListValues($list);

        if(!array_key_exists($index, $valuelist))
        {
            return $index;
        }

        return $valuelist[$index];
    }

    public function getName()
    {
        return 'value_list_extension';
    }
}
