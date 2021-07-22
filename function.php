<?php

require 'vendor/autoload.php';
use Tracy\Debugger;
Debugger::enable();

# MySQL

/*

$dbType     = "mysql";
$hostname   = "localhost";
$username   = "root";
$password   = "";
$DB         = "experimental_db";
$port       = "3306";

*/

# PostgreSQL

$dbType     = "pgsql";
$hostname   = "rosie.db.elephantsql.com";
$username   = "admcfoiw";
$password   = "cEm9H-wCgjS2g0BUGmpxuE7LkQh7J4yk";
$DB         = "admcfoiw";
$port       = "5432";

try {
    $pdo = new PDO($dbType.':host='.$hostname.';port='.$port.';dbname='.$DB, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $status = "connected";
}catch(PDOException $e) {
    $status =  "Connection failed: " . $e->getMessage();
}

###### PDO ######

# SELECT

function preparedStatement($string)
{
    global $pdo;

    $statement = $pdo->prepare($string);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_OBJ);
}

function queryPreparedStmt($string)
{
    global $pdo;
    $data = [];

    $statement = $pdo->prepare($string);
    $statement->execute();

    while ($result = $statement->fetch(PDO::FETCH_OBJ)) :
        $data[] = $result;
    endwhile;

    return $data;
}

function queryString($string)
{
    global $pdo;
    $data = [];

    $query = $pdo->query($string);
    
    while ($result = $query->fetch(PDO::FETCH_OBJ)) : /* PDO::FETCH_ASSOC <=> PDO::FETCH_NUM */
       $data[] = $result;
    endwhile;

    return $data;
}

function psByBindCol()
{
    global $pdo;
    $data = [];
    $string = "SELECT data, ref_number, image_blob FROM users_list ORDER BY id DESC";
    $statement = $pdo->prepare($string);
    $statement->execute();

    $statement->bindColumn('data', $texts);
    $statement->bindColumn('ref_number', $integers);
    $statement->bindColumn('image_blob', $img);

    while($result = $statement->fetch(PDO::FETCH_BOUND)):
        $data[] = (object) [ // Array to Object conversion
            'varchar'   => $texts, 
            'int'       => $integers, 
            'blob'      => $img
        ];
    endwhile;

    return $data;
}

# SELECT - conditionnal

function getDataBy($id)
{
    global $pdo;

    $string = "SELECT * FROM users_list WHERE id = :id";
    $query = $pdo->prepare($string);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    return $query->fetch(PDO::FETCH_OBJ);
}

# INSERT

function insert($arr)
{
    global $pdo;
    $string = "INSERT INTO users_list (data, ref_number, image_blob) VALUES (:data, :ref_number, :blob_img)";
    $query = $pdo->prepare($string);

    $query->bindValue(':data', $arr['text'], PDO::PARAM_STR);
    $query->bindValue(':ref_number', $arr['ref'], PDO::PARAM_INT);
    $query->bindValue(':blob_img', $arr['bi'], PDO::PARAM_LOB);
    // $query->bindValue(':blob_img', $arr['bi'], PDO::PARAM_STR);

    # The bindParam () function is used to pass variable not value
    # $query->bindParam(':data', arr['text'], PDO::PARAM_STR);
    # $query->bindParam(':ref_number', arr['ref'], PDO::PARAM_INT);
    # $query->bindParam(':blob_img', arr['bi'], PDO::PARAM_LOB);

    $query->execute();
    return $pdo->lastInsertId();
}

# UPDATE

function edit($arr)
{
    global $pdo;
    $string = "UPDATE users_list SET data = :data, ref_number = :ref_number, image_blob = :blob_img WHERE id= :id";
    $query = $pdo->prepare($string);

    $query->bindValue(':data', $arr['text'], PDO::PARAM_STR);
    $query->bindValue(':ref_number', $arr['ref'], PDO::PARAM_INT);
    $query->bindValue(':blob_img', $arr['bi'], PDO::PARAM_LOB);
    $query->bindValue(':id', $arr['id'], PDO::PARAM_INT);

    $query->execute();
}

# DELETE

function delete($id)
{
    global $pdo;
    $string = "DELETE FROM users_list WHERE id = ".$id;
    $pdo->exec($string);
}

# ================================================================== #

# App- functions

// dump(psByBindCol());

function display()
{
    $sql = "SELECT * FROM users_list ORDER BY id DESC";
    return preparedStatement($sql);
}

function displayOne()
{
    return getDataBy($_GET['id']);
}

# App - Form events

if (isset($_POST['btnInsert'])) :
    $arr = [
        'text'  => $_POST['data'],
        'ref'   => $_POST['intergers'],
        'bi'    => fopen($_FILES['img']['tmp_name'], 'rb')
    ];
    insert($arr);
endif;

if (isset($_POST['btnUpdate'])) :
    $img = ( isset($_FILES['img']['tmp_name']) && !empty($_FILES['img']['tmp_name']) ) ? fopen($_FILES['img']['tmp_name'], 'rb') : displayOne()->image_blob;
    $arr = [
        'text'  => $_POST['data'],
        'ref'   => $_POST['intergers'],
        'bi'    => $img,
        'id'    => $_GET['id'],
    ];
    edit($arr);
endif;

if (isset($_GET['delete']) && $_GET['delete'] == true) :
    delete($_GET['id']);
endif;