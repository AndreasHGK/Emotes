<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\event;

use AndreasHGK\Emotes\emote\Emote;
use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\Player;

class EmoteEvent extends Event implements Cancellable {

    /** @var Emote */
    private $emote;
    /** @var Player[] */
    private $viewers;

    public function __construct(Emote $emote, array $viewers) {
        $this->emote = $emote;
        $this->viewers = $viewers;
    }

    /**
     * Get the emote that is being broadcasted
     *
     * @return Emote
     */
    public function getEmote() : Emote {
        return $this->emote;
    }

    /**
     * Get the players that will see the emote
     *
     * @return Player[]
     */
    public function getViewers() : array {
        return $this->viewers;
    }

    /**
     * Set the players that will see the emote
     *
     * @param array $viewers
     */
    public function setViewers(array $viewers) : void {
        $this->viewers = $viewers;
    }

}