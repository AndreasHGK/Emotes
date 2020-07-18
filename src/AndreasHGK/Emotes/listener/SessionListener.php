<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\listener;

use AndreasHGK\Emotes\session\SessionManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\EmoteListPacket;

class SessionListener implements Listener {

    /** @var SessionManager */
    private $sessionManager;

    public function __construct() {
        $this->sessionManager = SessionManager::getInstance();
    }

    /**
     * @param PlayerLoginEvent $event
     */
    public function onLogin(PlayerLoginEvent $event) : void {
        $this->sessionManager->makeSession($event->getPlayer());
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event) : void {
        $this->sessionManager->destroySession($event->getPlayer());
    }

    /**
     * @param DataPacketReceiveEvent $event
     */
    public function onEmoteList(DataPacketReceiveEvent $event) : void {
        $packet = $event->getPacket();
        if(!$packet instanceof EmoteListPacket) return;
        $player = $event->getPlayer();
        $session = $this->sessionManager->getSession($player);

        $emotes = [];
        foreach($packet->getEmoteIds() as $emoteId) {
            $emotes[] = $emoteId->toString(); //for some reason in EmotePacket the ID is a string and in EmoteListPacket it is a UUID object
        }
        $session->setEmotes($emotes);
    }

}