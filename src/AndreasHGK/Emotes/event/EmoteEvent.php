<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\event;

use AndreasHGK\Emotes\emote\Emote;
use pocketmine\event\Cancellable;
use pocketmine\event\Event;

class EmoteEvent extends Event implements Cancellable {

    /** @var Emote */
    private $emote;

    public function __construct(Emote $emote) {
        $this->emote = $emote;
    }

    /**
     * Get the emote that is being broadcasted
     *
     * @return Emote
     */
    public function getEmote() : Emote {
        return $this->emote;
    }

}