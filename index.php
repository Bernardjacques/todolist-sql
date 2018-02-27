<?php
function ecrireJSON($tache, $terminer)
{
  $filename = "./todo.json";
  $tab = array("Nom" => $tache, "Terminer" => $terminer);
  $json = json_encode($tab);
  $json .= "\n";
  $file=file_put_contents($filename, $json, FILE_APPEND | LOCK_EX);
}
function afficheJSON($terminer)
{
  $filename = "./todo.json";
  $tab = file($filename);
  for($i=0; $i < sizeof($tab); $i++)
  {
    $obj = json_decode($tab[$i]);
    $nom = $obj->{'Nom'};
    if($obj->{'Terminer'} == $terminer)
     {
      $txt = '<input type="checkbox" name="tache_ligne[]" value="';
      $txt .= $i.'" ';
      $txt .= $terminer?"checked":"";
      $txt .= ">";
      $txt .= '<label ';
      $txt .= 'class="';
      $txt .= $terminer?"tache_terminer":"tache_non_terminer";
      $txt .= '" ';
      $txt .= 'for="'.$nom.'">'.$nom.'</label>';
      $txt .= "<br/>";
      echo $txt;
    }
  }
}
function enregistreJSON($ligne)
{

  $ligne = (int)$ligne;
  $filename = "./todo.json";
  $tab_file = file($filename);
  $obj = json_decode($tab_file[$ligne]);
  $nom = $obj->{'Nom'};
  $tab = array("Nom" => $nom, "Terminer" => true );
  $json = json_encode($tab);
  $json .= "\n";
  $tab_file[$ligne] = $json;
  $file=file_put_contents($filename, $tab_file, LOCK_EX);
}
$options = array(
  'tache'     => FILTER_SANITIZE_STRING
);
$result = filter_input_array(INPUT_POST, $options);
if($result != null && $result != FALSE && $_SERVER['REQUEST_METHOD']=='POST'){
  if(isset($_POST["ajouter"]) && $_POST["ajouter"] == "Ajouter"){
    $tache=$_POST["tache"];
    if(!empty($tache)){
      ecrireJSON($tache, false);
    }
  }
  if(isset($_POST["submit"]) && $_POST["submit"] == "Enregistrer"){
    $tache_ligne = $_POST["tache_ligne"];
    for ($i = 0; $i <sizeof($tache_ligne); $i++){
      enregistreJSON($tache_ligne[$i]);
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
    <link href="style.css" rel="stylesheet">
    <title>Todo-List</title>
</head>
<body>
    <section class="task_list">
            <h4>À Faire</h4>
              <form action="formjson.php" method="POST">
                <?php
                  afficheJSON(false);
                ?>
                <input type="submit" name="submit" value="Enregistrer">
              </form>
            <h4>Archive</h4>
                <?php
                  afficheJSON(true);
                ?>
    </section>

    <section>
        <div class="addtask">
            <h2>Ajouter une Tâche</h2>
            <form action="formjson.php" method="POST">
                <label for="task">La tâche à effectuer</label>
                <input type="text" name="tache" value="">
                <input type="submit" name="ajouter" value="Ajouter">
            </form>
        </div>
    </section>
</body>
</html>
