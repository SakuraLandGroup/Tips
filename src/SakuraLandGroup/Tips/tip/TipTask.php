<?php

namespace SakuraLandGroup\Tips\tip;

use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;
use SakuraLandGroup\Essentials\util\Util;
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

            $player->sendTip(Util::colorizeText($message));
        }
    }
}
