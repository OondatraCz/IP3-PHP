<?php
require_once("connectToDB.php");
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT, ["options" => ["min_range"=> 1]]);

if ($id === null || $id === false) {
    http_response_code(400);
    $status = "bad_request";
} else {
    $rowTitles = ["Jméno", "Příjmení", "Pozice", "Mzda", "Místnost"];

    $stmt = $pdo->prepare("SELECT e.name, e.surname, e.job, e.wage, r.name AS roomname, e.room FROM employee e JOIN room r ON r.room_id = e.room AND e.employee_id = :id");
    
    $stmt->execute(["id" => $id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        $status = "not_found";



    } else {
        $employee = $stmt->fetch();
        $status = "OK";

        $html = "<h1>Karta osoby: ".$employee->name." ".$employee->surname."</h1><dl>";
        $i = 0;
        foreach ($employee as $key => $value) {
            $text1 = "";
            $text2 = "";
            if($key == "roomname"){
                $text1 = "<a href=infoMistnost.php?id=".$employee->room.">";
                $text2 = "</a>";
            }
            elseif($key == "room"){
                break;
            }
            $html .= "<dt>".(isset($rowTitles[$i])?$rowTitles[$i]:$key)."</dt>".$text1."<dd>".$value."</dd>".$text2;
            $i++;
        }

        $stmt = $pdo->prepare("SELECT r.name, r.room_id FROM room r JOIN `key` k ON r.room_id = k.room AND k.employee = :id");
        
        $stmt->execute(["id" => $id]);

        $html .= "<dt>Klíče</dt>";
        while ($row = $stmt->fetch()) {
            $html .= "<a href=infoMistnost.php?id=".$row->room_id."><dd>".$row->name."</dd></a>";
        }
        $html .= "</dl><a href='zamestnanci.php?sort=Jmeno_Up'><button>Jít zpět</button></a>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karta osoby</title>
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