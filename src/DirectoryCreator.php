<?php namespace Euler;

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

    private function getLatestFile($directory)
    {
        if (is_dir($directory)) {
            $latest_ctime    = 0;
            $latestFilename = '';

            $d = dir($directory);

            while (false !== ($entry = $d->read())) {
                $filepath = sprintf("%s/%s", $directory, $entry);

                if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
                    $latest_ctime   = filectime($filepath);
                    $latestFilename = $entry;
                }
            }
        }

        return $latestFilename ?: false;
    }
}
