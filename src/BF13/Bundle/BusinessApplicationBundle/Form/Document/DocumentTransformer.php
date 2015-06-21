<?php
namespace BF13\Bundle\BusinessApplicationBundle\Form\Document;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class DocumentTransformer implements DataTransformerInterface
{
    /**
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Transforms an object (file) to a string (number).
     *
     * @param  string $filename
     * @return File|null
     * @throws TransformationFailedException if object (File) is not found.
     *
     */
    public function transform($filename)
    {
        if (null === $filename) {
            return null;
        }

        $filename = new File($filename);

        return new File($filename);
    }

    /**
     * Transforms a string (number) to an object (file).
     *
     * @param  File|null $file
     * @return string
     */
    public function reverseTransform($file)
    {
        if (!$file) {
            return null;
        }

        $new = $file->move($this->path, $file->getClientOriginalName());

        $filename = $new->getPathName();

        return $filename;
    }
}