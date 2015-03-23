<?php
/**
 * Created by PhpStorm.
 * User: fath
 * Date: 3/23/15
 * Time: 12:02 AM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
class Database
{
    static $db;
    public static function Init($db)
    {
        self::$db = $db;
    }

    public static function MakeTestInstance($testerId, $fileName, $md5, $at, $log)
    {
        $query = self::$db->prepare('INSERT INTO TestInstances (tester_id, file, md5, moment, log) VALUES(?, ?, ?, ?, ?)');
        $query->execute(array($testerId, $fileName, $md5, $at, $log));
        return self::$db->lastInsertId();
    }

    public static function GetTestInstanceByID($id)
    {
        $stmt = self::$db->prepare("SELECT * FROM TestInstances WHERE id=?");
        $stmt->execute(array($id));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows[0];
    }

    public static function TesterByID($id)
    {
        $stmt = self::$db->prepare("SELECT * FROM Testers WHERE id=?");
        $stmt->execute(array($id));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($rows) == 0)
            return null;
        return $rows[0];
    }

    public static function TesterFiles($id)
    {
        $stmt = self::$db->prepare("SELECT * FROM Testers_files WHERE tester_id=?");
        $stmt->execute(array($id));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public static function AllTesters($count = 10)
    {
        $stmt = self::$db->prepare("SELECT * FROM Testers ORDER BY id LIMIT $count");
        $stmt->execute(array());
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public static function AllTests($count = 10)
    {
        $stmt = self::$db->prepare("SELECT * FROM TestInstances ORDER BY id DESC LIMIT $count");
        $stmt->execute(array());
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
}

Database::Init(new PDO('mysql:host=localhost;dbname=sim', "root", ""));