<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class RunCommand
 * @package Euler
 */
class CurrentCommand extends Command {

    /**
     *
     */
    public function configure()
    {
        $this->setName('current')
             ->setDescription('Output the current problem');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $file      = 1;
        $directory = $this->getApplication()->config['problems_directory'];

        if (is_dir($directory))
        {
            $latest_ctime    = 0;
            $latest_filename = '';

            $d = dir($directory);

            while (false !== ($entry = $d->read()))
            {
                $filepath = sprintf("%s/%s", $directory, $entry);

                if (is_file($filepath) && filectime($filepath) > $latest_ctime)
                {
                    $latest_ctime    = filectime($filepath);
                    $latest_filename = $entry;
                }
            }
        }

        if ($latest_filename)
        {
            $bits = explode('.', $latest_filename);
            $file = $bits[0];

            $this->getApplication()->find('read')
                 ->run(new ArrayInput([
                     'problem' => $file,
                     'command' => 'read'
                 ]), $output);
        }
    }
}