<?php
/**
@file     data/DataAccess.php
@author   Florian Lopitaux
@version  0.1
@summary  Abstract class to connect to the database.

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

abstract class DataAccess {
    // FIELDS
    private static string $HOST = "mysql-mindshare-ai.alwaysdata.net";
    private static string $DATA_BASE = "mindshare-ai_bd";

    private \PDO|null $dbh;
    private \PDOStatement $query;


    // CONSTRUCTOR
    /**
     * The constructor of the DataAccess class. It starts the connexion to the database.
     *
     * @param string $user The user identifier to connect to the database.
     * @param string $password The password to connect to the database.
     */
    protected function __construct(string $user, string $password) {
        try {
            $this->dbh = new \PDO('mysql:host=' . DataAccess::$HOST . ';dbname=' . DataAccess::$DATA_BASE,
                $user, $password);

        } catch (\PDOException $e) {
            print 'Database connexion error : !' . $e->getMessage() . '<br/>';
            die();
        }
    }


    // DESTRUCTOR
    /**
     * The destructor of the DataAccess class. It close the connexion to the database.
     */
    public function __destruct() {
        $this->dbh = null;
    }


    // METHODS
    /**
     * This method is called to prepare the sql request.
     *
     * @param string $query The sql request with '?' character instead of the input.
     */
    protected function prepareQuery(string $query): void {
        $this->query = $this->dbh->prepare($query);
    }

    /**
     * This method is called to execute the query prepared with the input passed as an array of parameters.
     * Need to called prepareQuery() method before called this method.
     *
     * @param array $parameters The inputs of the sql request.
     */
    protected function executeQuery(array $parameters): void {
        $this->query->execute($parameters);
    }

    /**
     * This method allows us to get the result of the request executed.
     * Need to called executeQuery() method before called this method.
     *
     * @return array The list of all results returned by the request.
     */
    protected function getQueryResult(): array {
        $result = $this->query->fetchAll();

        $this->closeQuery();
        return $result;
    }

    /**
     * This method close the cursor of the current request.
     * You have to call this method if you don't call the getQueryResult() method
     * after called the executeQuery() method (in INSERT or DELETE request case for example).
     */
    protected function closeQuery(): void {
        $this->query->closeCursor();
    }
}
