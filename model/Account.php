<?php
/**
@file     model/Account.php
@author   Florian Lopitaux
@version  0.1
@summary  Model class of the Account SQL table.

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

final class Account {
    // FIELDS
    private static int $BASE_YEARS_OLD = 18;

    private int $idAccount;
    private string $lastName;
    private string $firstName;
    private int $yearsOld;
    private string $biography;


    // CONSTRUCTOR
    /**
     * The constructor to instantiate an Account object.
     *
     * @param int $id The id (PRIMARY KEY) of the account.
     * @param string $lastName The last name of the account.
     * @param string $firstName The first name of the account.
     * @param int $yearsOld The years old of the account.
     * @param string $bio The biography (short text presentation) of the account.
     */
    public function __construct(int $id, string $lastName, string $firstName,
                                int $yearsOld, string $bio) {

        $this->idAccount = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->biography = $bio;

        $this->yearsOld = ($yearsOld > 0) ? $yearsOld : Account::$BASE_YEARS_OLD;
    }


    // GETTERS
    /**
     * This method is the getter of the 'idAccount' attribute.
     *
     * @return int The id (PRIMARY KEY) of the account.
     */
    public function getIdAccount(): int {
        return $this->idAccount;
    }

    /**
     * This method is the getter of the 'lastName' attribute.
     *
     * @return string The last name of the account.
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * This method is the getter of the 'firstName' attribute.
     *
     * @return string The first name of the account.
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * This method is the getter of the 'yearsOld' attribute.
     *
     * @return int The years old of the account.
     */
    public function getYearsOld(): int {
        return $this->yearsOld;
    }

    /**
     * This method is the getter of the 'biography' attribute.
     *
     * @return string A short text to present the account.
     */
    public function getBiography(): string {
        return $this->biography;
    }


    // SETTERS
    /**
     * This method is the setter of the 'idAccount' attribute.
     *
     * @param int $idAccount
     */
    public function setIdAccount(int $idAccount): void {
        $this->idAccount = $idAccount;
    }

    /**
     * This method is the setter of the 'lastName' attribute.
     *
     * @param string $lastName
     */
    public function setLastName(string $lastName): void {
        $this->lastName = $lastName;
    }

    /**
     * This method is the setter of the 'firstName' attribute.
     *
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void {
        $this->firstName = $firstName;
    }

    /**
     * This method is the setter of the 'yearsOld' attribute.
     *
     * @param int $yearsOld
     */
    public function setYearsOld(int $yearsOld): void {
        if ($yearsOld <= 0) {
            return;
        }

        $this->yearsOld = $yearsOld;
    }

    /**
     * This method is the setter of the 'biography' attribute.
     *
     * @param string $biography
     */
    public function setBiography(string $biography): void {
        $this->biography = $biography;
    }


    // METHODS
    public function toJson() : string {
        $object = array(
            'id_account' => $this->idAccount,
            'last_name' => $this->lastName,
            'first_name' => $this->firstName,
            'years_old' => $this->yearsOld,
            'biography' => $this->biography
        );

        return json_encode($object);
    }
}
