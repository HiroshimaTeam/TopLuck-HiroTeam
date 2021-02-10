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

use hiroteam\romainsav\models\LuckyPlayerModel;
use hiroteam\romainsav\TopLuck;

class TopLuckManager
{

    /**
     * @var TopLuck
     */
    private $main;

    /**
     * TopLuckManager constructor.
     * @param TopLuck $main
     */
    public function __construct(TopLuck $main)
    {
        $this->main = $main;
    }

    /**
     * @param string $name
     * @return LuckyPlayerModel|null
     */
    public function getLuckyPlayerInstance(string $name): ?LuckyPlayerModel
    {
        foreach ($this->main->allLuckyPlayersModels as $index => $model) {

            if ($model->getName() === $name) {

                return $model;
            }

        }

        return null;
    }

    /**
     * @param string $name
     */
    public function updateLuckyPlayerInstance(string $name): void
    {
        foreach ($this->main->allLuckyPlayersModels as $index => $model) {

            if ($model->getName() === $name) {

                if ($model->getCreatedTime() > (time() + ($this->main->getConfig()->get('playerStatsUpdate') * 60))) {

                    $model->updateCreationTime();

                }

            }

        }
    }

}
