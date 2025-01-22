<?php

namespace SakuraLandGroup\Tips;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use SakuraLandGroup\Tips\tip\Tip;

class Tips extends PluginBase implements Listener
{
    protected $tips = [];

    public function getTip(string $name)
    {
        foreach ($this->tips as $tip) {
            if ($tip->getName() === $name) {
                return $tip;
            }
        }
        return false;
    }

    public function getTips(): array
    {
        return $this->tips;
    }

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->reloadConfig();

        $this->saveResource('tips.yml');
        foreach ((new Config($this->getDataFolder() . 'tips.yml', Config::YAML))->getAll() as $name => $data) {
            $this->tips[] = new Tip($this, $name, $data['messages'], $data['updateTime'] ?? 10, $data['sidebar'] ?? false);
        }

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        if ($this->getConfig()->getNested('autoLoad.enabled')) {
            $tip = $this->getTip($this->getConfig()->getNested('autoLoad.tipName', ''));
            if ($tip instanceof Tip) {
                $tip->addPlayer($event->getPlayer());
            }
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event)
    {
        foreach ($this->tips as $tip) {
            if ($tip instanceof Tip && in_array($event->getPlayer(), $tip->getPlayers())) {
                $tip->removePlayer($event->getPlayer());
            }
        }
    }
}
