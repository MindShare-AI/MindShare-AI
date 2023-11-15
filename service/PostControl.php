<?php
/**
@file     control/PostControl
@author   Florian Lopitaux
@version  0.1
@summary  Class to manage http request related to the posts and commentaries.

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

use model\Post;
require_once 'model/Post.php';

use data\PostAccess;
require_once 'data/PostAccess.php';

final class PostControl extends BaseController {
    // CONSTRUCTOR
    public function __construct(array $config, string $requestMethod) {
        parent::__construct($requestMethod);
        $this->dbAccess = new PostAccess($config['posts_identifier'], $config['posts_password']);
    }


    // PUBLIC METHODS
    public function processRequest(array $uriParameters, array $postParams): void {
        if ($this->requestMethod === 'GET') {
            if (count($uriParameters) === 0) {
                $response = $this->getAllPosts();

            } else if (count($uriParameters) == 1) {
                $response = $this->getPost($uriParameters[0]);

            } else if (count($uriParameters) == 2) {
                if ($uriParameters[0] === 'comments') {
                    $response = $this->getCommentsOfPost($uriParameters[1]);

                } else if ($uriParameters[0] === 'stats') {
                    $response = $this->getStats($uriParameters[1]);

                } else {
                    $response = $this->getPostsOfAccount($uriParameters[0], $uriParameters[1]);
                }

            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'Bad uri parameters format'), JSON_PRETTY_PRINT);
                die();
            }

        } else if ($this->requestMethod === 'POST') {
            $response = $this->addPost($postParams);

        } else if ($this->requestMethod === 'DELETE') {
            if (count($uriParameters) === 1) {
                $response = $this->deletePost($uriParameters[0]);

            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'Bad uri parameters format'), JSON_PRETTY_PRINT);
                die();
            }
        } else {
            http_response_code(404);
            echo json_encode(array('response' => 'http request method not allowed for "/account"'), JSON_PRETTY_PRINT);
            die();
        }

        http_response_code($response[0]);
        echo json_encode($response[1], JSON_PRETTY_PRINT);
    }


    // PRIVATE METHODS
    private function getAllPosts() : array {
        $posts = $this->dbAccess->getAllPosts();
        $response = array(200, array());

        foreach ($posts as $currentPost) {
            $response[1][] = $currentPost->toArray();
        }

        return $response;
    }

    private function getPost(int $idPost) : array {
        $post = $this->dbAccess->getPost($idPost);

        $response = array(200);
        if (is_null($post)) {
            $response[] = array();
        } else {
            $response[] = $post->toArray();
        }

        return $response;
    }

    private function getCommentsOfPost(int $idPost) : array {
        $comments = $this->dbAccess->getCommentsOfPost($idPost);
        $response = array(200, array());

        foreach ($comments as $currentComment) {
            $response[1][] = $currentComment->toArray();
        }

        return $response;
    }

    private function getStats(int $idAccount) : array {
        $stats = $this->dbAccess->getStatsOfAccount($idAccount);
        return array(200, $stats);
    }

    private function getPostsOfAccount(string $lastName, string $firstName) : array {
        $posts = $this->dbAccess->getPostsOfAccount($lastName, $firstName);
        $response = array(200, array());

        foreach ($posts as $currentPost) {
            $response[1][] = $currentPost->toArray();
        }

        return $response;
    }

    private function addPost(array $postData) : array {
        if (in_array('id_account', $postData)) {
            $this->dbAccess->addPost($postData);
            $response = array(200, array('response' => 'ok'));

        } else {
            $response = array(400, array('Bad post parameters'));
        }

        return $response;
    }

    private function deletePost(int $idPost) : array {
        $isDeleted = $this->dbAccess->deletePost($idPost);

        if ($isDeleted) {
            $response = array(200, array('response' => 'ok'));
        } else {
            $response = array(202, array('response' => "Given post can't be remove"));
        }

        return $response;
    }
}
