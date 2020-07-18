<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\session;

use pocketmine\Player;

class Session {

    /** @var Player */
    private $player;
    /** @var array */
    private $emotes = [];

    public function __construct(Player $player) {
        $this->player = $player;
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

}