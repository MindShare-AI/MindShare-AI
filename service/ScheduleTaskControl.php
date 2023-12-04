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
    private static array $POSTS = array(
        "Le café d'abord, la vie d'adulte ensuite.",
        "C'est déjà vendredi ?",
        "J'ai besoin de plus d'heures dans la journée.",
        "Pourquoi mon téléphone est-il toujours en batterie faible ?",
        "Encore un épisode, et je serai productif.",
        "Encore des embouteillages ? Sérieusement ?",
        "Je devrais vraiment boire plus d'eau aujourd'hui.",
        "Où ai-je mis mes clés ?",
        "Note à moi-même : faire les courses après le travail",
        "Je mérite de me faire plaisir aujourd'hui.",
        "J'aimerais avoir plus de temps pour lire.",
        "J'ai besoin de vacances.",
        "Pourquoi est-ce si difficile de prendre des décisions pour le dîner ?",
        "Faisons en sorte qu'aujourd'hui soit une bonne journée pour les cheveux.",
        "Dois-je vraiment être adulte aujourd'hui ?",
        "Je commencerai mon régime demain.",
        "Pourquoi tout le monde est-il pressé ?",
        "Le week-end me manque déjà.",
        "Je devrais appeler ma mère, mon père ou mon ami.",
        "J'ai besoin d'une pause dans les médias sociaux.",
        "Encore quelques minutes de sommeil, s'il vous plaît.",
        "Je devrais peut-être essayer de préparer des repas.",
        "J'aimerais pouvoir travailler à domicile tous les jours.",
        "Je n'ai rien à me mettre.",
        "Je devrais faire de l'exercice... ou pas.",
        "Pourquoi le jour de la lessive est-il toujours aussi accablant ?",
        "Je n'arrive pas à croire que nous sommes déjà en décembre.",
        "J'ai besoin d'une nouvelle playlist pour mon trajet.",
        "Je devrais apprendre à cuisiner cette recette.",
        "J'ai hâte de me détendre et de me relaxer ce soir."
    );

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
            if (count($uriParameters) === 0) {
                $this->sendAutomaticPost();
                $response = array(200, array('response' => 'ok'));
            } else {
                http_response_code(400);
                echo json_encode(array('response' => 'Bad uri parameters format'), JSON_PRETTY_PRINT);
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
        // choosing random post
        $randomPost = ScheduleTaskControl::$POSTS[rand(0, count(ScheduleTaskControl::$POSTS) - 1)];

        // choosing account that posts
        $accounts = $this->accountAccess->getAllAccounts();
        $randomAccount = $accounts[rand(0, count($accounts) - 1)];

        // send the new post
        $newPost = new Post(-1, $randomAccount->getIdAccount(), $randomPost, date('Y-m-d'));
        $this->dbAccess->addPost($newPost->toArray());
    }
}
