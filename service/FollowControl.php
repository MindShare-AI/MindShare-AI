<?php
/**
@file     control/FollowControl
@author   Florian Lopitaux
@version  0.1
@summary  Class to manage http request related to the follows.

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

use data\{AccountAccess, FollowAccess};
require_once 'data/AccountAccess.php';
require_once 'data/FollowAccess.php';

final class FollowControl extends BaseController {
    // FIELDS
    private AccountAccess $accountAccess;


    // CONSTRUCTOR
    public function __construct(array $config, string $requestMethod) {
        parent::__construct($requestMethod);

        $this->dbAccess = new FollowAccess($config['account_identifier'], $config['account_password']);
        $this->accountAccess = new AccountAccess($config['account_identifier'], $config['account_password']);
    }


    // PUBLIC METHODS
    public function processRequest(array $uriParameters, array $postParams): void {
        if ($this->requestMethod === 'GET') {
            if (count($uriParameters) === 2 && $uriParameters[0] === 'stats') {
                $response = $this->statsRequest($uriParameters[1]);

            } else if (count($uriParameters) === 1) {
                $response = $this->getRequest($uriParameters[1]);

            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'Bad uri parameters format'));
                die();
            }
        } else if ($this->requestMethod === 'POST') {
            if (count($postParams) === 2 &&
                array_key_exists('follower', $postParams) &&
                array_key_exists('following', $postParams)) {

                $response = $this->addFollowLink($postParams['follower'], $postParams['following']);

            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'wrong post parameters'));
                die();
            }

        } else {
            http_response_code(404);
            echo json_encode(array('response' => 'http request method not allowed for "/follow"'));
            die();
        }

        http_response_code($response[0]);
        echo json_encode($response[1]);
    }


    // PRIVATE METHODS
    private function statsRequest(int $idAccount) : array {
        $followersCount = $this->dbAccess->getFollowersCount($idAccount);
        $followingCount = $this->dbAccess->getFollowingAccounts($idAccount);

        $response = array(
            'followers' => $followersCount,
            'following' => $followingCount
        );

        return array(200, $response);
    }

    private function getRequest(int $idAccount) : array {
        $followers = $this->dbAccess->getFollowers($idAccount);
        $following = $this->dbAccess->getFollowingAccounts($idAccount);

        $response = array(
            'followers' => array(),
            'following' => array()
        );

        foreach ($followers as $idAccount) {
            $currentAccount = $this->accountAccess->getAccountByID($idAccount);
            $response['followers'][] = $currentAccount->toJson();
        }

        foreach ($following as $idAccount) {
            $currentAccount = $this->accountAccess->getAccountByID($idAccount);
            $response['following'][] = $currentAccount;
        }

        return array(200, $response);
    }

    private function addFollowLink(int $idFollower, int $idFollowed) : array {
        $this->dbAccess->addFollowEntity($idFollower, $idFollowed);
        return array(200, array('response' => 'ok'));
    }
}
