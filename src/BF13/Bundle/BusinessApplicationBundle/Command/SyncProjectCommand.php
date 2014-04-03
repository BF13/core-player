<?php
namespace BF13\Bundle\BusinessApplicationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class SyncProjectCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this->setName('bf13:sync:project');

        $this->setDescription('Synchronize a project');
        $this->setDefinition(array(
            new InputOption('make-scope', 'm', InputOption::VALUE_REQUIRED, 'Generate synchronisation file for a scope'),
            new InputOption('scope', 'c', InputOption::VALUE_REQUIRED, 'Define the synchronisation scope')
        ));

        $this->setHelp(<<<EOT
Sync a project
EOT
        );
    }

    /**
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $make_scope = $input->getOption('make-scope');
        $scope = $input->getOption('scope');

        if ($make_scope) {

            $this->makeScope($make_scope);

        } else {

            $this->syncProject($scope);
        }

        $output->writeln('Terminé');
    }

    protected function makeScope($scope)
    {
        $filepath = $this->buildZipFile('project.tmp.zip', $scope);

        $dir = realpath($this->getContainer()->getParameter('kernel.cache_dir')) . '/bf13_scope/' . $scope;

        $fs = new Filesystem();

        if (is_dir($dir)) {

            $fs->remove($dir);
        }

        $fs->mkdir($dir);

        $this->extractZipFile($filepath, $dir);

        $finder = new Finder();

        $finder->files()->in($dir);

        $files = array('include' => array(), 'exclude' => array());

        foreach ($finder as $file) {

            $files['include'][] = $file->getRelativePathname();
        }

        $yaml = new Yaml();

        $yaml_data = $yaml->dump($files);

        $dir = realpath($this->getContainer()->getParameter('kernel.root_dir')) . '/bf13-dev/scope';

        if (! is_dir($dir)) {

            $fs->mkdir($dir);
        }

        $filescope = sprintf('%s.scope.yml', $scope);
        $this->output->writeln(array(
            sprintf('Création du fichier "%s"', $filescope)
        ));

        file_put_contents($dir . '/' . $filescope, $yaml_data);
    }


    protected function syncProject($scope = null)
    {
        $this->output->writeln(array(
            'Synchronisation du projet'
        ));

        $filepath = $this->buildZipFile('project.tmp.zip', $scope);

        $include = null;

        if ($scope) {

            $dir = realpath($this->getContainer()->getParameter('kernel.root_dir')) . '/bf13-dev/scope';

            $filescope = sprintf('%s/%s.scope.yml', $dir, $scope);

            if(! is_file($filescope))
            {
                throw new \Exception(sprintf('Fichier "%s" introuvable !', $filescope));
            }

            $content = file_get_contents($filescope);

            $yaml = new Yaml();

            $data = $yaml->parse($content);

            $include = $data['include'];
        }

        $dir = realpath($this->getContainer()->getParameter('kernel.root_dir') . '/..');

        $this->extractZipFile($filepath, $dir . '/', $include);
    }

    protected function buildZipFile($filename, $scope)
    {
        $api_params = $this->getContainer()->getParameter('bf13_business_application');

        $dest = $api_params['api_workdir'];

        $fs = new Filesystem();

        if (! is_dir($dest)) {
            throw new \Exception('Le paramètre "workdir" est incorrecte !');
        }

        $dest .= '/bf13_sync';

        if (is_dir($dest)) {
            $fs->remove($dest);
        }

        $fs->mkdir($dest);

        $filename = $dest . '/' . $filename;
        $api_url = $api_params['api_url'] . $api_params['api_call'];

        $ZipFile = $this->getZipFile($api_url, $api_params['api_auth']);

        $this->saveZipFile($filename, $ZipFile);

        return $filename;
    }

    protected function getZipFile($url, $auth)
    {
        // retrieve zipfile
        $this->output->writeln('- Connexion & téléchargement');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/zip'
        ));

        if ('' != trim($auth)) {
            curl_setopt($ch, CURLOPT_USERPWD, $auth);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $http_response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        switch (substr($http_status, 0, 1)) {
            case 2:
                return $http_response;
                break;

            case 4:
                throw new \Exception('! Erreur HTTP ' . $http_status . ":\nl'authentification de base a échouée !");
                break;

            default:
                throw new \Exception('! Erreur HTTP ' . $http_status);
        }
    }

    protected function saveZipFile($filename, $content)
    {
        // save zipfile
        $this->output->writeln('- Enregistrement');
        @unlink($filename);
        file_put_contents($filename, $content);
    }

    protected function extractZipFile($filename, $extract_folder, $include = null)
    {


        // extract files
        $this->output->writeln('- Extraction');
        $za = new \ZipArchive();
        $za->open($filename);

        $files = null;
        if($include)
        {
            for($i = 0; $i < $za->numFiles; $i++) {
                $entry = $za->getNameIndex($i);
                //Use strpos() to check if the entry name contains the directory we want to extract
                if (in_array($entry, $include)) {
                    //Add the entry to our array if it in in our desired directory
                    $files[] = $entry;
                }
            }
        }

        $za->extractTo($extract_folder, $files);
        $za->close();
    }
}