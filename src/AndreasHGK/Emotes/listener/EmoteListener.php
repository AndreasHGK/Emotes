<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\listener;

use AndreasHGK\Emotes\emote\Emote;
use AndreasHGK\Emotes\emote\EmoteIds;
use AndreasHGK\Emotes\Emotes;
use AndreasHGK\Emotes\session\SessionManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\EmotePacket;

class EmoteListener implements Listener {

    /** @var SessionManager */
    private $sessionManager;
    /** @var Emotes */
    private $plugin;

    public function __construct() {
        $this->sessionManager = SessionManager::getInstance();
        $this->plugin = Emotes::getInstance();
    }

    /**
     * Listens for players sending the EmotePacket
     *
     * @param DataPacketReceiveEvent $event
     */
    public function onEmote(DataPacketReceiveEvent $event) : void {
        $packet = $event->getPacket();
        if(!$packet instanceof EmotePacket) return;
        $player = $event->getOrigin()->getPlayer();

        $session = $this->sessionManager->getSession($player);
        if(!$player->hasPermission("emotes.perform")) {
            $player->sendMessage($this->plugin->getPermissionMessage());
            return;
        }
        if(!$session->hasEmote($packet->getEmoteId())) {
            return; //this should usually not happen unless the EmoteListPacket (or EmotePacket) was modified
        }
        if($session->hasActiveCooldown()) {
            $player->sendMessage(sprintf($this->plugin->getCooldownMessage(), $session->getRemainingCooldown()));
            return;
        }

        $emote = Emote::fromPacket($player, $packet);
        $emote->broadcast();
        $session->updateLastEmoteTime();
    }

}