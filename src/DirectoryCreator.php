<?php namespace Euler;

/**
 * Class DirectoryCreator
 * @package Euler
 */
Trait DirectoryCreator {

    /**
     * @param $directory
     */
    private function createDirectory($directory)
    {
        if ( ! $this->directoryExists($directory))
        {
            mkdir($directory, 0755, true);
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
}