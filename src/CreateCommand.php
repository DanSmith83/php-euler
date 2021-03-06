<?php namespace Euler;

use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class CreateCommand
 * @package Euler
 */
class CreateCommand extends Command
{
    use DirectoryCreator;

    /**
     * @param Client $client
     * @param null   $name
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
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');

        $this->createDirectory(
            sprintf('%s%s%s',
                $this->getApplication()->config['problems_directory'],
                DIRECTORY_SEPARATOR,
                $problem
            ));

        $this->fetchResources($input, $output);
    }

    /**
     * @param  OutputInterface $output
     * @throws \Exception
     */
    public function runSetupCommand(OutputInterface $output)
    {
        $i = new ArrayInput(['setup']);
        $this->getApplication()->find('setup')->run($i, $output);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    private function fetchResources(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');
        $url     = sprintf('%s/problem=%s', $this->getApplication()->config['base_url'], $problem);
        $crawler = $this->client->request('GET', $url);
        $files   = $crawler->filter('div.problem_content > p > a')->extract(['_text', 'href']);
        $text    = $crawler->filter('div.problem_content')->extract(['_text']);

        if ($files) {

            $directory = sprintf('%s%s%s',
                $this->getApplication()->config['problems_directory'],
                DIRECTORY_SEPARATOR,
                $problem);

            foreach ($files as $file) {
                $this->createFile(
                    $directory.DIRECTORY_SEPARATOR.$file[0],
                    file_get_contents(sprintf('%s%s%s', $this->baseUrl, DIRECTORY_SEPARATOR, $file[1]))
                );
            }
        }

        $this->createFile($this->getFile($problem, 'problem'), trim($text[0]));

        $this->createFile(
            $this->getFile($problem, 'solution'),
            sprintf(file_get_contents('config/template.php'), $problem)
        );
    }
}
