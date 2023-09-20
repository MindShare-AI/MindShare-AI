<?php
/**
@file     data/FollowAccess.php
@author   Florian Lopitaux
@version  0.1
@summary  Class to interact with the Follow sql table.

-------------------------------------------------------------------------

Copyright (C) 2023 MindShare-AI

Use of this software is governed by the GNU Public License, version 3.

MindShare-API is free RESTFUL API: you can use it under the terms
of the GNU General Public License as published by the
Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

MindShare-API is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with MindShare-API. If not, see <http://www.gnu.org/licenses/>.

This banner notice must not be removed.

-------------------------------------------------------------------------
 */

namespace data;

require_once 'DataAccess.php';

use model\Device;
require_once 'model/Device.php';

final class DeviceAccess extends DataAccess {
    // CONSTRUCTOR
    /**
     * The constructor to instantiate a DeviceAccess object.
     * Connect with the database.
     */
    public function __construct() {
        // Loads the .ini file
        $db_accounts = parse_ini_file('db_account.ini');

        if (!$db_accounts) { // file doesn't found or not parsable
            http_response_code(500);
            echo json_encode([]);
            die();
        }

        parent::__construct($db_accounts['device_identifiers'], $db_accounts['device_password']);
    }


    // METHODS
    /**
     * Returns all devices registered in the database.
     *
     * @return array The devices.
     */
    public function getAllDevices() : array {
        $devices = array();

        // send sql request
        $this->prepareQuery('SELECT * FROM DEVICE');
        $this->executeQuery(array());

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $devices[] = new Device($row['uuid']);
        }

        return $devices;
    }

    /**
     * Returns the data of a given device.
     *
     * @param int $idDevice The identifier of the device.
     *
     * @return Device|null The device's data or null if the device doesn't exist.
     */
    public function getDevice(int $idDevice) : ?Device {
        // send sql request
        $this->prepareQuery('SELECT * FROM DEVICE WHERE uuid = ?');
        $this->executeQuery(array($idDevice));

        // get and check the response
        $result = $this->getQueryResult();

        if (count($result) == 0) {
            return null;
        } else {
            return new Device($result[0]['uuid']);
        }
    }

    /**
     * Checks if a given device exist in the database.
     *
     * @param int $idDevice The uuid of the device to check.
     *
     * @return bool The boolean response.
     */
    public function isExist(int $idDevice) : bool {
        // send sql request
        $this->prepareQuery('SELECT * FROM DEVICE WHERE uuid = ?');
        $this->executeQuery(array($idDevice));

        // get the response
        $result = $this->getQueryResult();

        return count($result) > 0;
    }
}
