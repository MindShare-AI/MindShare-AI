<?php
/**
@file     service/ScheduleTaskControl
@author   Florian Lopitaux
@version  0.1
@summary  Class to manage http request related to the schedule tasks of the server.

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

use model\{Account, Post};
require_once 'model/Account.php';
require_once 'model/Post.php';

use data\{AccountAccess, PostAccess};
require_once 'data/AccountAccess.php';
require_once 'data/PostAccess.php';

final class ScheduleTaskControl extends BaseController {
    // FIELDS
    private AccountAccess $accountAccess;


    // CONSTRUCTOR
    public function __construct(array $config, string $requestMethod) {
        parent::__construct($requestMethod);

        $this->dbAccess = new PostAccess($config['posts_identifier'], $config['posts_password']);
        $this->accountAccess = new AccountAccess($config['accounts_identifier'], $config['accounts_password']);
    }


    // PUBLIC METHODS
    public function processRequest(array $uriParameters, array $postParams): void {
        if ($this->requestMethod === 'GET') {
            if (count($uriParameters) === 1) {
                if ($uriParameters[0] === "post") {
                    $this->sendAutomaticPost();
                    $response = array(200, array('response' => 'ok'));

                } else if ($uriParameters[0] === "comment") {
                    $this->sendAutomaticComment();
                    $response = array(200, array('response' => 'ok'));

                } else {
                    $response = array(400, array('response' => 'Bad uri parameters format'), JSON_PRETTY_PRINT);
                }
            } else {
                http_response_code(400);
                echo json_encode(array(400, 'response' => 'Bad uri parameters format'), JSON_PRETTY_PRINT);
                die();
            }
        } else {
            http_response_code(404);
            echo json_encode(array('response' => 'http request method not allowed for "/scheduleTask"'), JSON_PRETTY_PRINT);
            die();
        }

        http_response_code($response[0]);
        echo json_encode($response[1], JSON_PRETTY_PRINT);
    }


    // PRIVATE METHODS
    private function sendAutomaticPost(): void {
        // get all posts generated
        $postFile = file_get_contents('generated_posts.json');
        $posts = json_decode($postFile);

        // choosing random post
        $randomPost = $posts[rand(0, count($posts) - 1)];

        // choosing account that posts
        $accounts = $this->accountAccess->getAllAccounts();
        $randomAccount = $accounts[rand(0, count($accounts) - 1)];

        // send the new post
        $newPost = new Post(-1, $randomAccount->getIdAccount(), $randomPost, date('Y-m-d'));
        $this->dbAccess->addPost($newPost->toArray());
    }

    private function sendAutomaticComment(): void {
        // get all comments generated
        $commentFile = file_get_contents('generated_comments.json');
        $comments = json_decode($commentFile);

        // choosing random comment
        $randomComment = $comments[rand(0, count($comments) - 1)];

        // choosing post to respond
        $posts = $this->dbAccess->getAllPosts();
        $randomPost = $posts[rand(0, count($posts) - 1)];

        // choosing account that posts
        $accounts = $this->accountAccess->getAllAccounts();
        $randomAccount = $accounts[rand(0, count($accounts) - 1)];

        // send the new comment
        $newComment = new Post(-1,
            $randomAccount->getIdAccount(),
            $randomComment,
            date('Y-m-d'),
            $randomPost->getIdPost());

        $this->dbAccess->addPost($newComment->toArray());
    }
}
