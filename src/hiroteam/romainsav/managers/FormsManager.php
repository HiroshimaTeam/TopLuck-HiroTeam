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
namespace hiroteam\romainsav\managers;

use hiroteam\romainsav\forms\SimpleForm;
use hiroteam\romainsav\TopLuck;
use pocketmine\Player;

class FormsManager
{

    /**
     * @var TopLuck
     */
    private $main;

    /**
     * FormsManager constructor.
     * @param TopLuck $main
     */
    public function __construct(TopLuck $main)
    {
        $this->main = $main;
    }

    /**
     * @param Player $player
     * @return SimpleForm
     */
    public function allPlayersUI(Player $player): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            $target = $data;
            if (is_null($target)) return;
            $this->onePlayerUI($player, $target);
        });

        $form->setTitle("- TopLuck-HiroTeam -");
        foreach ($this->main->getServer()->getOnlinePlayers() as $p) {

            $pInstance = $this->main->getManager()->getLuckyPlayerInstance($p->getName());

            $form->addButton($p->getName() . "\n§c" . $pInstance->getPercentage(), -1, '', $p->getName());

        }
        $form->sendToPlayer($player);
        return $form;
    }

    /**
     * @param Player $player
     * @param string $luckyPlayer
     * @return SimpleForm
     */
    public function onePlayerUI(Player $player, string $luckyPlayer): SimpleForm
    {
        $form = new SimpleForm(function (Player $player, $data = null) use ($luckyPlayer) {
            $target = $data;
            if (is_null($target)) return;

            if ($target === 'teleportation') {
                $pTarget = $this->main->getServer()->getPlayer($luckyPlayer);
                if ($pTarget instanceof Player) {
                    $player->teleport($pTarget->getPosition());
                    return;
                }
                return;
            }
            if ($target === 'back') {
                $this->allPlayersUI($player);
                return;
            }
        });
        $instance = $this->main->getManager()->getLuckyPlayerInstance($luckyPlayer);
        $form->setTitle("- TopLuck-HiroTeam -");
        $form->setContent("Player: ".$luckyPlayer . "\n\nPercentage: " . $instance->getPercentage() . "\n\nAll blocks: " . number_format($instance->getAllMinedBlocks(), 0, ".", "'") . "\n\nRare blocks: " . number_format($instance->getAllRareMinedBlocks(), 0, ".", "'"));
        $form->addButton("Teleportation", -1, '', 'teleportation');
        $form->addButton("Back", -1, '', 'back');
        $form->sendToPlayer($player);
        return $form;
    }
}
