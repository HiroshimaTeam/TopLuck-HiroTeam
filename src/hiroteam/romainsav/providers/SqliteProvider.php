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
namespace hiroteam\romainsav\providers;

use hiroteam\romainsav\TopLuck;
use SQLite3;

class SqliteProvider implements IProvider
{

    /**
     * @var TopLuck
     */
    private $main;

    /**
     * @var SQLite3
     */
    private $sql;

    /**
     * SqliteProvider constructor.
     * @param TopLuck $main
     */
    public function __construct(TopLuck $main)
    {
        $this->main = $main;
        $this->sql = new SQLite3($main->getDataFolder() . "TopLuck.db");
        $this->sql->query("CREATE TABLE IF NOT EXISTS topluck (username TEXT PRIMARY KEY, allBlocks INT, rareBlocks INT, creationDate INT);");
    }

    public function saveAllLuckyPlayersInstances(): void
    {

        foreach ($this->main->allLuckyPlayersModels as $index => $model) {

            $req = $this->sql->prepare("INSERT OR REPLACE INTO topluck (username, allBlocks, rareBlocks, creationDate) VALUES (:username, :allBlocks, :rareBlocks, :creationDate);");
            $req->bindValue(':username', strtolower($model->getName()));
            $req->bindValue(':allBlocks', $model->getAllMinedBlocks());
            $req->bindValue(':rareBlocks', $model->getAllRareMinedBlocks());
            $req->bindValue(':creationDate', $model->getCreatedTime());
            $req->execute();

        }

    }

    /**
     * @param string $name
     */
    public function savePlayerInstance(string $name): void
    {
        $model = $this->main->getManager()->getLuckyPlayerInstance($name);

        if (!is_null($model)) {

            $req = $this->sql->prepare("INSERT OR REPLACE INTO topluck (username, allBlocks, rareBlocks, creationDate) VALUES (:username, :allBlocks, :rareBlocks, :creationDate);");
            $req->bindValue(':username', strtolower($model->getName()));
            $req->bindValue(':allBlocks', $model->getAllMinedBlocks());
            $req->bindValue(':rareBlocks', $model->getAllRareMinedBlocks());
            $req->bindValue(':creationDate', $model->getCreatedTime());
            $req->execute();
        }
    }

    /**
     * @param string $name
     * @return int
     */
    public function getAllBlocks(string $name): int
    {
        $res = $this->sql->query("SELECT * FROM topluck WHERE username LIKE '" . strtolower($name) . "'");
        $resArr = $res->fetchArray(SQLITE3_ASSOC);

        return empty($resArr) ? 0 : $resArr['allBlocks'];
    }

    /**
     * @param string $name
     * @return int
     */
    public function getRareBlocks(string $name): int
    {
        $res = $this->sql->query("SELECT * FROM topluck WHERE username LIKE '" . strtolower($name) . "'");
        $resArr = $res->fetchArray(SQLITE3_ASSOC);

        return empty($resArr) ? 0 : $resArr['rareBlocks'];
    }

    /**
     * @param string $name
     * @return int
     */
    public function getCreationTime(string $name): int
    {
        $res = $this->sql->query("SELECT * FROM topluck WHERE username LIKE '" . strtolower($name) . "'");
        $resArr = $res->fetchArray(SQLITE3_ASSOC);

        return empty($resArr) ? 0 : $resArr['creationDate'];
    }
}
