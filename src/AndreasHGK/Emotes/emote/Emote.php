<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\emote;

use AndreasHGK\Emotes\event\PlayerEmoteEvent;
use AndreasHGK\Emotes\session\SessionManager;
use pocketmine\network\mcpe\protocol\EmotePacket;
use pocketmine\player\Player;

class Emote {

    /**
     * Create an Emote object from a packet
     *
     * @param Player $sender
     * @param EmotePacket $emotePacket
     * @return static
     */
    public static function fromPacket(Player $sender, EmotePacket $emotePacket) : self {
        return new self($sender, $emotePacket->getEmoteId(), $emotePacket->getFlags());
    }

    /** @var Player */
    private $player;
    /** @var string */
    private $emoteId;
    /** @var int */
    private $flags;

    public function __construct(Player $player, string $emoteId, int $flags = 0) {
        $this->player = $player;
        $this->emoteId = $emoteId;
        $this->flags = $flags;
    }

    /**
     * Return the emote as a packet that can be sent to players to display the emote for them
     *
     * @return EmotePacket
     */
    public function asPacket() : EmotePacket {
        return EmotePacket::create($this->getEntityId(), $this->getEmoteId(), $this->flags);
    }

    /**
     * Get the entity ID of the player that is doing the Emote
     *
     * @return int
     */
    public function getEntityId() : int {
        return $this->player->getId();
    }

    /**
     * Get the sender of the Emote
     *
     * @return Player
     */
    public function getPlayer() : Player {
        return $this->player;
    }

    /**
     * Get the ID of the emote that is being sent
     *
     * @return string
     */
    public function getEmoteId() : string {
        return $this->emoteId;
    }

    /**
     * Change the ID of the emote
     *
     * @param string $emoteId
     */
    public function setEmoteId(string $emoteId) : void {
        $this->emoteId = $emoteId;
    }

    /**
     * Get the flags that change the behaviour of the packet
     * @see EmotePacket
     *
     * @return int
     */
    public function getFlags() : int {
        return $this->flags;
    }

    /**
     * Change the flags of the emote
     * @see EmotePacket
     *
     * @param int $flags
     */
    public function setFlags(int $flags) : void {
        $this->flags = $flags;
    }

    /**
     * Check if the sender is able to use the emote
     * (this checks if they have it set in their emote list)
     *
     * @return bool
     */
    public function canUse() : bool {
        $sessionManager = SessionManager::getInstance();
        if(!$sessionManager->hasSession($this->getPlayer())) return true; //if for some reason the session is not there
        return SessionManager::getInstance()->getSession($this->getPlayer())->hasEmote($this->getEmoteId());
    }

    /**
     * Broadcast the packet to a list of players, or in the world of a player
     * The sender will be automatically ignored
     *
     * @param Player[] $players the players you want to broadcast the packet to
     * @param bool $silent whether or not to call an event for the emote
     */
    public function broadcast(array $players = [], bool $silent = false) : void {
        if(empty($players)) {
            $players = $this->player->getWorld()->getPlayers();
        }

        $event = new PlayerEmoteEvent($this);
        if(!$silent) $event->call();
        if($event->isCancelled()) return;
        $emote = $event->getEmote();

        $packet = $emote->asPacket();
        foreach($players as $player) {
            if($players === $emote->getPlayer()) continue;
            $player->getNetworkSession()->sendDataPacket($packet);
        }
    }

}