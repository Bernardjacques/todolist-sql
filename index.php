<?php
 
try {
    $db = new PDO('mysql:host=localhost;dbname=id4956572_todolist;charset=utf8', 'id4956572_nhanar', 'user64');
}   

catch(Exception $erreur) {
    die('Erreur: ' .$erreur->getMessage());

}

  $options = array(
    'task' => FILTER_SANITIZE_STRING
  );
  $result = filter_input_array(INPUT_POST, $options);

  //Add SQL Entry

  if(isset($_POST['submit']) && !empty($_POST['newtask']))
  {
    $newtask = ($_POST['newtask']);

    global $db;    

    $sql = "INSERT INTO tasks (task, status) VALUES ('$newtask', '0')";
    $db->exec($sql);
  }

// Change SQL Value

  function updatemysql($id, $status)
  {
    global $db;

    $sql = $db->prepare("UPDATE tasks SET status = :status WHERE id = :id");
    $sql->execute(array(
        "id" => $id,
        "status" => $status
        ));
  }

  if(isset($_POST['archiving']))
  {
    for($i = 0; $i < count($_POST["task"]); $i++) 
    {
        if(isset($_POST["status"][$i]) && $_POST["status"][$i] == '1') 
        {
            updatemysql($_POST["task"][$i], 1);
        }
    }
  }

  if(isset($_POST['uncheck']))
  {
    for($i = 0; $i < count($_POST["task"]); $i++) 
    {
        if(isset($_POST["status"][$i])) 
        {
            updatemysql($_POST["task"][$i], 0);
        }
    }
  }

// Delete SQL Entry

  function deletemysql($id)
  {
    global $db;
    $sql = $db->prepare("DELETE FROM tasks WHERE id = :id"); 
    $sql->execute(array(
        "id" => $id
        ));

  }

  if(isset($_POST['delete']))
  {
    for($i = 0; $i < count($_POST["task"]); $i++) 
    {
        if(isset($_POST["status"][$i]) && $_POST["status"][$i] == '1') 
        {
            deletemysql($_POST["task"][$i], 1);
        }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link id="pagestyle" href="style.css" rel="stylesheet">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/grundschrift" type="text/css"/> 
    <title>Todo-List</title>
</head>
<body>
    <h1>TO-DO List</h1>
    <section class="notepad">
        <div class="bookmark">
            <input id="stylesheet1" type="image" src="images/sun.png" />
            <input id="stylesheet2" type="image" src="images/moon.png" />
        </div>
        <section class="task_list">
            <div class="todo">
                <h2>À Faire</h2>
                <form action="index.php" method="post">
                    <?php
                        $result = $db->query('SELECT * FROM tasks WHERE status="0"');
                        $resultArr = $result->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($resultArr as $key => $task)
                        {
                            print $task["task"];
                            echo "<input type='hidden' name='task[$key]' value='".$task["id"]."' />";
                            echo "<input type='checkbox' name='status[$key]' value='1'/>";
                            echo "<br/>";
                        }
                    ?>
                    <input class="button" type="submit" name="archiving" value="Fini">
                    
                </form>
            </div>
            <div class="done">
                <h2>Archive</h2>
                <form class="archive" action="index.php" method="post" name="formchecked">
                    <p>
                        <?php
                        $result = $db->query('SELECT * FROM tasks WHERE status="1"');
                        $resultArr = $result->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($resultArr as $key => $task)
                        {
                            print $task["task"];
                            echo "<input type='hidden' name='task[$key]' value='".$task["id"]."' />";
                            echo "<input type='checkbox' name='status[$key]' value='1' />";
                            echo "<br/>";
                        }
                    ?>
                    </p>
                    <input class= "button" type="submit" name="uncheck" value="uncheck">
                    <input class= "button" type="submit" name="delete" value="delete">
                </form>
            </div>
        </section>
        <section class="addtask">
            <div>
                <h2>Ajouter une Tâche</h2>
                <form action="index.php" method="post">
                <input type="text" name="newtask" value="" placeholder="Entrer votre tâche">
                <input class="button" type="submit" name="submit" value="Valider">
                </form>
            </div>
        </section>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="theme.js"></script>
</body>
</html>