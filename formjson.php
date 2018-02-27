<?php
$db = mysqli_connect("localhost","root","","todolist");

// Check connection

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  if (isset($_POST['submit'])) {
    if (empty($_POST['task'])) {
        $errors = "You must fill in the task";
    }else{
        $task = $_POST['task'];
        $sql = "INSERT INTO tâches (tâche) VALUES ('$task')";
        mysqli_query($db, $sql);
        header('location: formjson.php');
    }
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
                <?php 
                    // select all tasks if page is visited or refreshed
                    $tâches = mysqli_query($db, "SELECT * FROM tâche");

                    $i = 1; while ($row = mysqli_fetch_array($tâches)) { 
                ?>
                    <input class="button" type="submit" name="submit" value="Enregistrer">
                </form>
            </div>
            <div class="done">
                <h3>Archive</h3>
                <p>

                </p>
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
                    <input type="text" name="tache" value="">
                    <input type="submit" name="ajouter" value="Ajouter">
                </form>
            </div>
        </section>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="theme.js"></script>
</body>
</html>