<?php
/**
@file     data/PostAccess.php
@author   Florian Lopitaux
@version  0.1
@summary  Class to interact with the Post sql table.

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

use model\Post;
require_once 'model/Post.php';

final class PostAccess extends DataAccess {
    // METHODS
    /**
     * Returns all posts registered in the database.
     *
     * @return array The posts.
     */
    public function getAllPosts() : array {
        $posts = array();

        // send sql request
        $this->prepareQuery('SELECT * FROM POST');
        $this->executeQuery(array());

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $posts[] = Post::fromArray($row);
        }

        return $posts;
    }

    public function getPost(int $idPost) : ?Post {
        $this->prepareQuery('SELECT * FROM POST WHERE id_post = ?');
        $this->executeQuery(array($idPost));

        $result = $this->getQueryResult();

        if (count($result) === 0) {
            return null;
        } else {
            return Post::fromArray($result[0]);
        }
    }

    /**
     * Returns all posts sent by a given account.
     *
     * @param string $lastName The last name of the account.
     * @param string $firstName The first name of the account.
     *
     * @return array All posts sent.
     */
    public function getPostsOfAccount(string $lastName, string $firstName) : array {
        $posts = array();

        // send sql request
        $this->prepareQuery('SELECT * FROM POST WHERE last_name = ? AND first_name = ?');
        $this->executeQuery(array($lastName, $firstName));

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $posts = Post::fromArray($row);
        }

        return $posts;
    }

    /**
     * Returns all comments of a specified post.
     *
     * @param int $idPost The identifier of the past that we want it comments.
     *
     * @return array The comments of the post.
     */
    public function getCommentsOfPost(int $idPost) : array {
        $comments = array();

        // send sql request
        $this->prepareQuery('SELECT * FROM POST WHERE id_post_commented = ?');
        $this->executeQuery(array($idPost));

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $comments = Post::fromArray($row);
        }

        return $comments;
    }

    public function getStatsOfAccount(int $idAccount) : array {
        $this->prepareQuery('SELECT COUNT(*) FROM POST WHERE id_account = ? AND id_post_commented IS NULL');
        $this->executeQuery(array($idAccount));

        $nbPosts = $this->getQueryResult();

        $this->prepareQuery('SELECT COUNT(*) FROM POST WHERE id_account = ? AND id_post_commented IS NOT NULL');
        $this->executeQuery(array($idAccount));

        $nbCommentaries = $this->getQueryResult();

        return array('nb_posts' => $nbPosts, 'nb_comments' => $nbCommentaries);
    }

    public function addPost(array $postData) : void {
        $message = in_array('message', $postData) ? $postData['message'] : '';
        $sendDate = in_array('send_date', $postData) ? $postData['send_date'] : date('Y-m-d');
        $idAccount = $postData['id_account'];
        $idPostCommented = in_array('id_post_commented', $postData) ? $postData['id_post_commented'] : null;

        $this->prepareQuery('INSERT INTO POST (id_account, message, send_date, id_post_commented) VALUES (?, ?, ?, ?)');
        $this->executeQuery(array($idAccount, $message, $sendDate, $idPostCommented));
        $this->closeQuery();
    }

    public function deletePost(int $idPost) : bool {
        // check if the post exists
        $this->prepareQuery('SELECT * FROM POST WHERE id_post = ?');
        $this->executeQuery(array($idPost));

        $result = $this->getQueryResult();

        if (count($result) === 0) { // post doesn't exist
            return false;
        }

        // delete the post and there commentaries
        $this->prepareQuery('DELETE FROM POST WHERE id_post = ?');
        $this->executeQuery(array($idPost));
        $this->closeQuery();

        $this->prepareQuery('DELETE FROM POST WHERE id_post_commented = ?');
        $this->executeQuery(array($idPost));
        $this->closeQuery();

        return true;
    }
}
