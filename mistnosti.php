<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mistnosti</title>
</head>
<body>
    <?php
require_once("connectToDB.php");
$sortType = filter_input(INPUT_GET, "sort");

switch($sortType){
    case "Nazev_Up":
        $stmt = $pdo->query('SELECT * FROM room ORDER BY name');
        break;
    case "Nazev_Down":
        $stmt = $pdo->query('SELECT * FROM room ORDER BY name DESC');
        break;
    case "Cislo_Up":
        $stmt = $pdo->query('SELECT * FROM room ORDER BY no');
        break;
    case "Cislo_Down":
        $stmt = $pdo->query('SELECT * FROM room ORDER BY no DESC');
        break;
    case "Telefon_Up":
        $stmt = $pdo->query('SELECT * FROM room ORDER BY phone');
        break;
    case "Telefon_Down":
        $stmt = $pdo->query('SELECT * FROM room ORDER BY phone DESC');
        break;
}

if ($stmt->rowCount() == 0) {
    echo "Záznam neobsahuje žádná data";
} else {
    $html = "<table><tbody><tr><th>Název<a href=mistnosti.php?sort=Nazev_Up>↑</a><a href=mistnosti.php?sort=Nazev_Down>↓</a></th><th>Číslo<a href=mistnosti.php?sort=Cislo_Up>↑</a><a href=mistnosti.php?sort=Cislo_Down>↓</a></th><th>Telefon<a href=mistnosti.php?sort=Telefon_Up>↑</a><a href=mistnosti.php?sort=Telefon_Down>↓</a></th></tr>";
    foreach($stmt as $row){
        $html .= "<tr><td><a href=infoMistnost.php?id=" . $row->room_id . ">" . $row->name . "</a></td><td>" . $row->no . "</td><td>" . $row->phone . "</td></tr>";
    }
    $html .= "</tbody></table>";
    echo $html;
}

    ?>
</body>
</html>