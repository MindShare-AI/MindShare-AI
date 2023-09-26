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
            $accounts[] = Account::fromArray($row);
        }

        return $accounts;
    }

    /**
     * Returns an Account object linked with the given identifier.
     *
     * @param int $idAccount The identifier (PRIMARY KEY) of the account that we want get the data.
     *
     * @return Account|null The account object that contains the data.
     *                      Or null if no object found.
     */
    public function getAccountByID(int $idAccount) : ?Account {
        // send sql request
        $this->prepareQuery('SELECT * FROM Account WHERE id_account = ?');
        $this->executeQuery(array($idAccount));

        // get the response
        $result = $this->getQueryResult();

        if (count($result) > 0) {
            return Account::fromArray($result[0]);
        } else {
            return null;
        }
    }

    /**
     * Returns an Account object linked with the given identifier.
     *
     * @param string $lastName The last name of the account.
     * @param string $firstName The first name of the account.
     *
     * @return Account|null The account object that contains the data.
     *                      Or null if no object found.
     */
    public function getAccountByName(string $lastName, string $firstName) : ?Account {
        // send sql request
        $this->prepareQuery('SELECT * FROM Account WHERE last_name = ? AND first_name = ?');
        $this->executeQuery(array($lastName, $firstName));

        // get the response
        $result = $this->getQueryResult();

        if (count($result) > 0) {
            return Account::fromArray($result[0]);
        } else {
            return null;
        }
    }

    /**
     * Inserts a new entity in the Account table.
     *
     * @param array $accountData The data to insert to the new account.
     */
    public function addAccount(array $accountData) : void {
        $lastName = $accountData['last_name'];
        $firstName = $accountData['first_name'];
        $years_old = in_array('years_old', $accountData) ? $accountData['years_old'] : 0;
        $biography = in_array('biography', $accountData) ? $accountData['biography'] : '';

        $this->prepareQuery('INSERT INTO ACCOUNT (last_name, first_name, years_old, biography) VALUES (?, ?, ?, ?)');
        $this->executeQuery(array($lastName, $firstName, $years_old, $biography));
        $this->closeQuery();
    }

    /**
     * Removes account linked with the given identifier.
     *
     * @param int $idAccount The identifier of the account to remove.
     *
     * @return bool A boolean value to know if the request has success.
     */
    public function deleteAccountByID(int $idAccount) : bool {
        $account = $this->getAccountByID($idAccount);

        if ($account === null) {
            return false;
        } else {
            $this->deleteAccount($account);
            return true;
        }
    }

    /**
     * Removes account linked with the given name.
     *
     * @param string $lastName The last name of the account to remove.
     * @param string $firstName The first name of the account to remove.
     *
     * @return bool A boolean value to know if the request has success.
     */
    public function deleteAccountByName(string $lastName, string $firstName) : bool {
        $account = $this->getAccountByName($lastName, $firstName);

        if ($account === null) {
            return false;
        } else {
            $this->deleteAccount($account);
            return true;
        }
    }


    // PRIVATE METHODS
    /**
     * Make SQL requests to remove an account and all entities linked with the account.
     *
     * @param Account $account The account model.
     */
    private function deleteAccount(Account $account) : void {
        // delete account
        $this->prepareQuery('DELETE FROM ACCOUNT WHERE id_account = ?');
        $this->executeQuery(array($account->getIdAccount()));
        $this->closeQuery();

        // delete account dependencies in other tables
        $this->prepareQuery('DELETE FROM POST WHERE id_account = ?');
        $this->executeQuery(array($account->getIdAccount()));
        $this->closeQuery();

        $this->prepareQuery('DELETE FROM CHATMESSAGE WHERE id_sender = ? OR id_receiver = ?');
        $this->executeQuery(array($account->getIdAccount(), $account->getIdAccount()));
        $this->closeQuery();
    }
}
