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
namespace hiroteam\romainsav\models;

use hiroteam\romainsav\TopLuck;
use pocketmine\block\Block;

class LuckyPlayerModel
{

    /**
     * @var TopLuck
     */
    private $main;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $createdAt;

    /**
     * @var int
     */
    private $allBlocks;

    /**
     * @var int
     */
    private $rareBlocks;

    /**
     * LuckyPlayerModel constructor.
     * @param TopLuck $main
     * @param string $name
     * @param int $allBlocks
     * @param int $rareBlocks
     * @param int $createdAt
     */
    public function __construct(TopLuck $main, string $name, int $allBlocks, int $rareBlocks, int $createdAt)
    {
        $this->main = $main;
        $this->name = $name;
        $this->allBlocks = $allBlocks;
        $this->rareBlocks = $rareBlocks;
        if ($createdAt === 0) {
            $this->createdAt = time();
        } else {
            $this->createdAt = $createdAt;
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCreatedTime(): int
    {
        return $this->createdAt;
    }

    public function updateCreationTime(): void
    {
        $this->createdAt = time();
    }

    /**
     * @return int
     */
    public function getAllMinedBlocks(): int
    {
        return $this->allBlocks;
    }

    /**
     * @return int
     */
    public function getAllRareMinedBlocks(): int
    {
        return $this->rareBlocks;
    }

    /**
     * @param Block $block
     */
    public function mineBlock(Block $block): void
    {

        foreach ($this->main->getConfig()->getAll()['raresBlocks'] as $param) {

            if ($param === $block->getId() . ":" . $block->getDamage()) {

                $this->rareBlocks++;
                return;

            }

        }

        foreach ($this->main->getConfig()->getAll()['allBlocks'] as $param) {

            if ($param === $block->getId() . ":" . $block->getDamage()) {

                $this->rareBlocks++;
                return;

            }

        }

    }

    /**
     * @return string
     */
    public function getPercentage(): string
    {
        if ($this->allBlocks === 0 && $this->rareBlocks === 0) {
            $result = 0;
        } elseif ($this->allBlocks === 0 && $this->rareBlocks > 0) {
            $result = 100;
        } else {
            $result = $this->rareBlocks * 100 / ($this->allBlocks + $this->rareBlocks);
        }
        return round($result, 2, PHP_ROUND_HALF_DOWN) . " %%";
    }

}
