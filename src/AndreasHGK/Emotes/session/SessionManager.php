<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\session;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class SessionManager {

    use SingletonTrait;

    /** @var Session[] */
    private $sessions = [];

    /**
     * Get every active session
     *
     * @return Session[]|array
     */
    public function getSessions() : array {
        return $this->sessions;
    }

    /**
     * Get a session for a given player
     *
     * @param Player $player
     * @return Session
     */
    public function getSession(Player $player) : Session {
        return $this->sessions[spl_object_hash($player)];
    }

    /**
     * Check if a player has a session
     *
     * @param Player $player
     * @return bool
     */
    public function hasSession(Player $player) : bool {
        return isset($this->sessions[spl_object_hash($player)]);
    }

    /**
     * Create a session for a player
     *
     * @param Player $player
     * @throws SessionException
     */
    public function makeSession(Player $player) : void {
        if($this->hasSession($player)) throw new SessionException("A session for the given player already exists");
        $this->sessions[spl_object_hash($player)] = new Session($player);
    }

    /**
     * Destroy a session for a player
     *
     * @param Player $player
     * @throws SessionException
     */
    public function destroySession(Player $player) : void {
        if(!$this->hasSession($player)) throw new SessionException("A session for the given player does not exist");
        unset($this->sessions[spl_object_hash($player)]);
    }

}