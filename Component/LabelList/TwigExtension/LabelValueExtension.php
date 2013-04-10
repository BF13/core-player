<?php
namespace BF13\Component\LabelList\TwigExtension;
use Twig_Extension;

class LabelValueExtension extends \Twig_Extension
{
    public function __construct($labelValueService)
    {
        $this->labelValueService = $labelValueService;
    }

    public function getFilters()
    {
        return array('label_value' => new \Twig_Filter_Method($this, 'labelValueFilter'),);
    }

    public function labelValueFilter($index, $list)
    {
        if (is_null($index)) {

            return $index;
        }

        $valuelist = $this->labelValueService->getLabelValues($list);

        if (!array_key_exists($index, $valuelist)) {
            return $index;
        }

        return $valuelist[$index];
    }

    public function getName()
    {
        return 'label_value_extension';
    }
}
