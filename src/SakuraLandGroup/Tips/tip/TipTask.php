<?php

namespace SakuraLandGroup\Tips\tip;

use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;
use SakuraLandGroup\Tips\Tips;

class TipTask extends PluginTask
{
    protected $tip;

    public function __construct(Tips $owner, Tip $tip)
    {
        parent::__construct($owner);
        $this->tip = $tip;
    }

    public function onRun($currentTick)
    {
        $plugin = $this->getOwner();
        if ($plugin->isDisabled()) {
            return;
        }
        /**
         * @var Player $player
         */
        foreach ($this->tip->getPlayers() as $player) {
            $message = '';
            foreach ($this->tip->getMessages() as $message_line) {
                if ($this->tip->isSidebar()) {
                    $message .= str_repeat(' ', 72);
                }
                $message .= $message_line . TextFormat::RESET . "\n";
            }

            $message = str_ireplace('%{online}', count($player->getServer()->getOnlinePlayers()), $message);
            $message = str_ireplace('%{player}', $player->getName(), $message);
            $message = str_ireplace('%{position}', implode(', ', [$player->getFloorX(), $player->getFloorY(), $player->getFloorZ()]), $message);
            $message = str_ireplace('%{world}', $player->level->getFolderName(), $message);

            $message = preg_replace('/' . preg_quote('&', '/') . '([0-9a-gk-or])/u', TextFormat::ESCAPE . '$1', $message);

            $player->sendTip($message);
        }
    }
}
