<?php namespace Euler;

Trait DirectoryCreator {

    private function createDirectory($directory)
    {
        if ( ! $this->directoryExists($directory))
        {
            mkdir($directory, 0755, true);
        }
    }

    private function directoryExists($directory)
    {
        return is_dir($directory);
    }
}