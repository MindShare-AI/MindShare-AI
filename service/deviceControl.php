<?php
/**
@file     control/deviceControl
@author   Florian Lopitaux
@version  0.1
@summary  Class to manage http request related to the device.

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

namespace service;

use data\{DeviceAccess};
use model\Device;

require_once 'data/DeviceAccess.php';

final class deviceControl {
    // FIELDS
    private DeviceAccess $dbAccess;
    private string $requestMethod;


    // CONSTRUCTOR
    public function __construct(array $db_accounts, string $requestMethod) {
        $this->dbAccess = new DeviceAccess($db_accounts['device_identifier'], $db_accounts['device_password']);
        $this->requestMethod = $requestMethod;
    }


    // PUBLIC METHODS
    public function processRequest(array $uriParameters, array $postParams, array $getParams) : void {
        if ($this->requestMethod === 'GET') {
            if (count($uriParameters) !== 1) {
                $device = $this->dbAccess->getDevice($uriParameters[0]);

                http_response_code(200);
                if (is_null($device)) {
                    echo json_encode(array());
                } else {
                    echo $device->toJson();
                }

            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'Bad uri parameters format'));
            }

        } else if ($this->requestMethod === 'POST') {
            if (array_key_exists('uuid', $postParams)) {
                $this->addDevice($postParams['uuid']);

                http_response_code(200);
                echo json_encode(array('response' => 'ok'));

            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'wrong post parameters'));
            }

        } else {
            http_response_code('404');
            echo json_encode(array('response' => 'http request method not allowed for "/device"'));
        }
    }


    // PRIVATE METHODS
    private function addDevice(string $uuid) : void {
        if (!$this->dbAccess->isExist($uuid)) {
            $newDevice = new Device($uuid);
            $this->dbAccess->registerDevice($newDevice);

        } else {
            http_response_code(403);
            echo json_encode(array('response' => 'device already registered'));
            die();
        }
    }
}
