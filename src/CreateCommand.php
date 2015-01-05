<?php namespace Euler;

use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Euler\DirectoryCreator;

/**
 * Class CreateCommand
 * @package Euler
 */
class CreateCommand extends Command {

    use DirectoryCreator;

    /**
     * @param Client $client
     * @param null $name
     */
    public function __construct(Client $client, $name = null)
    {
        parent::__construct($name);

        $this->client = $client;
    }

    /**
     *
     */
    public function configure()
    {
        $this->setName('create')
             ->setDescription('Create boilerplate for a given problem number')
             ->addArgument('problem', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');
        //$this->runSetupCommand($output);
        $this->fetchResources($input, $output);

        if ( ! file_exists($this->getApplication()->config['solutions_directory'].DIRECTORY_SEPARATOR.$problem.'.php'))
        {
            file_put_contents(
                $this->getApplication()->config['solutions_directory'].DIRECTORY_SEPARATOR.$problem.'.php',
                sprintf(file_get_contents('config/template.php'), $problem)
            );

            $output->writeln(sprintf('<info>Added file %s.php</info>', $problem));
        }
    }

    /**
     * @param OutputInterface $output
     * @throws \Exception
     */
    public function runSetupCommand(OutputInterface $output)
    {
        $i = new ArrayInput(['setup']);
        $this->getApplication()->find('setup')->run($i, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function fetchResources(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');
        $url     = sprintf('%s/problem=%s', $this->getApplication()->config['base_url'], $problem);
        $crawler = $this->client->request('GET', $url);
        $files   = $crawler->filter('div.problem_content > p > a')->extract(['_text', 'href']);
        $text    = $crawler->filter('div.problem_content')->extract(['_text']);

        if ($files)
        {
            $directory = sprintf('%s/%s', $this->getApplication()->config['resources_directory'], $problem);
            $this->createDirectory($directory);

            foreach ($files as $file)
            {
                file_put_contents(
                    $directory . DIRECTORY_SEPARATOR . $file[0],
                    file_get_contents(sprintf('%s/%s', $this->baseUrl, $file[1]))
                );
            }
        }

        if ( ! file_exists(sprintf('%s/%s.php',$this->getApplication()->config['problems_directory'], $problem)))
        {
            file_put_contents(
                sprintf('%s/%s.php',$this->getApplication()->config['problems_directory'], $problem),
                $text
            );
        }
    }
}