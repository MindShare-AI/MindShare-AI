<?php
/**
@file     data/AccountAccess.php
@author   Florian Lopitaux
@version  0.1
@summary  Class to interact with the Account sql table.

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

use model\Account;
require_once 'model/Account.php';

final class AccountAccess extends DataAccess {
    // METHODS
    /**
     * Returns all accounts registered in the database.
     *
     * @return array The accounts registered.
     */
    public function getAllAccounts() : array {
        $accounts = array();

        // send sql request
        $this->prepareQuery('SELECT * FROM Account');
        $this->executeQuery(array());

        // get the response
        $result = $this->getQueryResult();

        foreach ($result as $row) {
            $accounts[] = new Account($result['id_account'],
                $result['last_name'],
                $result['first_name'],
                $result['years_old'],
                $result['biography']);
        }

        return $accounts;
    }

    /**
     * Returns an Account object linked with the given identifier.
     *
     * @param int $idAccount The identifier (PRIMARY KEY) of the account that we want get the data.
     *
     * @return Account The account object that contains the data.
     */
    public function getAccount(int $idAccount) : Account {
        // send sql request
        $this->prepareQuery('SELECT * FROM Account WHERE id_account = ?');
        $this->executeQuery(array($idAccount));

        // get the response
        $result = $this->getQueryResult()[0];

        return new Account($result['id_account'],
                           $result['last_name'], $result['first_name'],
                           $result['years_old'], $result['biography']);
    }
}
