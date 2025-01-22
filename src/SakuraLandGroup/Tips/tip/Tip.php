<?php

namespace SakuraLandGroup\Tips\tip;

use pocketmine\Player;
use pocketmine\Server;
use SakuraLandGroup\Tips\Tips;

class Tip
{
    protected $is_sidebar;
    protected $name;
    protected $messages;
    protected $players = [];
    protected $plugin;
    protected $task;
    protected $update_time;

    public function __construct(Tips $plugin, string $name, array $messages, int $update_time = 10, bool $is_sidebar = false)
    {
        $this->plugin = $plugin;
        $this->name = $name;
        $this->messages = $messages;
        $this->update_time = $update_time;
        $this->is_sidebar = $is_sidebar;
        Server::getInstance()->getScheduler()->scheduleRepeatingTask($this->task = new TipTask($this->plugin, $this), $this->update_time);
    }

    public function addPlayer(Player $player)
    {
        $this->players[$player->getId()] = $player;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function getTask(): TipTask
    {
        return $this->task;
    }

    public function getUpdateTime(): int
    {
        return $this->update_time;
    }

    public function isSidebar(): bool
    {
        return $this->is_sidebar;
    }

    public function removePlayer(Player $player)
    {
        unset($this->players[$player->getId()]);
    }
}
