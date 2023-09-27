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
        $this->prepareQuery('SELECT * FROM CHATMESSAGE
            WHERE (id_sender = ? AND id_receiver = ?)
            OR (id_sender = ? AND id_receiver = ?)
            ORDER BY send_date');
        $this->executeQuery(array($idAccount, $idDevice, $idDevice, $idAccount));

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $conversation[] = new ChatMessage($row['id_message'], $row['id_sender'], $row['id_receiver'],
                $row['message'], $row['send_date']);
        }

        return $conversation;
    }

    public function addMessage(array $messageData) : void {
        $message = (in_array('message', $messageData)) ? $messageData['message'] : '';
        $date = in_array('send_date', $messageData) ? $messageData['send_date'] : date('Y-m-d');

        $this->prepareQuery('INSERT INTO CHATMESSAGE (message, send_date, id_account, id_device)
            VALUES (?, ?, ?, ?)');
        $this->executeQuery(array($message, $date, $messageData['id_account'], $messageData['id_device']));

        $this->closeQuery();
    }

    public function deleteMessage(int $idMessage) : bool {
        $this->prepareQuery('SELECT COUNT(*) FROM CHATMESSAGE WHERE id_message = ?');
        $this->executeQuery(array($idMessage));

        $result = $this->getQueryResult()[0];
        if ($result === 0) {
            return false;
        }

        $this->prepareQuery('DELETE FROM CHATMESSAGE WHERE id_message = ?');
        $this->executeQuery(array($idMessage));
        $this->closeQuery();

        return true;
    }

    public function deleteConversation(int $idAccount, int $idDevice) : void {
        $this->prepareQuery('DELETE FROM CHATMESSAGE
            WHERE (id_sender = ? AND id_receiver = ?)
            OR (id_sender = ? AND id_receiver = ?)');

        $this->executeQuery(array($idAccount, $idDevice, $idDevice, $idAccount));
        $this->closeQuery();
    }
}
