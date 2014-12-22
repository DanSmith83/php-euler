<?php namespace Euler;

use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;



class SetupCommand extends Command {

    private $baseUrl = 'https://projecteuler.net';

    public function __construct(Client $client, $name = null)
    {
        parent::__construct($name);

        $this->client = $client;
    }

    public function configure()
    {
        $this->setName('setup')
             ->setDescription('The problem number')
             ->addArgument('problem', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');
        $url     = sprintf('%s/problem=%s', $this->baseUrl, $problem);
        $crawler = $this->client->request('GET', $url);
        $files   = $crawler->filter('div.problem_content > p > a')->extract(['_text', 'href']);



        if ($files)
        {
            foreach ($files as $file)
            {
                $dir = sprintf('resources/%s', $problem);

                if ( ! is_dir($dir))
                {
                    mkdir($dir, 0755, true);
                }

                file_put_contents (
                    $dir.DIRECTORY_SEPARATOR.$file[0],
                    file_get_contents(sprintf('%s/%s', $this->baseUrl, $file[1]))
                );
            }
        }


        if ( ! is_dir('functions'))
        {
            mkdir('functions', 0755, true);
        }

        if ( ! is_dir('solutions'))
        {
            mkdir('solutions', 0755, true);
        }

        if ( ! file_exists('solutions'.DIRECTORY_SEPARATOR.$problem.'.php'))
        {
            file_put_contents(
                'solutions'.DIRECTORY_SEPARATOR.$problem.'.php',
                sprintf(file_get_contents('config/template.php'), $problem)
            );

            $output->writeln(sprintf('<info>Added file %s.php</info>', $problem));
        }
    }


}