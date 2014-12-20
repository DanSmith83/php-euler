<?php namespace Euler;

use Goutte\Client;
use Symfony\Component\Console\Command\Command;

class SetupCommand extends Command {

    private $baseUrl = 'https://projecteuler.net';

    public function configure()
    {
        $this->setName('setup')
             ->setDescription('The problem number')
             ->addArgument('problem', \Symfony\Component\Console\Input\InputArgument::REQUIRED);
    }

    public function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $problem = $input->getArgument('problem');
        //$output->writeln(sprintf('Yo bitch, get on with problem %s', $problem));

        $client  = new Client();
        $url     = sprintf('%s/problem=%s', $this->baseUrl, $problem);
        $crawler = $client->request('GET', $url);


        $files = $crawler->filter('div.problem_content > p > a')->extract(['_text', 'href']);

        $output->writeln(count($files));

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

        if ( ! is_dir('solutions'))
        {
            mkdir('solutions', 0755, true);
        }

        if ( ! file_exists('solutions'.DIRECTORY_SEPARATOR.$problem.'.php'))
        {
            file_put_contents(
                'solutions'.DIRECTORY_SEPARATOR.$problem.'.php',
                '<?php'
            );
        }
    }


}