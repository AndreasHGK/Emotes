<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes\event;

use AndreasHGK\Emotes\emote\Emote;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;

class PlayerEmoteEvent extends PlayerEvent implements Cancellable {

    use CancellableTrait;

    /** @var Emote */
    private $emote;

    public function __construct(Emote $emote) {
        $this->player = $emote->getPlayer();
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