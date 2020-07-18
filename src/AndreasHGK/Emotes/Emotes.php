<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes;

use AndreasHGK\Emotes\listener\EmoteListener;
use AndreasHGK\Emotes\listener\SessionListener;
use pocketmine\plugin\PluginBase;

class Emotes extends PluginBase {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(new SessionListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new EmoteListener(), $this);
    }

}
