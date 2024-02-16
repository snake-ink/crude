<?php

namespace SnakeInk\Crude\Commands\Helpers;

use Illuminate\Console\Command;

class Publisher
{
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function publishFile($source, $destinationPath, $fileName, $overwriteFile = false): bool
    {
        $destinationFilePath = $destinationPath.'/'.$fileName;

        if (!is_dir($destinationPath)) {
            if (!mkdir($destinationPath, 0755, true)) {
                $this->command->error("Can't create directory: ".$destinationPath);

                return false;
            }
        }

        if (!is_writable($destinationPath)) {
            if (!chmod($destinationPath, 0755)) {
                $this->command->error('Destination path is not writable.');

                return false;
            }
        }

        if (file_exists($source)) {
            if (file_exists($destinationFilePath) && $overwriteFile === false) {
                $this->command->error('Destination file already exists. Use "--force" option to overwrite it.');

                return false;
            } elseif (!copy($source, $destinationFilePath)) {
                $this->command->error('File was not copied.');

                return false;
            }
        } else {
            $this->command->error('Source file does not exist.');

            return false;
        }

        return true;
    }

    public function publishDirectory($source, $destination)
    {
        if (!is_dir($source)) {
            $this->command->error('Bad source path.');
        } else {
            $dir = opendir($source);

            if (!is_dir($destination)) {
                if (!mkdir($destination, 0755, true)) {
                    $this->command->error("Can't create directory: ".$destination);
                }
            }

            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($source.'/'.$file)) {
                        $this->publishDirectory($source.'/'.$file, $destination.'/'.$file);
                    } else {
                        copy($source.'/'.$file, $destination.'/'.$file);
                    }
                }
            }
            closedir($dir);
        }
    }
}
