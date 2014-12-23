<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NextCommand extends Command {

    public function configure()
    {
        $this->setName('next')
             ->setDescription('Generate boilerplate for the next unsolved problem');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $next = $this->getNext();

        $output->writeln('<info>Next Problem</info>');
        $this->getApplication()->find('create')
                               ->run(new ArrayInput(['problem' => $next, 'command' => 'create']), $output);
    }

    private function getNext()
    {
        $file = 1;

        if (is_dir('solutions'))
        {
            $latest_ctime = 0;
            $latest_filename = '';

            $d = dir('solutions');

            while (false !== ($entry = $d->read()))
            {
                $filepath = "solutions/{$entry}";

                if (is_file($filepath) && filectime($filepath) > $latest_ctime)
                {
                    $latest_ctime    = filectime($filepath);
                    $latest_filename = $entry;
                }
            }

            $bits = explode('.', $latest_filename);
            $file = $bits[0]++;
        }

        return $file;
    }

}