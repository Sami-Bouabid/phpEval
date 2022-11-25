<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $postClean = [];
        $errors = [];

        foreach ($_POST as $key => $value) 
        {
            $postClean[$key] = strip_tags(trim($value));
        }

        if (isset($postClean["title"])) 
        {
           if (empty($postClean["title"]))
           {
                $errors["title"] = "Le nom du livre est obligatoire.";
           }
           else if(mb_strlen($postClean["title"]) > 255)
           {
                $errors["title"] = "Livre doit contenir 255 caracteres max";
           }
        }

        if (isset($postClean["genre"])) 
        {
           if (empty($postClean["genre"]))
           {
                $errors["genre"] = "Le nom du livre est obligatoire.";
           }
           else if(mb_strlen($postClean["genre"]) > 255)
           {
                $errors["genre"] = "Livre doit contenir 255 caracteres max";
           }
        }

        if (isset($postClean["author"])) 
        {
           if (empty($postClean["author"]))
           {
                $errors["author"] = "Le nom du livre est obligatoire.";
           }
           else if(mb_strlen($postClean["author"]) > 255)
           {
                $errors["author"] = "Livre doit contenir 255 caracteres max";
           }
        }

        if (isset($postClean["review"])) 
        {
           if (is_string($postClean["review"]) && ($postClean["review"] == ''))
           {
                $errors["review"] = "La note est obligatoire";
           } 
           else if(empty($postClean["review"]) && ($postClean["review"]) != 0) 
           {
                $errors["review"] = "La note est obligatoire.";
           }
           elseif (!is_numeric($postClean["review"])) 
           {
                $errors["review"] = "La note doit être un nombre";
           }
           elseif (($postClean["review"] < 0) || ($postClean["review"] > 10)) 
           {
                $errors["review"] = "La note doit être comprise entre 0 et 10";
           }
           
        }

        if (count($errors) > 0) 
        {
            $_SESSION["errors"] = $errors;
            $_SESSION["old"] = $postClean;
            
            return header("Location: " . $_SERVER["HTTP_REFERER"]);
        }

        $bookTitle = $postClean["title"];
        $bookGenre = $postClean["genre"];
        $bookAuthor = $postClean["author"];
        $bookReview = round($postClean["review"], 1);

        require __DIR__ . "/db/connection.php";

        $req = $db->prepare("INSERT INTO books (title, genre, author, review, created_at, updated_at) VALUES (:title, :genre, :author, :review, now(), now() )");

        $req->bindValue(":title", $bookTitle);
        $req->bindValue(":genre", $bookGenre);
        $req->bindValue(":author", $bookAuthor);
        $req->bindValue(":review", $bookReview);

        $req->execute();
        $req->closeCursor();

        $_SESSION["success"] = "Le livre a été ajouté à la liste";
        return header("Location: index.php");
 
    }        
?>        



<?php include "partials/head.php"; ?>

<main>

    <h1>Ajouter un livre</h1>

    <?php if(isset($_SESSION["errors"]) && !empty($_SESSION["errors"])) : ?>
                <div class="alert">
                    <ul>
                        <?php foreach($_SESSION["errors"] as $error) : ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>       
                    </ul>
                </div>
                <?php unset($_SESSION["errors"]); ?>
    <?php endif ?> 

<div>
                <form method="post">
                    <div>
                        <label for="title"></label>
                        <input type="text" name="title" id="title" placeholder="Titre" >
                    </div>

                    <div>
                        <label for="genre"></label>
                        <input type="text" name="genre" id="genre" placeholder="Genre" >
                    </div>

                    <div>
                        <label for="author"></label>
                        <input type="text" name="author" id="author" placeholder="Auteur" >
                    </div>

                    <div>
                        <label for="review"></label>
                        <input type="text" name="review" id="review" placeholder="Note sur 10">
                    </div>

                    <div>
                        <input type="submit">
                    </div>
                </form>
</div>

</main>



<?php include "partials/foot.php"; ?>