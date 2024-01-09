<?php
require_once ('conf.php');
session_start();
global $yhendus;



//punktide lisamine
if(isset($_REQUEST["punktid0"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=0 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["punktid0"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}
//peitmine
if(isset($_REQUEST["peitmine"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET avalik=0 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["peitmine"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}

//naitmine
if(isset($_REQUEST["naitmine"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET avalik=1  WHERE id=?");
    $kask->bind_param("i",$_REQUEST["naitmine"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}

?>
<!doctype html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0,
           maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tantsut tähtega</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<h1>Tantsud tähtega</h1>
<header>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
        <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="login.php">Logi sisse</a>
        <?php
    }
    ?>
</header>
<nav>
    <li><a href="lala.php">Kasutaja Panel</a></li>
    <li><a href="adminleht.php">Admin Panel</a></li>
</nav>
<h2>Administrerimisleht</h2>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Päev</th>
        <th>Kommentaarid</th>
        <th>Avalik</th>
    </tr>
    <?php
    global $yhendus;
    $kask=$yhendus->prepare("SELECT id, tantsupaar,
 punktid,ava_paev,komentaarid,avalik from tantsud");
    $kask->bind_result($id,$tantsupaar,$punktid,$paev, $komment,$avalik);
    $kask->execute();
    while ($kask->fetch()){
        $tekst="Näita";
        $seisunud="naitmine";
        $tekst2="Kasutaja ei näe";
        if ($avalik==1){
            $tekst="Peida";
            $seisunud="peitmine";
            $tekst2="Kasutaja näeb";
        }
        echo "<tr>";
        $tantsupaar=htmlspecialchars($tantsupaar);
        echo "<td>".$tantsupaar."</td>";
        echo "<td>".$punktid."</td>";
        echo "<td>".$paev."</td>";
        echo "<td>".$komment."</td>";
        echo "<td>".$avalik."/".$tekst2."</td>";
        echo "<td><a href='?punktid0=$id'>PUnktid Nulliks!</a></td>";
        echo "<td><a href='?$seisunud=$id'>$tekst</a></td>";
        echo "</tr>";
    }
    ?>

</table>
</body>
</html>


