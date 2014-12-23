<?php namespace Euler;

use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Euler\DirectoryCreator;

class CreateCommand extends Command {

    use DirectoryCreator;

    public function __construct(Client $client, $name = null)
    {
        parent::__construct($name);

        $this->client = $client;
    }

    public function configure()
    {
        $this->setName('create')
             ->setDescription('Create boilerplate for a given problem number')
             ->addArgument('problem', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');
        $this->runSetupCommand($output);
        $this->fetchResources($input, $output);

        if ( ! file_exists('solutions'.DIRECTORY_SEPARATOR.$problem.'.php'))
        {
            file_put_contents(
                'solutions'.DIRECTORY_SEPARATOR.$problem.'.php',
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

    private function fetchResources(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');
        $url     = sprintf('%s/problem=%s', $this->getApplication()->config['base_url'], $problem);
        $crawler = $this->client->request('GET', $url);
        $files   = $crawler->filter('div.problem_content > p > a')->extract(['_text', 'href']);

        if ($files)
        {
            foreach ($files as $file)
            {
                $directory = sprintf('resources/%s', $problem);
                $this->createDirectory($directory);

                file_put_contents(
                    $directory . DIRECTORY_SEPARATOR . $file[0],
                    file_get_contents(sprintf('%s/%s', $this->baseUrl, $file[1]))
                );
            }
        }
    }
}