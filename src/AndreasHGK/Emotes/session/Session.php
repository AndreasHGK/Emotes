<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\session;

use pocketmine\Player;

class Session {

    /** @var Player */
    private $player;
    /** @var array */
    private $emotes = [];
    /** @var float */
    private $cooldownTime;
    /** @var float */
    private $lastEmoteTime = 0;

    public function __construct(Player $player, float $cooldownTime) {
        $this->player = $player;
        $this->cooldownTime = $cooldownTime;
    }

    /**
     * Get the player that the session is for
     *
     * @return Player
     */
    public function getPlayer() : Player {
        return $this->player;
    }

    /**
     * Get an array of the IDs of the emotes that this player has
     *
     * @return array
     */
    public function getEmotes() : array {
        return $this->emotes;
    }

    /**
     * Set which emotes the player can do
     *
     * @param array $emotes
     */
    public function setEmotes(array $emotes) : void {
        $this->emotes = $emotes;
    }

    /**
     * Check if a player has an emote
     *
     * @param string $id
     * @return bool
     */
    public function hasEmote(string $id) : bool {
        return in_array($id, $this->emotes);
    }

    /**
     * Get the time that a player should have to wait between doing emotes in seconds
     *
     * @return float
     */
    public function getCooldownTime() : float {
        return $this->cooldownTime;
    }

    /**
     * Set the cooldown between emotes
     *
     * @param float $cooldownTime cooldown in seconds
     */
    public function setCooldownTime(float $cooldownTime) : void {
        $this->cooldownTime = $cooldownTime;
    }

    /**
     * Get the time when a player last used an emote
     *
     * @return float
     */
    public function getLastEmoteTime() : float {
        return $this->lastEmoteTime;
    }

    /**
     * Update the last time a player did an emote to the current time
     */
    public function updateLastEmoteTime() : void {
        $this->lastEmoteTime = microtime(true);
    }

    /**
     * Check if the player currently has an active cooldown for emotes
     *
     * @return bool
     */
    public function hasActiveCooldown() : bool {
        return $this->getLastEmoteTime() + $this->cooldownTime > microtime(true);
    }

    /**
     * Get the remaining cooldown time that the player has on emotes
     *
     * @return float
     */
    public function getRemainingCooldown() : float {
        return max($this->getLastEmoteTime() + $this->getCooldownTime() - microtime(true), 0);
    }

}