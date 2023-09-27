<?php
/**
@file     model/Post.php
@author   Florian Lopitaux
@version  0.1
@summary  Model class of the Post SQL table.

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

final class Post {
    // FIELDS
    private int $idPost;
    private int $idAccount;
    private string $message;
    private DateTime $sendDate;
    private int $idPostCommented;


    // CONSTRUCTOR
    /**
     * The constructor to instantiate a Post object.
     *
     * @param int $idPost The id (PRIMARY KEY) of the post.
     * @param int $idAccount The id (FOREIGN KEY) of the account that sent the post.
     * @param string $message The text message of the post.
     * @param DateTime $date The date when the post was published.
     * @param int|null $idPostCommented The post identifier (FOREIGN KEY) of the post
     *                                  if this post is a comment in response to another post.
     */
    public function __construct(int $idPost, int $idAccount,
                                string $message, DateTime $date, int $idPostCommented = null) {

        $this->idPost = $idPost;
        $this->idAccount = $idAccount;
        $this->message = $message;
        $this->sendDate = $date;
        $this->idPostCommented = $idPostCommented;
    }


    // GETTERS
    /**
     * This method is the getter of the 'idPost' attribute.
     *
     * @return int The id (PRIMARY KEY) of the post.
     */
    public function getIdPost(): int {
        return $this->idPost;
    }

    /**
     * This method is the getter of the 'idAccount' attribute.
     *
     * @return int The id (FOREIGN KEY) of the account that sent the post.
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
     * @return DateTime The date when the post was published.
     */
    public function getSendDate(): DateTime {
        return $this->sendDate;
    }

    /**
     * This method is the getter of the 'idPostCommented' attribute.
     *
     * @return int|null The post identifier (FOREIGN KEY) of the post
     *                  if this post is a comment in response to another post.
     */
    public function getIdPostCommented() : ?int {
        return $this->idPostCommented;
    }


    // SETTERS
    /**
     * This method is the setter of the 'idPost' attribute.
     *
     * @param int $idPost The id (PRIMARY KEY) of the post.
     */
    public function setIdPost(int $idPost): void {
        $this->idPost = $idPost;
    }

    /**
     * This method is the setter of the 'idAccount' attribute.
     *
     * @param int $idAccount The id (FOREIGN KEY) of the account that sent the post.
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
     * @param DateTime $sendDate The date when the post was published.
     */
    public function setSendDate(DateTime $sendDate): void {
        $this->sendDate = $sendDate;
    }

    /**
     * This method is the setter of the 'idPostCommented' attribute.
     *
     * @param int $idPost The post identifier (FOREIGN KEY) of the "original post".
     */
    public function setIdPostCommented(int $idPost): void {
        $this->idPostCommented = $idPost;
    }


    // PUBLIC METHODS
    /**
     * Converts this object to array format to send by http request.
     *
     * @return array The object in array format.
     */
    public function toArray() : array {
        return array(
            'id_post' => $this->idPost,
            'id_account' => $this->idAccount,
            'message' => $this->message,
            'send_date' => $this->sendDate,
            'id_post_commented' => $this->idPostCommented
        );
    }


    // STATIC METHODS
    /**
     * Create an account object from the sql array.
     *
     * @param array $entity the sql entity.
     *
     * @return Post The post object.
     */
    public static function fromArray(array $entity) : Post {
        return new Post($entity['id_post'],
            $entity['id_account'],
            $entity['message'],
            $entity['send_date'],
            $entity['id_post_commented']);
    }
}
