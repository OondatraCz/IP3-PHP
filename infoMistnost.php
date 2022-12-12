<?php
require_once("connectToDB.php");

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT, ["options" => ["min_range"=> 1]]);

if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
} else {
    $rowTitles = ["Číslo", "Název", "Telefon"];

    $stmt = $pdo->query("SELECT `no`, `name`, phone FROM room WHERE room_id = ".$id);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        $status = "not_found";
    } else {
        $zamestnanec = $stmt->fetch();
        $status = "OK";

        $html = "<h1>Místnost č. " . $zamestnanec->no . "</h1><dl>";
        $i = 0;
        foreach ($zamestnanec as $key => $value) {
            $text1 = "";
            $text2 = "";
            if($key == "roomname"){
                $text1 = "<a href=room.php?roomId=" . $zamestnanec->room . ">";
                $text2 = "</a>";
            }
            elseif($key == "room"){
                break;
            }
            $html .= "<dt>" . (isset($rowTitles[$i])?$rowTitles[$i]:$key) . "</dt>" . $text1 . "<dd>" . $value . "</dd>" . $text2;
            $i++;
        }

        $stmt = $pdo->query('SELECT CONCAT(`name`," ",surname) AS fullname, employee_id FROM employee WHERE room =' . $id);

        $html .= "<dt>Lidé</dt>";
        
        while ($row = $stmt->fetch()) {
            $html .= "<a href=infoZamestnanec.php?id=" . $row->employee_id . "><dd>" . $row->fullname . "</dd></a>";
        }

        $stmt = $pdo->query("SELECT AVG(wage) AS wage FROM employee WHERE room =" . $id);
        
        $zamestnanec = $stmt->fetch();
        $html .= "<dt>Průměrná mzda</dt><dd>" . $zamestnanec->wage . "</dd><dt>Klíče</dt>";
        
        $stmt = $pdo->query('SELECT CONCAT(e.name," ", e.surname) AS fullname, e.employee_id FROM employee e JOIN `key` k ON e.employee_id = k.employee WHERE k.room = ' . $id);

        while ($row = $stmt->fetch()) {
            $html .= "<a href=infoZamestnanec.php?id=" . $row->employee_id . "><dd>" . $row->fullname . "</dd></a>";
        }

        $html .= "</dl>";
        $html .= "<a href=mistnosti.php?sort=Nazev_Up><button>Jít zpět</button></a>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detaily mistnosti</title>
</head>
<body>
<?php
switch ($status) {
    case "bad_request":
        echo "<h1>Error 400: Bad request</h1>";
        break;
    case "not_found":
        echo "<h1>Error 404: Not found</h1>";
        break;
    default:
        echo $html;
        break;
}
?>
</body>
</html>