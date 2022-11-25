<?php 
session_start();

if (!isset($_GET['book_id']) || empty($_GET['book_id'])) 
{
    return header("Location: index.php");
}

$bookId = (int) strip_tags($_GET['book_id']);

require __DIR__ . "/db/connection.php";

$req = $db->prepare("SELECT * FROM books WHERE id = :id");
$req-> bindValue(":id", $bookId);
$req-> execute();
$count = $req-> rowCount();

if ($count != 1) 
{
    return header("Location: index.php");
}

$bookToDelete = $req-> fetch();
$req-> closeCursor();

$req = $db-> prepare("DELETE FROM books WHERE id = :id ");
$req-> bindValue(":id", $bookToDelete["id"]);
$req-> execute();
$req-> closeCursor();

$_SESSION["success"] = $bookToDelete['title'] . " a été retiré de la liste";
return header("Location: index.php");

?>