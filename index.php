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

header('Content-Type: application/json');
$request_method = $_SERVER['REQUEST_METHOD'];

// parse the url to get the uri parameters and know which methods called
$uriParameters = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
array_shift($uriParameters); // remove first element always empty

if (sizeof($uriParameters) < 2 || $uriParameters[0] != "api") {
    http_response_code(404);
    echo json_encode(array());
    die();
}
