<?php

/**
 * TmongoDB Operation Class
 * TMONGODB IS A LIBRARY FOR MONGODB OPERATION. IT IS FAST AND EASY TO USE.
 *
 * @package     Thendfeel/TmongoDB
 * @author      thendfeel@gmail.com
 * @link        https://github.com/thendfeel/TmongoDB
 * @project     http://project.uacool.com
 * @site        http://www.uacool.com
 * @created     2013-12-13
 */
class TmongoDB
{

    protected static $_mongoDB;

    /**
     * Config For MongDB
     *
     * @var unknown
     */
    protected static $_config = array(
        'host' => 'localhost',
        'port' => '27017',
        'password' => NULL,
        'db' => 'test',
        'collection' => 'user'
    );

    public function __construct($db = '', $collection = '')
    {
        self::init($db, $collection);
    }

    /**
     * Init The Class
     *
     * @param string $db            
     * @param string $collection            
     */
    public static function init($db = '', $collection = '')
    {
        if (! self::$_mongoDB) {
            $config = self::$_config;
            $_mongoDB = new Mongo("mongodb://{$config['host']}:{$config['port']}", array(
                "connect" => false
            ));
            if ($db && $collection) {
                self::$_mongoDB = $_mongoDB->selectCollection($db, $collection);
            } else {
                self::$_mongoDB = $_mongoDB->selectCollection($config['db'], $config['collection']);
            }
        }
    }

    /**
     * Fetch From MongoDB
     *
     * @param array $argv            
     * @param number $skip            
     * @param number $limit            
     * @param array $sort            
     * @return Ambigous <Ambigous, multitype:, multitype:>
     */
    public static function fetch($argv = array(), $skip = 0, $limit = 30, $sort = array())
    {
        return self::find($argv, $skip, $limit, $sort);
    }

    /**
     * Fetch One From MongoDB
     *
     * @param array $argv            
     * @param array $fields            
     * @return multitype: boolean
     */
    public static function fetchOne($argv = array(), $fields = array())
    {
        self::init();
        if ($argv) {
            return self::$_mongoDB->findOne($argv, $fields);
        }
        return FALSE;
    }

    /**
     * Fetch All From MongoDB
     *
     * @param array $argv            
     * @param array $fields            
     * @return Ambigous <multitype:, multitype:>|boolean
     */
    public static function fetchAll($argv = array(), $fields = array())
    {
        self::init();
        if ($argv) {
            $result = self::$_mongoDB->find($argv, $fields);
            return self::toArray($result);
        }
        return FALSE;
    }

    /**
     * Fetch From Mongodb
     *
     * @param array $argv            
     * @param number $skip            
     * @param number $limit            
     * @param array $sort            
     * @return Ambigous <multitype:, multitype:>|boolean
     */
    public static function find($argv = array(), $skip = 0, $limit = 30, $sort = array())
    {
        self::init();
        if ($argv) {
            $result = self::$_mongoDB->find($argv)
                ->skip($skip)
                ->limit($limit)
                ->sort($sort);
            return self::toArray($result);
        }
        return array();
    }

    /**
     * Fetch By MongoId
     *
     * @param string $_id            
     * @return Ambigous <Ambigous, boolean, multitype:>
     */
    public static function findById($_id = '')
    {
        if (is_string($_id)) {
            return self::fetchOne(array(
                '_id' => new MongoId($_id)
            ));
        }
    }

    /**
     * Update MongoDB
     *
     * @param array $argv            
     * @param array $newData            
     * @param string $options            
     */
    public static function update($argv = array(), $newData = array(), $options = 'multiple')
    {
        self::init();
        self::$_mongoDB->update($argv, array(
            '$set' => $newData
        ), array(
            "{$options}" => true
        ));
    }

    /**
     * Update MongoDB By Id
     *
     * @param string $_id            
     * @param array $newData            
     */
    public static function updateById($_id, $newData = array())
    {
        $result = array();
        if (is_string($_id)) {
            $result = self::update(array(
                '_id' => new MongoId($_id)
            ), $newData);
        }
        return $result;
    }

    /**
     * Insert Into Mongodb
     *
     * @param array $data            
     */
    public static function insert($data = array())
    {
        self::init();
        return self::$_mongoDB->insert($data);
    }

    /**
     * Remove All From Mongodb
     *
     * @param array $argv            
     */
    public static function remove($argv = array())
    {
        self::init();
        return self::$_mongoDB->remove($argv);
    }

    /**
     * Remove By Id From Mongodb
     *
     * @param string $_id            
     * @return Ambigous <boolean, multitype:>
     */
    public static function removeById($_id)
    {
        return self::removeOne(array(
            '_id' => new MongoId($_id)
        ));
    }

    /**
     * Remove One From Mongodb
     *
     * @param array $argv            
     */
    public static function removeOne($argv = array())
    {
        self::init();
        return self::$_mongoDB->remove($argv, array(
            "justOne" => true
        ));
    }

    /**
     * Mongodb Object To Array
     *
     * @param array $data            
     * @return multitype:
     */
    private static function toArray($data)
    {
        return iterator_to_array($data);
    }
}