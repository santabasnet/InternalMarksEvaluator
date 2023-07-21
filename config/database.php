<?php

include_once('config\config.php');
include_once('model\messages.php');

class Database
{
    protected $connection = null;
    /**
     * The default constructor for db connection.
     * @throws Exception
     */
    public function __construct()
    {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
            if (mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Select query, takes string of SQL query.
     * @param string $query
     * @param array $params
     * @return array of results.
     */
    public function select(string $query = "", array $params = []): array
    {
        $result = array();
        try {
            $stmt = $this->executeStatement($query, $params);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } catch (Exception $e) {
        }
        return $result;
    }

    /**
     * Execute query for create equation for the rule entry.
     */
    public function insert(array $queries, string $id): array
    {
        $begin = mysqli_begin_transaction($this->connection, MYSQLI_TRANS_START_READ_WRITE);
        foreach ($queries as $i_query) {
            mysqli_query($this->connection, $i_query);
        }
        $end = mysqli_commit($this->connection);
        if ($begin and $end) {
            $no = count($queries) - 1;
            return rulesCreated($id, $no);
        } else {
            return errorOnRuleCreation($id);
        }
    }

    /**
     * @param $query
     * @param $params
     * @return mysqli_stmt
     * @throws Exception
     */
    private function executeStatement($query = "", $params = []): mysqli_stmt
    {
        try {
            $stmt = $this->connection->prepare($query);
            if ($stmt === false) {
                throw new Exception("Unable to do prepared statement: " . $query);
            }
            if ($params) {
                $stmt->bind_param($params[0], $params[1]);
            }
            $stmt->execute();
            return $stmt;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}