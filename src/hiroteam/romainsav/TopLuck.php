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
namespace hiroteam\romainsav;

use hiroteam\romainsav\commands\topluckCommand;
use hiroteam\romainsav\events\PlayersEvents;
use hiroteam\romainsav\managers\FormsManager;
use hiroteam\romainsav\managers\MysqlManager;
use hiroteam\romainsav\managers\TopLuckManager;
use hiroteam\romainsav\models\LuckyPlayerModel;
use hiroteam\romainsav\providers\MysqlProvider;
use hiroteam\romainsav\providers\SqliteProvider;
use pocketmine\plugin\PluginBase;

class TopLuck extends PluginBase
{

    /**
     * @var MysqlProvider|SqliteProvider
     */
    private $provider;

    /**
     * @var TopLuckManager
     */
    private $manager;

    /**
     * @var MysqlManager
     */
    private $mysqlManager;

    /**
     * @var FormsManager
     */
    private $formsManager;

    /**
     * @var LuckyPlayerModel[]
     */
    public $allLuckyPlayersModels = [];

    public function onLoad()
    {
        $this->manager = new TopLuckManager($this);
        $this->mysqlManager = new MysqlManager($this);
        $this->formsManager = new FormsManager($this);
    }

    public function onEnable()
    {
        $this->saveDefaultConfig();
        $this->loadProvider();
        $this->loadListeners();
        $this->loadCommands();
    }

    public function onDisable()
    {
        $this->getProvider()->saveAllLuckyPlayersInstances();
    }

    /**
     * @return TopLuckManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return MysqlManager
     */
    public function getMysqlManager()
    {
        return $this->mysqlManager;
    }

    /**
     * @return FormsManager
     */
    public function getFormsManager()
    {
        return $this->formsManager;
    }

    /**
     * @return MysqlProvider|SqliteProvider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    private function loadProvider(): void
    {
        switch ($this->getConfig()->get('provider')) {

            case 'mysql':
                $this->provider = new MysqlProvider($this);
                break;

            case 'sqlite':
                $this->provider = new SqliteProvider($this);
                break;

            default:
                $this->getLogger()->error("An error occurred. Your provider has been badly defined");
                $this->getServer()->disablePlugins();
                break;

        }
    }

    private function loadListeners(): void
    {
        new PlayersEvents($this);
    }

    private function loadCommands(): void
    {
        $this->getServer()->getCommandMap()->registerAll('HiroTeam', [
            new topluckCommand($this)
        ]);
    }
}
