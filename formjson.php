<?php
function ConnectMySQL()
{
    try 
    {
        $db = new PDO('mysql:host=localhost;dbname=todolist;charset=utf8', 'root', 'user');
    }   
    catch(Exception $e) 
    {
        die('Erreur: ' .$e->getMessage());
    }

    return $db;
}
  /*Sanitisation*/
  $options = array(
    'task' => FILTER_SANITIZE_STRING,
    'taskline' => FILTER_SANITIZE_STRING // ID ?
  );
  $result = filter_input_array(INPUT_POST, $options);
  /*fin Sanitisation*/
  //Requête POST:
  //vérification des valeurs après la Sanitisation

  if($result != null && $result != FALSE && $_SERVER['REQUEST_METHOD']=='POST')
{
    if(isset($_POST["add"])){
      $task=$_POST["task"];
      insertmysql($task, "0");
    }
}
 
  function affichemysql($status="0")
  {
    $db = ConnectMySQL();
    $answer = $db->query('SELECT * FROM `tasks`');
    
    while ($line=$answer->fetch())
    {
       
    if($line['status'] == $status)
      {
        $i = $line['id']; 
        $txt = '<div class="draggable">';
        $txt .= '<label class="';
        $txt .= $status=="1"?"0":"1";
        $txt .= '" for="">';
        /*début : balise <input>*/
        $txt .= '<input type="checkbox" name="taskline[]" value="';  // ID ?
        /*$i représente le numero de la ligne*/
        $txt .= $i.'" ';
        /*si la valeur $archive est vraie ajouter l'attribut "checked" */
        //$txt .= $archive=="true"?"checked":"";
        $txt .= ">";
        //$ligne['archive'] = true;
        /*fin : balise <input>*/
        /*balise fermante <label>*/
        $txt .= $line['task'].'</label>';
        $txt .= "<br/>";
        $txt .= '</div>';
        echo $txt;
      }
    }
  }

  function insertmysql($task, $status)
  {
    $db = ConnectMySQL();
    $req = $db->prepare("INSERT INTO tasks(task, status) VALUES(:task, :status)");
    $req->execute(array(
        "task" => $task,
        "status" => $status
  ));
    
  }

  function updatemysql($id)
  {
    $db = ConnectMySQL();
    $answer = $db->prepare("SELECT * FROM tasks WHERE id = :id LIMIT 0,1"); 
    $answer->execute(array(
        "id" => $id,
        ));
    $line = $answer->fetch();
    $status = $line["status"] == "1" ? "0": "1";
    $req = $db->prepare("UPDATE tasks SET status = :status WHERE id = :id");
    $req->execute(array(
        "id" => $id,
        "status" => $status
        ));
  }

  function deletemysql($id)
  {
    $db = ConnectMySQL();
    $answer = $db->prepare("DELETE FROM tasks WHERE id = :id"); 
    $answer->execute(array(
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
                <form action="formjson.php" method="POST">
               
                <?php affichemysql("0"); ?>

                    <input class="button" type="submit" name="submit" value="Archiver">
                </form>
            </div>
            <div class="done">
                <h3>Archive</h3>
                <?php affichemysql("1"); ?>
                <input class="button" type="submit" name="back" value="Refaire">
                <input class="button" type="submit" name="Supprimer" value="Supprimer"> 
            </div>
        </section>
        <section class="addtask">
            <div>
                <h2>Ajouter une Tâche</h2>
                <form action="formjson.php" method="POST">
                <?php if (isset($errors)) { ?>
                <p><?php echo $errors; ?></p>
                <?php } ?>
                    <label for="task">La tâche à effectuer</label>
                    <input type="text" name="task" value="">
                    <input type="submit" name="add" value="Ajouter">
                </form>
            </div>
        </section>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="theme.js"></script>
</body>
</html>