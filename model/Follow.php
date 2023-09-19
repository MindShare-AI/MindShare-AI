<?php
/**
@file     model/Follow.php
@author   Florian Lopitaux
@version  0.1
@summary  Model class of the Follow SQL table.

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

final class Follow {
    // FIELDS
    private int $idAccountFollowed;
    private int $idAccountFollower;


    // CONSTRUCTOR
    /**
     * The constructor to instantiate a Follow object.
     *
     * @param int $idAccountFollowed The id (PRIMARY KEY) of the account followed.
     * @param int $idAccountFollower The id (PRIMARY KEY) of the account that follow the other.
     */
    public function __construct(int $idAccountFollowed, int $idAccountFollower) {
        $this->idAccountFollowed = $idAccountFollowed;
        $this->idAccountFollower = $idAccountFollower;
    }


    // GETTERS
    /**
     * This method is the getter of the 'idAccountFollowed' attribute.
     *
     * @return int The id (PRIMARY KEY) of the account followed.
     */
    public function getIdAccountFollowed(): int {
        return $this->idAccountFollowed;
    }

    /**
     * This method is the getter of the 'idAccountFollower' attribute.
     *
     * @return int The id (PRIMARY KEY) of the account that follow the other.
     */
    public function getIdAccountFollower(): int {
        return $this->idAccountFollower;
    }


    // SETTERS
    /**
     * This method is the setter of the 'idAccountFollowed' attribute.
     *
     * @param int $idAccountFollowed The id (PRIMARY KEY) of the account followed.
     */
    public function setIdAccountFollowed(int $idAccountFollowed): void {
        $this->idAccountFollowed = $idAccountFollowed;
    }

    /**
     * This method is the setter of the 'idAccountFollower' attribute.
     *
     * @param int $idAccountFollower The id (PRIMARY KEY) of the account that follow the other.
     */
    public function setIdAccountFollower(int $idAccountFollower): void {
        $this->idAccountFollower = $idAccountFollower;
    }
}
