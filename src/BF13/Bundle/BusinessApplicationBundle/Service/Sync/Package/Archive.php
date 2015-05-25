<?php
namespace BF13\Bundle\BusinessApplicationBundle\Service\Sync\Package;

interface Archive
{

    public function extractFile($filename, $extract_folder, $include = null);
}