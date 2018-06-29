<?php
    define('DB_DRIVER','mysql');
    define('DB_HOST','localhost');
    define('DB_NAME','todo');
    define('DB_USER','root');
    define('DB_PASS','');
    try
    {
        $connect_str = DB_DRIVER . ':host='. DB_HOST . ';dbname=' . DB_NAME;
        $db = new PDO($connect_str,DB_USER,DB_PASS);
        $error_array = $db->errorInfo();
        if($db->errorCode() != 0000)
            echo "SQL :fffdf " . $error_array[2] . '<br />';
        $error_array = $db->errorInfo();
        if($db->errorCode() != 0000)
            echo "SQL: " . $error_array[2] . '<br /><br />';
    }
    catch(PDOException $e)
    {
        die("Error: ".$e->getMessage());
    }
    $result = $db->query("SELECT * FROM `tasks` ")
?>
  <style>
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }

    table td, table th {
        border: 1px solid #ccc;
        padding: 5px;
    }

    table th {
        background: #eee;
    }
  </style>
  <h1>Список дел на сегодня</h1>
  <div style="float: left">
    <form method="GET">
        <input type="text" name="description" placeholder="Описание задачи" value="" />
        <input type="submit" name="save" value="Добавить" />
    </form>
  </div>
  <div style="float: left; margin-left: 20px;">
    <form method="POST">
        <label for="sort">Сортировать по:</label>
        <select name="sort_by">
            <option value="date_created">Дате добавления</option>
            <option value="is_done">Статусу</option>
            <option value="description">Описанию</option>
        </select>
        <input type="submit" name="sort" value="Отсортировать" />
    </form>
  </div>
  <div style="clear: both"></div>
  <table>
    <tr>
        <th>Описание задачи</th>
        <th>Дата добавления</th>
        <th>Статус</th>
        <th></th>
    </tr>
    <tr>
<?php
    if (!empty($_GET["save"])&&!empty($_GET["description"])) {
        $a = $_GET["description"];
        $id = $db->lastInsertId();
        $today = date("Y-m-d H:i:s");
        $res1 = $db->exec("INSERT INTO `tasks` (`id`, `description`, `is_done`, `date_added`)
         VALUES ('$id', '$a', '0', '$today')");
        $result = $db->query("SELECT * FROM `tasks` ");
    }
    if (!empty($_POST["sort_by"])&&!empty($_POST["sort"])) {
        if ($_POST["sort_by"]== 'is_done') {
            $result = $db->query("SELECT description,date_added,is_done FROM `tasks` ORDER BY `is_done` DESC");
        }
    }
    if (!empty($_POST["sort_by"])&&!empty($_POST["sort"])) {
        if ($_POST["sort_by"]== 'date_created') {
            $result = $db->query("SELECT description,`date_added`,is_done FROM `tasks` ORDER BY `date_added` DESC");
        }
    }
    if (!empty($_POST["sort_by"])&&!empty($_POST["sort"])) {
        if ($_POST["sort_by"]== 'description') {
            $result = $db->query("SELECT description,`date_added`,is_done FROM `tasks` ORDER BY `description` DESC");
        }
    }
    if (!empty($_GET["action"])) {
        if ($_GET["action"] == "delete"){
            $result1 = $db->query("DELETE FROM `tasks` WHERE id=1");
            $result = $db->query("SELECT * FROM `tasks` ");
        }
    }
    if (!empty($_GET["action"])) {
        if ($_GET["action"] == "done"){
            $res3 = $db->exec("UPDATE tasks SET is_done = 1");
            $result = $db->query("SELECT * FROM `tasks` ");
        }
    }
    if (!empty($_GET['action'])) {
        if ($_GET['action'] == 'rew'){?>
        <div style="float: left">
            <form method="POST">
                <input type="text" name="desc" placeholder="Описание задачи" value=""/>
                <input type="submit" name="rew" value="Изменить"/>
            </form>
        </div>
        <?php
        }
    if (!empty($_POST["desc"])&&!empty($_POST["rew"])) {
        $a = $_POST["desc"];
        $res2 = $db->exec("UPDATE tasks SET description = '$a'");
        $result = $db->query("SELECT * FROM `tasks` ");
    }
        }
    while($row = $result->fetch()){
?>
    <tr>
        <td style="width:6px valign=" center
        " align="center"><?php echo $row['description']; ?> </td>
        <td style="width:400px valign=" center
        " align="center"><?php echo $row['date_added']; ?></td>
        <td style="width:180px valign=" center
        " align="center"><?php echo ($row['is_done'] == 0) ? 'Не выполнено' : 'Выполнено'; ?></td>
        <td style="width:50px valign=" center
        " align="center">
        <a href='index.php?action=rew'>Изменить</a>
        <a href='index.php?action=done'>Выполнить</a>
        <a href='index.php?action=delete'>Удалить</a>
        </td>
    </tr>
<?php
    }
?>
  </table>