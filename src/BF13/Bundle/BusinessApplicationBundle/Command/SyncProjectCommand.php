<?php
namespace BF13\Bundle\BusinessApplicationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Filesystem\Filesystem;

class SyncProjectCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this->setName('bf13:sync:project');

        $this->setDescription('Synchronize a project');

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

        $output->writeln(array(
            'Synchronisation du projet'
        ));

        $project_root_dir = realpath($this->getContainer()->getParameter('kernel.root_dir') . '/..');

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

        $filename = $dest . '/project.tmp.zip';
        $api_url = $api_params['api_url'] . $api_params['api_call'];

        $ZipFile = $this->getZipFile($api_url, $api_params['api_auth']);
        $this->saveZipFile($filename, $ZipFile);
        $this->extractZipFile($filename, $project_root_dir);

        $output->writeln('Terminé');
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

        if('' != trim($auth))
        {
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

    protected function extractZipFile($filename, $extract_folder)
    {
        // extract files
        $this->output->writeln('- Extraction');
        $za = new \ZipArchive();
        $za->open($filename);
        $za->extractTo($extract_folder);
        $za->close();
    }
}