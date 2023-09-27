<?php
/**
@file     control/MessagesControl
@author   Florian Lopitaux
@version  0.1
@summary  Class to manage http request related to the chat messages.

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

use model\ChatMessage;
require_once 'model/ChatMessage.php';

use data\ChatMessageAccess;
require_once 'data/ChatMessageAccess.php';

final class MessagesControl extends BaseController {
    // CONSTRUCTOR
    public function __construct(array $config, string $requestMethod) {
        parent::__construct($requestMethod);
        $this->dbAccess = new ChatMessageAccess($config['device_identifier'], $config['device_password']);
    }


    // PUBLIC METHODS
    public function processRequest(array $uriParameters, array $postParams): void {
        if ($this->requestMethod === 'GET') {
            if (count($uriParameters) === 2) {
                $response = $this->getConversation($uriParameters[0], $uriParameters[1]);
            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'Bad uri parameters format'));
                die();
            }
        } else if ($this->requestMethod === 'POST') {
            $response = $this->addMessage($postParams);

        } else if ($this->requestMethod === 'DELETE') {
            if (count($uriParameters) === 1) {
                $response = $this->deleteSpecificMessage($uriParameters[0]);

            } else if (count($uriParameters) === 2) {
                $response = $this->deleteConversation($uriParameters[0], $uriParameters[1]);

            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'Bad uri parameters format'));
                die();
            }
        } else {
            http_response_code(404);
            echo json_encode(array('response' => 'http request method not allowed for "/messages"'));
            die();
        }

        http_response_code($response[0]);
        echo json_encode($response[1]);
    }


    // PRIVATE METHODS
    private function getConversation(int $idAccount, int $idDevice) : array {
        $conversations = $this->dbAccess->getConversation($idAccount, $idDevice);

        $response = array(200, array());

        foreach ($conversations as $message) {
            $response[1][] = $message->toArray();
        }

        return $response;
    }

    private function addMessage(array $postParams) : array {
        if (in_array('id_account', $postParams) && in_array('id_device', $postParams)) {
            $this->dbAccess->addMessage($postParams);
            $response = array(200, array('response' => 'ok'));
        } else {
            $response = array(400, array('Bad post parameters'));
        }

        return $response;
    }

    private function deleteSpecificMessage(int $idMessage) : array {
        $isDeleted = $this->dbAccess->deleteMessage($idMessage);

        if ($isDeleted) {
            $response = array(200, array('response' => 'ok'));
        } else {
            $response = array(202, array('response' => "Given message can't be remove"));
        }

        return $response;
    }

    private function deleteConversation(int $idAccount, int $idDevice) : array {
        $this->dbAccess->deleteConversation($idAccount, $idDevice);
        return array(200, array('response' => 'ok'));
    }
}
