<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\listener;

use AndreasHGK\Emotes\emote\Emote;
use AndreasHGK\Emotes\emote\EmoteIds;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\EmotePacket;

class EmoteListener implements Listener {

    /**
     * Listens for players sending the EmotePacket
     *
     * @param DataPacketReceiveEvent $event
     */
    public function onEmote(DataPacketReceiveEvent $event) : void {
        $packet = $event->getPacket();
        if(!$packet instanceof EmotePacket) return;
        $player = $event->getOrigin()->getPlayer();

        $emote = Emote::fromPacket($player, $packet);
        if(!$emote->canUse()) return;
        $emote->broadcast();
    }

}