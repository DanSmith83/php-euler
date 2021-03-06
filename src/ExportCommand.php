<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ExportCommand
 * @package Euler
 */
class ExportCommand extends Command
{
    /**
     *
     */
    public function configure()
    {
        $this->setName('archive')
             ->setDescription('Export files as zip');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $file = 'archive.zip';
        $zip  = new \ZipArchive();

        $zip->open($file, \ZipArchive::CREATE);

        if (! $zip->open($file, \ZipArchive::OVERWRITE)) {
        }

        $this->zipDirectory($zip, $this->getApplication()->config['problems_directory']);
        $this->zipDirectory($zip, $this->getApplication()->config['shared_directory']);

        if (! $zip->status == \ZipArchive::ER_OK) {
        }

        $zip->close();

        $output->writeln('<info>Archive complete</info>');
    }

    /**
     * @param $zip
     * @param $directory
     * @return mixed
     */
    public function zipDirectory($zip, $directory)
    {
        if (is_dir($directory)) {
            foreach (glob($directory.'/*') as $file) {
                $zip->addFile($file, sprintf('%s/%s', $directory, $file));
            }
        }
    }
}
