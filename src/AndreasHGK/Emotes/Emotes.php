<?php

declare(strict_types=1);

namespace AndreasHGK\Emotes;

use AndreasHGK\Emotes\listener\EmoteListener;
use AndreasHGK\Emotes\listener\SessionListener;
use AndreasHGK\Emotes\session\SessionManager;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginException;

class Emotes extends PluginBase {

    /** @var Emotes */
    private static $instance;

    /**
     * @return Emotes
     */
    public static function getInstance() : Emotes {
        if(!isset(self::$instance)) throw new PluginException("Trying to get the plugin instance while the plugin has not yet been loaded");
        return self::$instance;
    }

    /** @var string */
    private $cooldownMessage;
    /** @var string */
    private $permissionMessage;
    /** @var float */
    private $defaultCooldown = 2;

    /**
     * Get the default cooldown time for emotes
     *
     * @return float
     */
    public function getDefaultCooldown() : float {
        return $this->defaultCooldown;
    }

    /**
     * Set the default cooldown time for emotes
     * Cooldown times can be changed per session, this value is not synced with currently active sessions
     *
     * @param float $defaultCooldown
     */
    public function setDefaultCooldown(float $defaultCooldown) : void {
        $this->defaultCooldown = $defaultCooldown;
    }

    /**
     * Get the message that will show when players try to perform an emote when they have an active cooldown on emotes
     *
     * @return string
     */
    public function getCooldownMessage() : string {
        return $this->cooldownMessage;
    }

    /**
     * Get the message that will show to players when they try to perform an emote without the required permission
     *
     * @return string
     */
    public function getPermissionMessage() : string {
        return $this->permissionMessage;
    }

    /**
     * Check if the config does not have any missing keys
     *
     * @return bool
     */
    public function validateConfig() : bool {
        $config = $this->getConfig()->getAll();
        $configValues = [
            "cooldown-time",
            "cooldown-message",
            "permission-message",
        ];
        foreach($configValues as $configValue) {
            if(!isset($config[$configValue])) return false;
        }
        return true;
    }

    public function onLoad() {
        self::$instance = $this;

        $permissionManager = PermissionManager::getInstance();
        $permissionManager->addPermission(new Permission("emotes.perform", "the permission required to perform emotes", Permission::DEFAULT_TRUE));

        if(!$this->validateConfig()) {
            $this->getLogger()->notice("The plugin configuration file has one or more missing values. The plugin will use the default values for the missing options.");
        }
        $config = $this->getConfig();
        SessionManager::getInstance()->setDefaultCooldown($config->get("cooldown-time", 2));
        $this->cooldownMessage = $config->get("cooldown-message", "§cYou cannot perform another emote for another %.2f second(s).");
        $this->permissionMessage = $config->get("permission-message", "§cYou do not have permission to perform emotes.");
    }

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(new SessionListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new EmoteListener(), $this);
    }

}
