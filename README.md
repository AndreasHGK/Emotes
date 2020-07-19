# Emotes
 Emote support for pocketmine

This plugin adds emotes into pocketmine so that everyone can see the emotes that you are doing!

**Why is this useful?**

By default pocketmine does not have support for emotes.
This means that you can see yourself perform an emote, but other players can not see you performing them.
This plugin fixes this problem and also adds a few extra features.

**Permission**

Players require a permission `emotes.perform` to perform an emote.
If they do not have this permission, others will not see the emote and the player will get a message telling them they do not have the permission.
You can change the permission message in the plugin configuration.

**Cooldowns**

You can set an emote cooldown for players in the plugin configuration.
By default this is set to 2 seconds, but you can change this to anything you want or even just disabe it.
When the cooldown is active, it prevents other players from being able to see the emotes that you do and you will get a message telling you that you currently have an active emote cooldown.
This message is also configurable in `config.yml`.

## API

The plugin also has an API to make it easy for developers to use emotes in plugins. 

### Emote event

Whenever a player performs an emote, an event will be called for this emote.
This event is `AndreasHGK\Emotes\event\EmoteEvent`.
You are able to cancel the event and also change the flags and emote ID.

### Sending emotes
With this plugin, you can also easily make a player (or any entity that extends the Human class, so even human slappers) perform an emote!
Here is how you can do it.

First of all, you need to import the correct classes. If you just want to be able to send an emote, you will only need these 2 classes. The `Emote` class is the class that allows you to send emotes and the `EmoteIds` class contains some emote IDs.
```php
use AndreasHGK\Emotes\emote\Emote;
use AndreasHGK\Emotes\emote\EmoteIds;
```

To actually send the emote, all you need to do is this.
This example shows you how to send the "over there" emote, but you can of course change it to any of the emotes in the EmoteIds class or use the id of another emote you have.
It is very simple, you first create an Emote object and you then call the `broadcast()` function on it, which will display the emote to everyone who can see the performer.
The performer can be any entity that extends the Human class.
```php
Emote::create($performer, EmoteIds::OVER_THERE, Emote::FLAG_SERVER)->broadcast();
```

The `broadcast()` function has 2 optional arguments.
The first is an array of the players that you want to broadcast the emote to.
Leave it empty to broadcast to everyone who can see the entity.
The second argument is by default false and it controls whether or not an event will be called for the emote.
If you don't want an event to be called be sure to change this to false.

### Changing the cooldown time per player

You can also change the cooldown time per player, allowing you to do things such as giving certain people a lower cooldown time.
First of all, you'll need to import this class.
```php
use AndreasHGK\Emotes\session\SessionManager;
```

Then all you need to do is get the session for a player and change its cooldown time.
In the following example the cooldown time for the player is changed to 5 seconds.
Be sure to make this variable a float.
```php
SessionManager::getInstance()->getSession($player)->setCooldownTime(5.0);
```