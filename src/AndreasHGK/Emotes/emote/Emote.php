<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\emote;

use AndreasHGK\Emotes\event\PlayerEmoteEvent;
use AndreasHGK\Emotes\session\SessionManager;
use pocketmine\entity\Human;
use pocketmine\network\mcpe\protocol\EmotePacket;
use pocketmine\player\Player;

class Emote {

    /**
     * Create an Emote object from a packet
     *
     * @param Human $sender
     * @param EmotePacket $emotePacket
     * @return static
     */
    public static function fromPacket(Human $sender, EmotePacket $emotePacket) : self {
        return new self($sender, $emotePacket->getEmoteId(), $emotePacket->getFlags());
    }

    /**
     * Create an Emote object
     *
     * @param Human $entity the player that is performing the emote
     * @param string $emoteId the ID of the emote
     * @param int $flags modify the behaviour of the emote
     * @return static
     */
    public static function create(Human $entity, string $emoteId, int $flags = 0) : self {
        return new self($entity, $emoteId, $flags);
    }

    /** @var Human */
    private $entity;
    /** @var string */
    private $emoteId;
    /** @var int */
    private $flags;

    public function __construct(Human $entity, string $emoteId, int $flags = 0) {
        $this->entity = $entity;
        $this->emoteId = $emoteId;
        $this->flags = $flags;
    }

    /**
     * Return the emote as a packet that can be sent to players to display the emote for them
     *
     * @return EmotePacket
     */
    public function asPacket() : EmotePacket {
        return EmotePacket::create($this->getEntityId(), $this->getEmoteId(), $this->getFlags());
    }

    /**
     * Get the entity ID of the player that is doing the Emote
     * @see EmoteIds
     *
     * @return int
     */
    public function getEntityId() : int {
        return $this->entity->getId();
    }

    /**
     * Get the sender of the Emote
     *
     * @return Human
     */
    public function getEntity() : Human {
        return $this->entity;
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
     * Broadcast the packet to a list of players, or in the world of a player
     *
     * @param Player[] $players the players you want to broadcast the packet to
     * @param bool $silent whether or not to call an event for the emote
     */
    public function broadcast(array $players = [], bool $silent = false) : void {
        if(empty($players)) {
            $players = $this->entity->getViewers();
        }

        $event = new PlayerEmoteEvent($this);
        if(!$silent) $event->call();
        if($event->isCancelled()) return;
        $emote = $event->getEmote();

        $packet = $emote->asPacket();
        foreach($players as $player) {
            $player->getNetworkSession()->sendDataPacket($packet);
        }
    }

}