<?php
namespace BF13\Component\Storage\DoctrineUnit\Loader;

use BF13\Component\Storage\DoctrineUnit\Schema;

interface LoaderInterface
{
    public function loadSchemaData(Schema $schema);
}
