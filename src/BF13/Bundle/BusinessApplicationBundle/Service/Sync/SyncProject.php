<?php
namespace BF13\Bundle\BusinessApplicationBundle\Service\Sync;

use Symfony\Component\Filesystem\Filesystem;
use BF13\Bundle\BusinessApplicationBundle\Service\Sync\Package\Archive;

/**
 * Synchronise a project
 *
 *
 *
 * @author FYAM
 *
 */
class SyncProject
{

    public function __construct(ApiConnector $ApiConnector, FileManager $filemanager, Archive $archiver, $options)
    {
        $this->filemanager = $filemanager;

        $this->ApiConnector = $ApiConnector;

        $this->archiver = $archiver;

        $this->options = $options;
    }

    public function setParams(array $params)
    {
        $this->cli = $params['cli'];

        $this->api_params = $params['api'];
    }

    /**
     * load cache files
     *
     * @param unknown $arguments
     * @param string $params
     */
    public function prepare($arguments)
    {
        $this->loadTmpArchiveFile($arguments);

        $this->extractTmpArchiveFile('project.tmp.zip');
    }

    /**
     * execute sync from cache files
     */
    public function execute()
    {
        $from_dir = $this->getExtractDir();

        $target_dir = $this->api_params['api_targetdir'];

        $this->message('- sync files');

        $this->filemanager->syncFiles($from_dir, $target_dir);
    }

    protected function loadTmpArchiveFile($arguments, $archive_file = 'project.tmp.zip')
    {
        $dest = $this->api_params['api_workdir'];

        $this->filemanager->buildFolder($dest);

        $filename = $dest . DIRECTORY_SEPARATOR . $archive_file;

        $this->message('- Connexion & téléchargement');

        if('latest' === $arguments['release'])
        {
            $filecontent = $this->ApiConnector->getLastRelease($this->options['token']);

        } else {

            $filecontent = $this->ApiConnector->getRelease($arguments['release'], $this->options['token']);
        }

        $this->filemanager->saveFile($filename, $filecontent);

        return $filename;
    }

    public function getCacheDir()
    {
        $dest = $this->api_params['api_workdir'];

        return $dest;
    }

    public function getTargetDir()
    {
        $dest = $this->api_params['api_targetdir'];

        return $dest;
    }

    public function getExtractDir()
    {
        $dest = $this->api_params['api_workdir'];

        $extract_folder = $dest . '/extract';

        return $extract_folder;
    }

    protected function extractTmpArchiveFile($archive_file)
    {
        $dest = $this->api_params['api_workdir'];

        $archive_file = $dest . '/' . $archive_file;

        $extract_folder = $this->getExtractDir();

        $this->filemanager->buildFolder($extract_folder);

        $this->message(sprintf('Extraction du fichier "%s"', $archive_file));

        $this->archiver->extractFile($archive_file, $extract_folder);
    }

    protected function message($msg)
    {
        $cli = $this->cli;

        $cli($msg);
    }
}