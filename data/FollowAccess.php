<?php
/**
@file     data/FollowAccess.php
@author   Florian Lopitaux
@version  0.1
@summary  Class to interact with the Follow sql table.

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

final class FollowAccess extends DataAccess {
    // METHODS
    /**
     * Returns all identifiers accounts which followed by the given account.
     *
     * @param int $idAccount The identifier of the account that we want his accounts following.
     *
     * @return array The identifiers account of the followers.
     */
    public function getFollowingAccounts(int $idAccount) : array {
        $following = array();

        // send sql request
        $this->prepareQuery('SELECT * FROM Follow WHERE id_account_follower = ?');
        $this->executeQuery(array($idAccount));

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $following[] = $row['id_account_followed'];
        }

        return $following;
    }

    /**
     * Returns the number of accounts following by a given account.
     *
     * @param int $idAccount The identifier of the account that we want to get the follow.
     *
     * @return int The number of following accounts.
     */
    public function getFollowsCount(int $idAccount): int {
        // send sql request
        $this->prepareQuery('SELECT COUNT(*) FROM Follow WHERE id_account_following = ?');
        $this->executeQuery(array($idAccount));

        // get the response
        $result = $this->getQueryResult();

        return $result[0];
    }

    /**
     * Returns all identifiers accounts which follows the given account.
     *
     * @param int $idAccount The identifier of the account that we want to get the followers.
     *
     * @return array The identifiers account of the followers.
     */
    public function getFollowers(int $idAccount) : array {
        $followers = array();

        // send sql request
        $this->prepareQuery('SELECT * FROM Follow WHERE id_account_followed = ?');
        $this->executeQuery(array($idAccount));

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $followers[] = $row['id_account_follower'];
        }

        return $followers;
    }

    /**
     * Returns the number of followers of an account.
     *
     * @param int $idAccount The identifier of the account that we want to get the followers.
     *
     * @return int The number of followers.
     */
    public function getFollowersCount(int $idAccount): int {
        // send sql request
        $this->prepareQuery('SELECT COUNT(*) FROM Follow WHERE id_account_followed = ?');
        $this->executeQuery(array($idAccount));

        // get the response
        $result = $this->getQueryResult();

        return $result[0];
    }

    /**
     * Add a new entity in the Follow table.
     *
     * @param int $idFollower The account identifier of the account which follows.
     * @param int $idFollowed The account identifier of the account which followed.
     */
    public function addFollowEntity(int $idFollower, int $idFollowed) : void {
        $this->prepareQuery('INSERT INTO FOLLOW VALUES (?, ?)');
        $this->executeQuery(array($idFollower, $idFollowed));

        $this->closeQuery();
    }
}
