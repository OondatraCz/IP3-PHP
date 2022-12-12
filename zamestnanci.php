<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamestnanci</title>
</head>
<body>
    <?php
require_once("connectToDB.php");
$sortType = filter_input(INPUT_GET, "sort");

switch($sortType){
    case "Jmeno_Up":
        $stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY fullname');
        break;
    case "Jmeno_Down":
        $stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY fullname DESC');
        break;
    case "Mistnost_Up":
        $stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY room');
        break;
    case "Mistnost_Down":
        $stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY room DESC');
        break;
    case "Telefon_Up":
        $stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY phone');
        break;
    case "Telefon_Down":
        $stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY phone DESC');
        break;
    case "Pozice_Up":
        $stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY job');
        break;
    case "Pozice_Down":
        $stmt = $pdo->query('SELECT CONCAT(e.name, " ", e.surname) AS fullname, r.name, r.phone, e.job, e.employee_id FROM employee e INNER JOIN room r ON e.room = r.room_id ORDER BY job DESC');
        break;
}




if ($stmt->rowCount() == 0) {
    echo "Záznam neobsahuje žádná data";
} else {
    $html = "<table><tbody><tr><th>Jméno<a href=zamestnanci.php?sort=Jmeno_Up>↑</a><a href=zamestnanci.php?sort=Jmeno_Down>↓</a></th><th>Místnost<a href=zamestnanci.php?sort=Mistnost_Up>↑</a><a href=zamestnanci.php?sort=Mistnost_Down>↓</a></th><th>Telefon<a href=zamestnanci.php?sort=Telefon_Up>↑</a><a href=zamestnanci.php?sort=Telefon_Down>↓</a></th><th>Pozice<a href=zamestnanci.php?sort=Pozice_Up>↑</a><a href=zamestnanci.php?sort=Pozice_Down>↓</a></th></tr>";

    foreach ($stmt as $row){
        $html .= "<tr><th><a href=infoZamestnanec.php?id=" . $row->employee_id . ">" . $row->fullname . "</a></th><th>" . $row->name . "</th><th>" . $row->phone . "</th><th>" . $row->job . "</th></tr>";
        //$html .= "<tr><th><a href=infoZamestnanec.php?employeeId=" . $row['surname'] . ">" . $row['surname'] . " " . $row['name'] . "</a>" . "</th><th>" . $idk['name'] . "</th><th>" . $idk['phone'] . "</th><th>" . $row['job'] . "</th></tr>";
    }
    $html .= "</tbody></table>";
    echo $html;
}
?>
</body>
</html>