<?php
namespace TripBuilder\Util;

use \PDO;

/**
 * Database access utility
 *
 * @author Elton
 *        
 */
class Database
{

    private $con;

    private static $instance;

    private function __construct()
    {
        // singleton
    }

    public static function getInstance()
    {
        if (! self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    private function connect()
    {
        $this->con = new PDO('sqlite:' . __DIR__ . '/../../db/db.sqlite');
        $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Perform a query, if $params are given then it will create a prepared statement
     *
     * @param string $query            
     * @param array $params
     *            (optional)
     * @throws Exception
     * @return PDOStatement
     * @example ->query("select * from table")
     * @example ->query("update table set col = :col where id = :id",
     *          array(":id" => 1, ":col" => "fooo"))
     */
    public function query($query, $params = false)
    {
        if (! is_resource($this->con)) {
            $this->connect();
        }
        
        try {
            if (! $params) {
                return $this->con->query($query);
            } else {
                $prepared = $this->con->prepare($query);
                $prepared->execute($params);
                return $prepared;
            }
        } catch (\Exception $e) {
            $x = new \Exception($e->getMessage());
            throw $x;
        }
    }

    /**
     * Same as query but will fetch data from resource and return them
     *
     * @param string $query            
     * @param array $params
     *            (optional)
     * @throws Exception
     * @return PDOStatement
     * @example ->query("select * from table")
     * @example ->query("select * from table where id = :id", array(":id" => 1))
     */
    public function queryFetch($query, $params = false)
    {
        return $this->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return the last insert id in a autoincrement column
     * Useful when working with mysql and sqlite due the lack of sequences
     */
    public function lastInsertId()
    {
        return $this->con->lastInsertId();
    }
}

