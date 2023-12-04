<?php
/**
@file     index.php
@author   Florian Lopitaux
@version  0.1
@summary  EntryPoint of the MindShare-API, loads everything and does the 'url routing'.

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

// Loads dependencies
use service\{AccountControl, FollowControl, PostControl, ScheduleTaskControl};
require_once 'service/AccountControl.php';
require_once 'service/FollowControl.php';
require_once 'service/PostControl.php';
require_once 'service/ScheduleTaskControl.php';

header('Content-Type: application/json');

// Loads the .ini file that contains the database identifiers
$config = parse_ini_file('config.ini');

if (!$config) { // file doesn't found or not parsable
    http_response_code(500);
    echo json_encode(array('response' => 'Internal problem'));
    die();
}

// parse the url to get the uri parameters and know which methods called
$uriParameters = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
array_shift($uriParameters); // remove first element always empty

if (sizeof($uriParameters) < 2 || $uriParameters[0] != "api") {
    http_response_code(404);
    die();
}

$uriParameters[1] = strtoupper(substr($uriParameters[1], 0, 1)) . substr($uriParameters[1], 1);
$serviceCalled = "service\\$uriParameters[1]Control";

if (class_exists("$serviceCalled")) {
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    // get the authorization token to some restricted requests (requests that modify the database not just get)
    $headers = getallheaders();

    if (in_array('Authorization', $headers)) {
        $headerToken = $headers['Authorization'];
    } else {
        $headerToken = null;
    }

    /*if ($headerToken !== $config['api_token']) {
        http_response_code(401);
        echo json_encode(array('response' => 'Bad token'));
        die();
    }*/

    $controller = new $serviceCalled($config, $requestMethod);
    $controller->processRequest(array_slice($uriParameters, 2), $_POST);

} else {
    http_response_code(400);
    echo json_encode(array('response' => 'URI parameters not accepted'));
    die();
}
