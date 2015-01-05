<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCommand
 * @package Euler
 */
class ImportCommand extends Command
{
    /**
     *
     */
    public function configure()
    {
        $this->setName('import')
            ->setDescription('Import zip file')
            ->addArgument('url', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $zip = new \ZipArchive();

        $tempFile = 'tmpfile.zip';

        if (! copy($input->getArgument('url'), $tempFile)) {
        }

        $res = $zip->open($tempFile);

        if ($res !== true) {
            die('balls');
        }

        $output->writeln('<info>'.$tempFile.'</info>');

        if ($zip->locateName($this->getApplication()->config['functions_directory'])) {
            $output->writeln('<info>Functions directory found</info>');
        }

        if ($zip->locateName($this->getApplication()->config['resources_directory'])) {
            $output->writeln('<info>Resources directory found</info>');
        }

        if ($zip->locateName($this->getApplication()->config['solutions_directory'])) {
            $output->writeln('<info>Solutions directory found</info>');
        }

        $zip->extractTo('./');

        $output->writeln('<info>Import complete</info>');

        unlink($tempFile);
    }
}
