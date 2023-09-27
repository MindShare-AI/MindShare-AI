<?php
/**
@file     control/BaseController
@author   Florian Lopitaux
@version  0.1
@summary  Abstract class to all controller services.

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

use data\DataAccess;
require_once 'data/DataAccess.php';

abstract class BaseController {
    // FIELDS
    protected DataAccess $dbAccess;
    protected string $requestMethod;


    // CONSTRUCTOR
    public function __construct(string $requestMethod) {
        $this->requestMethod = $requestMethod;
    }


    // METHODS
    public function processRequest(array $uriParameters, array $postParams) : void {

    }
}
