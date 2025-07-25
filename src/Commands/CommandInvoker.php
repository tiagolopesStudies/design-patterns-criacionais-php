<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Commands;

class CommandInvoker
{
    private array $commands = [];

    public function addCommand(CommandInterface $command): void
    {
        $this->commands[] = $command;
    }

    public function executeCommands(): void
    {
        /** @var CommandInterface $command */
        foreach ($this->commands as $command) {
            $command->execute();
        }
    }

    public function clearCommands(): void
    {
        $this->commands = [];
    }
}
