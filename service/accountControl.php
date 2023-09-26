<?php
/**
@file     service/AccountControl
@author   Florian Lopitaux
@version  0.1
@summary  Class to manage http request related to the accounts.

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

require_once 'BaseController.php';

use data\AccountAccess;
require_once 'data/AccountAccess.php';

final class accountControl extends BaseController {
    // CONSTRUCTOR
    public function __construct(array $config, string $requestMethod) {
        parent::__construct($requestMethod);
        $this->dbAccess = new AccountAccess($config['account_identifier'], $config['account_password']);
    }


    // PUBLIC METHODS
    public function processRequest(array $uriParameters, array $postParams, array $getParams) : void {
        if ($this->requestMethod === 'GET') {
            if (count($uriParameters) > 2) {
                http_response_code(400);
                echo json_encode(array('response' => 'wrong post parameters'));
                die();

            } else if (count($uriParameters) === 0) {
                $response = $this->getAllAccounts();
            } else {
                $response = $this->getAccount($uriParameters);
            }

        } else if ($this->requestMethod === 'POST') {
            $response = $this->addAccount($postParams);

        } else if ($this->requestMethod === 'DELETE') {
            if (count($uriParameters) === 1 || count($uriParameters) === 2) {
                $response = $this->deleteAccount($uriParameters);

            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'wrong post parameters'));
                die();
            }
        } else {
            http_response_code(404);
            echo json_encode(array('response' => 'http request method not allowed for "/account"'));
            die();
        }

        http_response_code($response[0]);
        echo json_encode($response[1]);
    }


    // PRIVATE METHODS
    private function getAllAccounts() : array {
        $accounts = $this->dbAccess->getAllAccounts();
        $response = array(200, array());

        foreach ($accounts as $currentAccount) {
            $response[1] = $currentAccount->toJson();
        }

        return $response;
    }

    private function getAccount(array $accountData) : array {
        if (count($accountData) === 1) {
            $account = $this->dbAccess->getAccountByID($accountData[0]);
        } else {
            $account = $this->dbAccess->getAccountByName($accountData[0], $accountData[1]);
        }

        $response = array();
        if (is_null($account)) {
            $response[] = 202;
            $response[] = array('response' => "Given account can't be remove");
        } else {
            $response[] = 200;
            $response[] = array('response' => 'ok');
        }

        return $response;
    }

    private function addAccount(array $accountData) : array {
        if (in_array('last_name', $accountData) && in_array('first_name', $accountData)) {
            $account = $this->dbAccess->getAccountByName($accountData['last_name'], $accountData['first_name']);

            if (is_null($account)) {
                $this->dbAccess->addAccount($accountData);
                return array(200, array('response' => 'ok'));

            } else {
                return array(202, array('response' => 'Account already exists'));
            }

        } else {
            return array(400, array('response' => "Post parameters doesn't contains mandatory parameters."));
        }
    }

    private function deleteAccount(array $accountData) : array {
        if (count($accountData) === 1) {
            $isDeleted = $this->dbAccess->deleteAccountByID($accountData[0]);
        } else {
            $isDeleted = $this->dbAccess->deleteAccountByName($accountData[0], $accountData[1]);
        }

        $response = array();
        if ($isDeleted) {
            $response[] = 200;
            $response[] = array('response' => 'ok');
        } else {
            $response[] = 202;
            $response[] = array('response' => "Given account can't be remove");
        }

        return $response;
    }
}
