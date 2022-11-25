<?php session_start(); 

require __DIR__ . "/db/connection.php";

$req = $db->prepare("SELECT * FROM books ORDER BY created_at DESC");
$req->execute();
$books = $req->fetchAll();

?>


<?php include __DIR__ . "/partials/head.php"; ?>

    <main>
        <h1>Liste des livres</h1>

        <?php if(isset($_SESSION["success"]) && !empty($_SESSION["success"])) :?>
                <div class="success">
                    <?= $_SESSION["success"] ?>
                </div>
                <?php unset($_SESSION["success"]); ?>
        <?php endif ?>

        <div>
            <a href="create.php">Ajouter un livre</a>
        </div>

        <div>
                <?php if(isset($books) && !empty($books)) : ?>
                    <?php foreach($books as $book) : ?>
                        <div class="card">
                            <h2>Titre: <?= htmlspecialchars($book["title"]) ?></h2>
                            <hr>
                            <p>Genre: <?= htmlspecialchars($book["genre"]) ?></p>
                            <p>Auteur: <?= htmlspecialchars($book["author"]) ?></p>
                            <p>Note: <?= htmlspecialchars($book["review"]) ?></p>
                            <a href="edit.php?book_id=<?= htmlspecialchars($book["id"]) ?>">Modifier</a>
                            <a href="delete.php?book_id=<?= htmlspecialchars($book["id"]) ?>" onclick="return confirm('Voulez-vous supprimer ce livre ?')">Supprimer</a>
                        </div>
                    <?php endforeach ?>   
                <?php else : ?>
                    <p>Aucun livre ajouté</p>
                <?php endif ?>
            </div>
    </main>

<?php include __DIR__ . "/partials/foot.php"; ?>