<?php
/**
 * ██╗░░██╗██╗██████╗░░█████╗░████████╗███████╗░█████╗░███╗░░░███╗
 * ██║░░██║██║██╔══██╗██╔══██╗╚══██╔══╝██╔════╝██╔══██╗████╗░████║
 * ███████║██║██████╔╝██║░░██║░░░██║░░░█████╗░░███████║██╔████╔██║
 * ██╔══██║██║██╔══██╗██║░░██║░░░██║░░░██╔══╝░░██╔══██║██║╚██╔╝██║
 * ██║░░██║██║██║░░██║╚█████╔╝░░░██║░░░███████╗██║░░██║██║░╚═╝░██║
 * ╚═╝░░╚═╝╚═╝╚═╝░░╚═╝░╚════╝░░░░╚═╝░░░╚══════╝╚═╝░░╚═╝╚═╝░░░░░╚═╝
 * TopLuck-HiroTeam By RomainSav
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://www.gnu.org/licenses/
 *
 *
 * GitHub: https://github.com/HiroshimaTeam/TopLuck-HiroTeam
 */
namespace hiroteam\romainsav\events;

use hiroteam\romainsav\models\LuckyPlayerModel;
use hiroteam\romainsav\TopLuck;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class PlayersEvents implements Listener
{
    /**
     * @var TopLuck
     */
    private $main;

    /**
     * PlayersEvents constructor.
     * @param TopLuck $main
     */
    public function __construct(TopLuck $main)
    {
        $this->main = $main;
        $main->getServer()->getPluginManager()->registerEvents($this, $main);
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onJoin(PlayerJoinEvent $event)
    {
        array_push($this->main->allLuckyPlayersModels, new LuckyPlayerModel($this->main, $event->getPlayer()->getName(), $this->main->getProvider()->getAllBlocks($event->getPlayer()->getName()), $this->main->getProvider()->getRareBlocks($event->getPlayer()->getName()), $this->main->getProvider()->getCreationTime($event->getPlayer()->getName())));
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event)
    {
        $this->main->getProvider()->savePlayerInstance($event->getPlayer()->getName());
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event)
    {
        $model = $this->main->getManager()->getLuckyPlayerInstance($event->getPlayer()->getName());

        if (!is_null($model)) {

            $model->mineBlock($event->getBlock());

        }

        $this->main->getManager()->updateLuckyPlayerInstance($event->getPlayer()->getName());

    }
}
