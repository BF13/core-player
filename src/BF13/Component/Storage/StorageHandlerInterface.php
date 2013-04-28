<?php
namespace BF13\Component\Storage;

/**
 * 
 * @author FYAMANI
 *
 */
interface StorageHandlerInterface
{
    public function retrieve($index);

    public function create(\Closure $fn = null);
}
