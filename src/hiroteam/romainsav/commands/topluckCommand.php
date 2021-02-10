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
namespace hiroteam\romainsav\commands;

use hiroteam\romainsav\TopLuck;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class topluckCommand extends Command
{

    /**
     * @var TopLuck
     */
    private $main;

    /**
     * topluckCommand constructor.
     * @param TopLuck $main
     */
    public function __construct(TopLuck $main)
    {
        parent::__construct('topluck', 'Find the cheaters on your server', '/topluck');
        $this->setPermission('topluck.command');
        $this->main = $main;
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($this->main->isEnabled()) {

            if ($sender instanceof Player) {

                if ($sender->hasPermission('topluck.command')) {

                    $this->main->getFormsManager()->allPlayersUI($sender);

                } else {

                    $sender->sendMessage("§cYou do not have permission to use this command.");

                }

            } else {

                $sender->sendMessage("§cPlease execute this command in-game");

            }

        }

    }
}
