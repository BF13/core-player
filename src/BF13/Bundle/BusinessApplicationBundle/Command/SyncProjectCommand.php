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
use BF13\Bundle\BusinessApplicationBundle\Entity\ValueList;
use Symfony\Component\Console\Input\ArrayInput;

class SyncProjectCommand extends ContainerAwareCommand
{

    public function configure()
    {
        $this->setName('bf13:sync:project');

        $this->setDescription('Synchronize a project');
        $this->setDefinition(array(
            new InputOption('make-scope', 'm', InputOption::VALUE_REQUIRED, 'Generate synchronisation file for a scope'),
            new InputOption('bypass-sync', 'by', InputOption::VALUE_NONE, 'Bypass synchronisation (for prod action)'),
            new InputOption('data-load', 'dl', InputOption::VALUE_NONE, 'Load value list'),
            new InputOption('init-bundles', 'ib', InputOption::VALUE_NONE, 'Generate bundles'),
            new InputOption('business-only', 'bo', InputOption::VALUE_NONE, 'Generate Business data only'),
            new InputOption('init-db', 'id', InputOption::VALUE_NONE, 'Create the database'),
            new InputOption('update-db', 'ud', InputOption::VALUE_NONE, 'Update the database schema'),
            new InputOption('data-load', 'dl', InputOption::VALUE_NONE, 'Load value list'),
            new InputOption('scope', 'c', InputOption::VALUE_REQUIRED, 'Define the synchronisation scope'),
            new InputOption('release', 'r', InputOption::VALUE_REQUIRED, 'Retrieve the selected release'),
            new InputOption('latest', 'l', InputOption::VALUE_NONE, 'Retrieve the last release else retrieve the release defined into release.bf13 file')
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
        $SynService = $this->getContainer()->get('bf13.sync_project');

        $output->writeln(array(
            '##########################################',
            '#       Synchronisation du projet        #',
            '##########################################'
        ));

        $this->output = $output;

        $make_scope = $input->getOption('make-scope');
        $scope = $input->getOption('scope');
        $bypass = $input->getOption('bypass-sync');
        $business_only = $input->getOption('business-only');

        $release = $this->defineSelectedRelease($input);

        $this->output->writeln('- Release: ' . $release);

        $arguments = array();

        $arguments['release'] = $release;
        $arguments['scope'] = $scope;
        $arguments['business-only'] = $business_only;

        if($business_only)
        {
            $manageBundles = false;

        } else {

            $manageBundles = true;
        }

        if ($make_scope) {} else {

            $api = [
                'api_workdir' => $this->getContainer()->getParameter('bf13_api_workdir'),
                'api_targetdir' => $this->getContainer()->getParameter('bf13_api_targetdir')
            ];

            $SynService->setParams(array(
                'api' => $api,
                'cli' => function ($message) use($output) {
                    $output->writeln($message);
                }
            ));

            if (false === $bypass) {

                $SynService->prepare($arguments);

                if (true === $manageBundles) {

                    $this->checkBundlesExists($SynService->getExtractDir(), $SynService->getTargetDir(), $input->getOption('init-bundles'));
                }

                $SynService->execute();
            }

            if ($input->getOption('init-db')) {
                $this->generateBusinessEntities();

                $this->initDatabase();
            }

            if ($input->getOption('update-db')) {
                $this->generateBusinessEntities();

                $this->updateDatabase();
            }

            if ($input->getOption('data-load')) {
                $this->loadValueList();
            }
        }

        try {} catch (\Exception $e) {

            $output->writeln(array(
                'ERROR !!!',
                $e->getMessage()
            ));
        }

        $output->writeln('Terminé');
    }

    protected function defineSelectedRelease($input)
    {
        if ($input->getOption('latest')) {
            return 'latest';
        }

        if ($release = $input->getOption('release')) {
            return $release;
        }

        $dir = realpath($this->getContainer()->getParameter('kernel.root_dir') . '/../');

        $release_file = $dir . '/release.BF13';

        if (file_exists($release_file) && $release = (string) file_get_contents($release_file)) {
            return $release;
        }

        return 'latest';
    }

    protected function checkBundlesExists($cache_dir, $root_dir, $initbundles = false)
    {
        $this->output->writeln('- check bundles');

        $file = $cache_dir . '/app/config/bf13/bundles.yml';

        if (! file_exists($file)) {
            throw new \Exception(sprintf('File "%s" not found !', $file));
        }

        $yaml = new Yaml();

        $data = file_get_contents($file);

        $yaml_data = $yaml->parse($data);

        foreach ($yaml_data['bundles'] as $bundle) {
            $target = $root_dir . '/src/' . $bundle;

            if ($initbundles && is_dir($target)) {
                $this->output->writeln(sprintf('[i] Bundle "%s" already exists !', $bundle));

                continue;
            } else
                if (! $initbundles && is_dir($target)) {
                    continue;
                } else
                    if (! $initbundles && ! is_dir($target)) {
                        throw new \Exception(sprintf("Bundle \"%s\" is undefined !\n relaunch command with --init-debug option", $bundle));
                    }

            $this->generateBundle($bundle);

            $this->purgeNewBundle($target);
        }
    }

    protected function generateBundle($bundle)
    {
        $this->output->writeln('[x] generate bundle: ' . $bundle);

        $command = $this->getApplication()->find('generate:bundle');

        $bundle_sections = explode('/', $bundle);

        $arguments = array(
            'command' => 'generate:bundle',
            '--namespace' => $bundle,
            '--bundle-name' => $bundle_sections[0] . end($bundle_sections),
            '--dir' => 'src',
            '--format' => 'yml',
//             '--structure' => false,
            '--no-interaction' => true
        );

        $input = new ArrayInput($arguments);

        $returnCode = $command->run($input, $this->output);
    }

    protected function purgeNewBundle($bundle_dir)
    {
        $this->output->writeln('- cleanup ' . $bundle_dir);

        $files = array(
            $bundle_dir . '/Resources/config/routing.yml',
            $bundle_dir . '/Resources/config/services.yml',
            $bundle_dir . '/Resources/public',
            $bundle_dir . '/Resources/views',
            $bundle_dir . '/Controller',
            $bundle_dir . '/Tests/Controller'
        );

        $fs = new Filesystem();

        $fs->remove($files);
    }

    protected function initDatabase()
    {
        $this->output->writeln('- generate database');

        $command = $this->getApplication()->find('doctrine:database:create');

        $arguments = array(
            'command' => 'doctrine:database:create'
        );

        $input = new ArrayInput($arguments);

        $returnCode = $command->run($input, $this->output);

        $this->output->writeln('- create schema');

        $command = $this->getApplication()->find('doctrine:schema:create');

        $arguments = array(
            'command' => 'doctrine:schema:create'
        );

        $input = new ArrayInput($arguments);

        $returnCode = $command->run($input, $this->output);
    }

    protected function updateDatabase()
    {
        $this->output->writeln('- update database');

        $command = $this->getApplication()->find('doctrine:schema:update');

        $arguments = array(
            'command' => 'doctrine:schema:update',
            '--force' => true
        );

        $input = new ArrayInput($arguments);

        $returnCode = $command->run($input, $this->output);
    }

    protected function generateBusinessEntities()
    {
        $SynService = $this->getContainer()->get('bf13.sync_project');

        $root_dir = $SynService->getTargetDir();

        $file = $root_dir . '/app/config/bf13/bundles.yml';

        if (! file_exists($file)) {
            throw new \Exception(sprintf('File "%s" not found !', $file));
        }

        $yaml = new Yaml();

        $data = file_get_contents($file);

        $yaml_data = $yaml->parse($data);

        foreach ($yaml_data['bundles'] as $bundle) {
            if (false === strpos($bundle, 'BusinessBundle')) {
                continue;
            }

            $path = sprintf('%s/src', $root_dir);

            $path_entities = $path . '/' . $bundle . '/Resources/config/doctrine';

            if (! is_dir($path_entities)) {
                $this->output->writeln(sprintf('[!] folder "%s/Resources/config/doctrine" does not exists', $bundle));

                continue;
            }

            $this->output->writeln(sprintf('- generate "%s" entities', $bundle));

            $command = $this->getApplication()->find('doctrine:generate:entities');

            $arguments = array(
                'command' => 'doctrine:generate:entities',
                'name' => $bundle,
                '--path' => $path,
                '--no-backup' => true
            );

            $input = new ArrayInput($arguments);

            $returnCode = $command->run($input, $this->output);
        }
    }

    protected function loadValueList()
    {
        $this->output->writeln('- Chargement des listes de valeurs');

        $entityManager = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        $conn = $entityManager->getConnection();

        $conn->query('DELETE FROM valuelist');

        $SynService = $this->getContainer()->get('bf13.sync_project');

        $root_dir = $SynService->getTargetDir();
        $root_dir .= '/src';

        $finder = new Finder();

        $finder->files()
            ->name('*.valuelist.yml')
            ->in($root_dir);

        foreach ($finder as $file) {
            $yaml = new Yaml();

            $this->output->writeln('+ ' . $file->getFilename());

            $data = file_get_contents($file->getRealpath());

            $yaml_data = $yaml->parse($data);

            $DataValueList = $this->insertValueList($yaml_data['value_list']);
        }
    }

    protected function insertValueList($data)
    {
        $entityManager = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        foreach ($data as $row) {

            $DataValueList = new ValueList();
            $DataValueList->setVlkey($row['key']);
            $DataValueList->setData($row['data']);

            $entityManager->persist($DataValueList);
        }

        $entityManager->flush();

        return $DataValueList;
    }
}