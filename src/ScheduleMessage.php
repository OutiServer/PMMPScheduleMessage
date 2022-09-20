<?php

declare(strict_types=1);

namespace outiserver\schedulemessage;

use JackMD\ConfigUpdater\ConfigUpdater;
use outiserver\schedulemessage\tasks\ScheduleMessageTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class ScheduleMessage extends PluginBase
{
    use SingletonTrait;

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->saveResource("config.yml");
        $config = new Config("{$this->getDataFolder()}config.yml", Config::YAML);

        ConfigUpdater::checkUpdate($this, $config, "version", 1, "");

        $this->getScheduler()->scheduleRepeatingTask(new ScheduleMessageTask($config->get("messages", [])), 20 * (int)$config->get("interval", 10));
    }
}