<?php

declare(strict_types=1);

namespace outiserver\schedulemessage;

use outiserver\schedulemessage\tasks\ScheduleMessageTask;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class ScheduleMessage extends PluginBase
{
    use SingletonTrait;

    public const VERSION = "1.0.0";

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        if (@file_exists("{$this->getDataFolder()}config.yml")) {
            $config = new Config("{$this->getDataFolder()}config.yml", Config::YAML);
            // データベース設定のバージョンが違う場合は
            if ($config->get("version") !== self::VERSION) {
                rename("{$this->getDataFolder()}config.yml", "{$this->getDataFolder()}config.yml.{$config->get("version")}");
                $this->getLogger()->warning("config.yml バージョンが違うため、上書きしました");
                $this->getLogger()->warning("前バージョンのconfig.ymlは{$this->getDataFolder()}config.yml.{$config->get("version")}にあります");
            }
        }

        $this->saveResource("config.yml");
        $config = new Config("{$this->getDataFolder()}config.yml", Config::YAML);

        $this->getScheduler()->scheduleRepeatingTask(new ScheduleMessageTask($config->get("messages", [])), 20 * (int)$config->get("interval", 10));
    }
}