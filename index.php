<?php
 
try {
    //je me connecte a MySQL
    $db = new PDO('mysql:host=localhost;dbname=todolist;charset=utf8', 'root', 'user');
}   

catch(Exception $erreur) {
    // En cas d'erreur, on affiche un message et on arrête tout
    die('Erreur: ' .$erreur->getMessage());

}

/*FORMULAIRE*/
  /*Sanitisation*/
  $options = array(
    'task' => FILTER_SANITIZE_STRING,
    'taskligne' => FILTER_SANITIZE_STRING
  );
  $result = filter_input_array(INPUT_POST, $options);
//   /*fin Sanitisation*/
//   //sqluête POST:
//   //vérification des valeurs après la Sanitisation
//   if($result != null && $result != FALSE && $_SERVER['REQUEST_METHOD']=='POST')
//   {

    if(isset($_POST["task"])){

      $task=$_POST["task"];
      insertmysql($task, 0);
    }

    // if(isset($_POST["submit"]) || isset($_POST["uncheck"])) {

    //   $task_ligne = $_POST["taskligne"];
    //   // print_r($task_ligne);
    //   for($i = 0; $i < sizeof($task_ligne); $i++){
    //     updatemysql($task_ligne[$i]);
    //     // enregistreJSON($task_ligne);

    //   } 
    // }

    if(isset($_POST["Supprimer"])){
      
      $task_ligne = $_POST["taskligne"];
      // print_r($task_ligne);
      for($i = 0; $i < sizeof($task_ligne); $i++){
        deletemysql($task_ligne[$i]);
      }
    }
    /*nom de la task contenu dans le "TextBox"*/
    /*$task=$_POST["task"];
    /utilisation de la fonction ecrireJSON/
    /ecrireJSON($task, false);*/


  function insertmysql($task, $status)
  {
      global $db;    
    $sql = $db->prepare("INSERT INTO `tasks`(`task`, `status`) VALUES (:task,:status)");
    // $sql = $db->prepare("INSERT INTO tasks(task, status) VALUES(:task, :status)");

    $sql->execute(array(
        "task" => $task,
        "status" => $status
  ));
    
  }

  function updatemysql($id)
  {
    global $db;
    $reponse = $db->prepare("SELECT * FROM tasks WHERE id = :id LIMIT 0,1"); 
    $reponse->execute(array(
        "id" => $id,
        ));

    $ligne = $reponse->fetch();
    $status = $ligne["status"] == "true" ? "false": "true";

    $sql = $db->prepare("UPDATE tasks SET status = :status WHERE id = :id");
    $sql->execute(array(
        "id" => $id,
        "status" => $status
        ));
  }
  function deletemysql($id)
  {
    global $db;
    $reponse = $db->prepare("DELETE FROM tasks WHERE id = :id"); 
    $reponse->execute(array(
        "id" => $id,
        ));

  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/grundschrift" type="text/css"/> 
    <title>Todo-List</title>
</head>
<body>
    <h1>TO-DO List</h1>
    <section class="notepad">
        <div class="bookmark">
            <button type="button" class="header--theme-button active" style="--theme-primary:orange; --theme-secondary:white;">
                </button>
            <button type="button" class="header--theme-button" style="--theme-primary:#2196F3; --theme-secondary:#eee;">
                </button>
            <button type="button" class="header--theme-button" style="--theme-primary:purple; --theme-secondary:white;">
                </button>
            <button type="button" class="header--theme-button" style="--theme-primary:#F44336; --theme-secondary:white;">
                </button>
            <button type="button" class="header--theme-button" style="--theme-primary:green; --theme-secondary:white;">
                </button>
            <button type="button" class="header--theme-button" style="--theme-primary:#FFEB3B; --theme-secondary:#222;">
                </button>
        </div>
        <section class="task_list">
            <div class="todo">
                <h3>À Faire</h3>
                <form action="index.php" method="post">
                    <?php
                        $result = $db->query('SELECT * FROM tasks WHERE status="0"');
                        $resultArr = $result->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($resultArr as $task)
                        {
                            print $task["task"] . "-" . $status["0"];
                            echo "<input type='checkbox' name='tache[]' value='".($donnees['nomtache'])."'/>
                            <label for='choix'>".($donnees['nomtache'])."</label><br />";
                        }
                    ?>
                    <input class="button" type="submit" name="archiver" value="Fini">
                    
                </form>
            </div>
            <div class="done">
                <h3>Archive</h3>
                <form action="index.php" method="post" name="formchecked">
                    <?php
                        $result = $db->query('SELECT * FROM tasks WHERE status="1"');
                        $resultArr = $result->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($resultArr as $task)
                        {
                            print $task["task"] . "-" . $status["1"];
                            echo "<input type='checkbox' name='tache[]' value='".($donnees['nomtache'])."'/>
                            <label for='choix'>".($donnees['nomtache'])."</label><br />";
                        }
                    ?>
                    <input class= "button" type="submit" name="uncheck" value="uncheck">
                    <input class= "button" type="submit" name="delete" value="delete">
                </form>
            </div>
        </section>
        <section class="addtask">
            <div>
                <h2>Ajouter une Tâche</h2>
                <form action="index.php" method="POST">
                <input type="text" name="task" value="">
                <input class="button" type="submit" name="submit" value="Valider">
                </form>
            </div>
        </section>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="theme.js"></script>
</body>
</html>