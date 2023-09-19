<?php
/**
@file     model/ChatMessage.php
@author   Florian Lopitaux
@version  0.1
@summary  Model class of the ChatMessage SQL table.

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

namespace model;

use DateTime;

final class ChatMessage {
    // FIELDS
    private int $idMessage;
    private int $idSender;
    private int $idReceiver;
    private string $message;
    private DateTime $sendDate;


    // CONSTRUCTOR
    /**
     * The constructor to instantiate a ChatMessage object.
     *
     * @param int $idMessage The id (PRIMARY KEY) of the message.
     * @param int $idSender The id (FOREIGN KEY) of the account or the device that sent the message.
     * @param int $idReceiver The id (FOREIGN KEY) of the account or the device that received the message.
     * @param string $message The text of the message.
     * @param DateTime $date The date when the message was published.
     */
    public function __construct(int $idMessage, int $idSender, int $idReceiver,
                                string $message, DateTime $date) {
        $this->idMessage = $idMessage;
        $this->idSender = $idSender;
        $this->idReceiver = $idReceiver;
        $this->message = $message;
        $this->sendDate = $date;
    }


    // GETTERS
    /**
     * This method is the getter of the 'idMessage' attribute.
     *
     * @return int The id (PRIMARY KEY) of the message.
     */
    public function getIdMessage(): int {
        return $this->idMessage;
    }

    /**
     * This method is the getter of the 'idSender' attribute.
     *
     * @return int The id (FOREIGN KEY) of the account or the device that sent the message.
     */
    public function getIdSender(): int {
        return $this->idSender;
    }

    /**
     * This method is the getter of the 'idReceiver' attribute.
     *
     * @return int The id (FOREIGN KEY) of the account or the device that received the message.
     */
    public function getIdReceiver(): int {
        return $this->idReceiver;
    }


    /**
     * This method is the getter of the 'message' attribute.
     *
     * @return string The text of the message.
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * This method is the getter of the 'sendDate' attribute.
     *
     * @return DateTime The date when the message was published.
     */
    public function getSendDate(): DateTime {
        return $this->sendDate;
    }


    // SETTERS
    /**
     * This method is the setter of the 'idMessage' attribute.
     *
     * @param int $idMessage The id (PRIMARY KEY) of the message.
     */
    public function setIdMessage(int $idMessage): void {
        $this->idMessage = $idMessage;
    }

    /**
     * This method is the setter of the 'idSender' attribute.
     *
     * @param int $idSender The id (FOREIGN KEY) of the account or the device that sent the message.
     */
    public function setIdSender(int $idSender): void {
        $this->idSender = $idSender;
    }

    /**
     * This method is the setter of the 'idReceiver' attribute.
     *
     * @param int $idReceiver The id (FOREIGN KEY) of the account or the device that received the message.
     */
    public function setIdReceiver(int $idReceiver): void {
        $this->idReceiver = $idReceiver;
    }

    /**
     * This method is the setter of the 'message' attribute.
     *
     * @param string $message The text of the message.
     */
    public function setMessage(string $message): void {
        $this->message = $message;
    }

    /**
     * This method is the setter of the 'sendDate' attribute.
     *
     * @param DateTime $sendDate The date when the message was published.
     */
    public function setSendDate(DateTime $sendDate): void {
        $this->sendDate = $sendDate;
    }
}
