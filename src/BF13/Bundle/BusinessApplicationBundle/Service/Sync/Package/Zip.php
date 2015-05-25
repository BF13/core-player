<?php
namespace BF13\Bundle\BusinessApplicationBundle\Service\Sync\Package;

use BF13\Bundle\BusinessApplicationBundle\Service\Sync\Exception\SyncException;

class Zip implements Archive
{

    public function __construct()
    {
    }

    public function extractFile($filename, $extract_folder, $include = null)
    {
        $za = new \ZipArchive();
        $opened = $za->open($filename);

        if($opened !== true)
        {
            $zip_error = array(
                \ZipArchive::ER_EXISTS => 'Le fichier existe déjà',
                \ZipArchive::ER_INCONS => 'L\'archive ZIP est inconsistante',
                \ZipArchive::ER_INVAL => 'Argument invalide',
                \ZipArchive::ER_MEMORY => 'Erreur de mémoire',
                \ZipArchive::ER_NOENT => 'Le fichier n\'existe pas',
                \ZipArchive::ER_NOZIP => 'Le fichier n\'est pas une archive valide',
                \ZipArchive::ER_OPEN => 'Impossible d\'ouvrir le fichier',
                \ZipArchive::ER_READ => 'Erreur lors de la lecture',
                \ZipArchive::ER_SEEK => 'Erreur de position',
            );
            throw new SyncException(sprintf('! ZIP erreur : "%s"', $zip_error[$opened]));
        }

        $files = null;
        if ($include) {
            for ($i = 0; $i < $za->numFiles; $i ++) {
                $entry = $za->getNameIndex($i);
                // Use strpos() to check if the entry name contains the directory we want to extract
                if (in_array($entry, $include)) {
                    // Add the entry to our array if it in in our desired directory
                    $files[] = $entry;
                }
            }
        }

        $za->extractTo($extract_folder, $files);
        $za->close();
    }
}