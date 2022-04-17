<?php
/**
 * root/php/create-tables.PHP
 *
 * Creates SQLite tables for user if not created
 *
 * PHP version 7.0.33-0+deb9u12
 *
 * @category  ?
 * @package   ?
 * @author    Steve Greig <greigsteve@gmail.com>
 * @copyright 2022 Steve Greig
 * @license   GPL-3.0-or-later gnu.org
 * @version   0
 * @link      http://stevespages.org.uk/
 */

$tableName = "uid".$_SESSION["uid"]."courses";
$sql = "CREATE TABLE IF NOT EXISTS '$tableName' (id INTEGER PRIMARY KEY, ";
$sql .= "name TEXT, postcode TEXT, ";
$sql .= "h1par INTEGER, h1si INTEGER, h2par INTEGER, h2si INTEGER, ";
$sql .= "h3par INTEGER, h3si INTEGER, h4par INTEGER, h4si INTEGER, ";
$sql .= "h5par INTEGER, h5si INTEGER, h6par INTEGER, h6si INTEGER, ";
$sql .= "h7par INTEGER, h7si INTEGER, h8par INTEGER, h8si INTEGER, ";
$sql .= "h9par INTEGER, h9si INTEGER, h10par INTEGER, h10si INTEGER, ";
$sql .= "h11par INTEGER, h11si INTEGER, h12par INTEGER, h12si INTEGER, ";
$sql .= "h13par INTEGER, h13si INTEGER, h14par INTEGER, h14si INTEGER, ";
$sql .= "h15par INTEGER, h15si INTEGER, h16par INTEGER, h16si INTEGER, ";
$sql .= "h17par INTEGER, h17si INTEGER, h18par INTEGER, h18si INTEGER)";
$stmt = $db->prepare($sql);
$stmt->execute();

$tableName = "uid".$_SESSION["uid"]."players";
$sql = "CREATE TABLE IF NOT EXISTS '$tableName' ";
$sql .= "(id INTEGER PRIMARY KEY, name TEXT, email TEXT)";
$stmt = $db->prepare($sql);
$stmt->execute();

$tablename = "uid".$_SESSION["uid"]."scores";
$sql = "CREATE TABLE IF NOT EXISTS '{$tablename}'";
$sql .= " (id INTEGER PRIMARY KEY, id_competitions INTEGER,";
$sql .= " id_players INTEGER, handicap INTEGER,";
$sql .= " h1 INTEGER, h2 INTEGER, h3 INTEGER, h4 INTEGER,";
$sql .= " h5 INTEGER, h6 INTEGER, h7 INTEGER, h8 INTEGER,";
$sql .= " h9 INTEGER, h10 INTEGER, h11 INTEGER, h12 INTEGER,";
$sql .= " h13 INTEGER, h14 INTEGER, h15 INTEGER, h16 INTEGER,";
$sql .= " h17 INTEGER, h18 INTEGER)";
$stmt = $db->prepare($sql);
$stmt->execute();

$tableName = "uid".$_SESSION["uid"]."competitions";
$sql = "CREATE TABLE IF NOT EXISTS '$tableName' ";
$sql .= "(id INTEGER PRIMARY KEY, name TEXT, ";
$sql .= "id_course INTEGER, date TEXT, time TEXT)";
$stmt = $db->prepare($sql);
$stmt->execute();

$tableName = "uid".$_SESSION["uid"]."emails";
$sql = "CREATE TABLE IF NOT EXISTS '$tableName' (id INTEGER PRIMARY KEY, id_players TEXT, id_teams TEXT, id_competitions TEXT, token TEXT)";
$stmt = $db->prepare($sql);
$stmt->execute();

$tableName = "uid".$_SESSION["uid"]."teams";
$sql = "CREATE TABLE IF NOT EXISTS '$tableName' (id INTEGER PRIMARY KEY, ";
$sql .= "name TEXT, id_competitions INTEGER)";
$stmt = $db->prepare($sql);
$stmt->execute();

$tableName = "uid".$_SESSION["uid"]."teams_players";
$sql = "CREATE TABLE IF NOT EXISTS '$tableName' (id INTEGER PRIMARY KEY, ";
$sql .= "id_teams INTEGER, id_players INTEGER, id_competitions INTEGER)";
$stmt = $db->prepare($sql);
$stmt->execute();

