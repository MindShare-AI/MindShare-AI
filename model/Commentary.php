<?php
/**
@file     model/Commentary.php
@author   Florian Lopitaux
@version  0.1
@summary  Model class of the Commentary SQL table.

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

final class Commentary {
    // FIELDS
    private int $idCommentary;
    private int $idPost;
    private int $idAccount;
    private string $message;
    private DateTime $sendDate;


    // CONSTRUCTOR
    /**
     * The constructor to instantiate a Commentary object.
     *
     * @param int $idCommentary The id (PRIMARY KEY) of the commentary.
     * @param int $idPost The id (FOREIGN KEY) of the post that the commentary reacted.
     * @param int $idAccount The id (FOREIGN KEY) of the account that sent the commentary.
     * @param string $message The text message of the post.
     * @param DateTime $date The date when the commentary was published.
     */
    public function __construct(int $idCommentary, int $idPost, int $idAccount,
                                string $message, DateTime $date) {
        $this->idCommentary = $idCommentary;
        $this->idPost = $idPost;
        $this->idAccount = $idAccount;
        $this->message = $message;
        $this->sendDate = $date;
    }


    // GETTERS
    /**
     * This method is the getter of the 'idCommentary' attribute.
     *
     * @return int The id (PRIMARY KEY) of the commentary.
     */
    public function getIdCommentary(): int {
        return $this->idCommentary;
    }

    /**
     * This method is the getter of the 'idPost' attribute.
     *
     * @return int The id (FOREIGN KEY) of the post that the commentary reacted.
     */
    public function getIdPost(): int {
        return $this->idPost;
    }

    /**
     * This method is the getter of the 'idAccount' attribute.
     *
     * @return int The id (FOREIGN KEY) of the account that sent the commentary.
     */
    public function getIdAccount(): int {
        return $this->idAccount;
    }

    /**
     * This method is the getter of the 'message' attribute.
     *
     * @return string The text message of the post.
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * This method is the getter of the 'sendDate' attribute.
     *
     * @return DateTime The date when the commentary was published.
     */
    public function getSendDate(): DateTime {
        return $this->sendDate;
    }


    // SETTERS
    /**
     * This method is the setter of the 'idCommentary' attribute.
     *
     * @param int $idCommentary The id (PRIMARY KEY) of the commentary.
     */
    public function setIdCommentary(int $idCommentary): void {
        $this->idCommentary = $idCommentary;
    }

    /**
     * This method is the setter of the 'idPost' attribute.
     *
     * @param int $idPost The id (FOREIGN KEY) of the post that the commentary reacted.
     */
    public function setIdPost(int $idPost): void {
        $this->idPost = $idPost;
    }

    /**
     * This method is the setter of the 'idAccount' attribute.
     *
     * @param int $idAccount The id (FOREIGN KEY) of the account that sent the commentary.
     */
    public function setIdAccount(int $idAccount): void {
        $this->idAccount = $idAccount;
    }

    /**
     * This method is the setter of the 'message' attribute.
     *
     * @param string $message The text message of the post.
     */
    public function setMessage(string $message): void {
        $this->message = $message;
    }

    /**
     * This method is the setter of the 'sendDate' attribute.
     *
     * @param DateTime $sendDate The date when the commentary was published.
     */
    public function setSendDate(DateTime $sendDate): void {
        $this->sendDate = $sendDate;
    }
}
