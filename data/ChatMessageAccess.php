<?php
/**
@file     data/ChatMessageAccess.php
@author   Florian Lopitaux
@version  0.1
@summary  Class to interact with the ChatMessage sql table.

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

use model\ChatMessage;
require_once 'model/ChatMessage.php';

final class ChatMessageAccess extends DataAccess {
    // CONSTRUCTOR
    /**
     * The constructor to instantiate an PostAccess object.
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
     * Returns all messages between an account and a user.
     *
     * @param int $idAccount The identifier of the account.
     * @param int $idDevice The identifier of the user's device.
     *
     * @return array All messages of the conversation.
     */
    public function getConversation(int $idAccount, int $idDevice) : array {
        $conversation = array();

        // send sql server
        $this->prepareQuery('SELECT * FROM CHATMESSAGE WHERE id_account = ? AND id_device = ?');
        $this->executeQuery(array($idAccount, $idDevice));

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $conversation[] = new ChatMessage($row['id_message'], $row['id_sender'], $row['id_receiver'],
                $row['message'], $row['send_date']);
        }

        return $conversation;
    }
}
