<?php

declare(strict_types=1);

namespace outiserver\schedulemessage\tasks;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class ScheduleMessageTask extends Task
{
    private int $next;

    private array $messages;

    public function __construct(array $messages)
    {
        $this->next = 0;
        $this->messages = $messages;
    }

    public function onRun(): void
    {
        if (count(Server::getInstance()->getOnlinePlayers()) < 1) return;

        if (count($this->messages) < 1) return;
        elseif (count($this->messages) < ($this->next + 1)) $this->next = 0;

        Server::getInstance()->broadcastMessage("§a[システム][定期] {$this->messages[$this->next]}");
        $this->next++;
    }
}