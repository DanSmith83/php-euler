<?php namespace Euler;

use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class DirectoryCreator
 * @package Euler
 */
trait DirectoryCreator
{
    /**
     * @param $directory
     */
    private function createDirectory($directory, $permissions = 0755)
    {
        if (! $this->directoryExists($directory)) {
            mkdir($directory, $permissions, true);
        }
    }

    /**
     * @param $directory
     * @return bool
     */
    private function directoryExists($directory)
    {
        return is_dir($directory);
    }

    private function getLatestDirectory($directory)
    {
        $iterator   = new \DirectoryIterator($directory);
        $latestTime = 0;
        $latestDir  = NULL;

        foreach ($iterator as $item) {
            if ($item->isDir() && ! $item->isDot()) {
                if ($item->getMTime() > $latestTime) {
                    $latestTime = $item->getMTime();
                    $latestDir  = $item->getFilename();
                }
            }
        }

        return $latestDir ?: false;
    }

    private function runCommand($command, $parameters, $output)
    {
        $parameters = new ArrayInput(array_merge(
            $parameters, [
                'command' => $command
            ]
        ));

        $this->getApplication()
             ->find($command)
             ->run($parameters, $output);
    }

    private function getFile($problem, $type)
    {
        if ( ! $this->directoryExists($this->getApplication()->config['problems_directory'])) {
            throw new Exception('Problems directory does not exist');
        }

        return sprintf(
            '%s%s%s%s%s.php',
            $this->getApplication()->config['problems_directory'],
            DIRECTORY_SEPARATOR,
            $problem,
            DIRECTORY_SEPARATOR,
            $type
        );
    }
}
