<?php
namespace BF13\Bundle\BusinessApplicationBundle\Service\Sync;

class FileManager
{

    public function __construct($Filesystem, $Finder)
    {
        $this->Filesystem = new $Filesystem;

        $this->Finder = new $Finder;
    }

    public function buildFolder($dest, $rebuild = true)
    {
        if ($rebuild && is_dir($dest)) {
            $this->Filesystem->remove($dest);
        }

        $this->Filesystem->mkdir($dest);
    }

    public function saveFile($filename, $content, $force_delete = true)
    {
        if($force_delete)
        {
            @unlink($filename);
        }

        file_put_contents($filename, $content);
    }

    public function syncFiles($from_dir, $target_dir)
    {
        $finder = forward_static_call(array($this->Finder, 'create'));

        $finder->files()->in($from_dir);

        foreach ($finder as $file) {

            $base_file_strategy = false;

            $src_pattern = $file->getRelativePathname();

            $filename = $file->getFilename();

            $new_file = $from_dir . DIRECTORY_SEPARATOR . $src_pattern;

            if (0 === strpos($filename, '$$')) {
                $base_file_strategy = true;

                $src_pattern = str_replace($filename, substr($filename, 2), $src_pattern);
            }

            $existing_file = $target_dir . DIRECTORY_SEPARATOR . $src_pattern;

            if (is_file($existing_file)) {
                $existing_file_content = file_get_contents($existing_file);
            } else {

                $existing_file_content = false;
            }

            if (is_file($new_file)) {
                $new_file_content = file_get_contents($new_file);
            } else {

                $new_file_content = false;
            }

            switch (true) {

                // ne rien faire si fichier existant et stratégie de réplication active
                case $base_file_strategy === true && $existing_file_content !== false && trim($existing_file_content) != '':

                    break;

                // copie si nouveau fichier
                case $existing_file_content === false && $new_file_content !== false:

                    $this->Filesystem->copy($new_file, $existing_file, true);

                    break;

                // ne rien faire si fichier existant et sans source
                case $new_file_content === false && $existing_file_content !== false:

                    break;

                // copie si fichier existant différent de la source et sans stratégie de réplication
                case $new_file_content !== false && $new_file_content !== $existing_file_content:

                    $this->Filesystem->copy($new_file, $existing_file, true);

                    break;

                // ne rien faire si fichier existant identique à la source
                case $new_file_content === $existing_file_content:
                default:
            }
        }
    }
}