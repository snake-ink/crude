<?php

namespace SnakeInk\Crude\Commands\Concerns;

trait HasExtraGeneratorFunctionality
{
    public function buildGeneratorCommand($command)
    {
        return $this->includeForceOpt($command.' '.$this->argument('name'));
    }

    public function buildGeneratorCommandArguments()
    {
        return [
            'name' => $this->argument('name'),
            '--force' => $this->option('force'),
        ];
    }

    public function includeForceOpt($command)
    {
        if ($this->option('force')) {
            return $command.' --force';
        }

        return $command;
    }
}
