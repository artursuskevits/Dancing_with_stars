<?php
require_once ('conf.php');
global $yhendus;



//punktide lisamine
if(isset($_REQUEST["heatants"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=punktid+1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["heatants"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}

if(isset($_REQUEST["halbtants"])){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE tantsud SET punktid=punktid-1 WHERE id=?");
    $kask->bind_param("i",$_REQUEST["halbtants"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
    exit();
}

if(isset($_REQUEST["kustuta"])){
    global $yhendus;
    $kask=$yhendus->prepare("DELETE FROM tantsud  WHERE id=?");
    $kask->bind_param("i",$_REQUEST["kustuta"]);
    $kask->execute();
}

if(isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"])){
    global $yhendus;
    $kask=$yhendus->prepare("INsert INTO tantsud (tantsupaar,ava_paev) Values(?,NOW())");
    $kask->bind_param("s",$_REQUEST["paarinimi"]);
    $kask->execute();
}



//kommentaaride
if(isset($_REQUEST["komment"])) {
    if(!empty($_REQUEST["uuskomment"])) {

        global $yhendus;
        $kask = $yhendus->prepare("UPDATE tantsud SET komentaarid=CONCAT(komentaarid,?) WHERE id=?");
        $komentaaridplus = $_REQUEST["uuskomment"]."\n";
        $kask->bind_param("si", $komentaaridplus, $_REQUEST["komment"]);
        $kask->execute();
    }
}
function isAdmin(){
    return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'];
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
    <script>
        // Add this JavaScript code to your existing script
        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        window.onclick = function (event) {
            var modal = document.getElementById("myModal");
            if (event.target == modal) {
                closeModal();
            }
        };
    </script>
</head>

<body>
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <?php include('login.php'); ?>
    </div>
</div>
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
        <a href="#" onclick="openModal()">Logi sisse</a>

        <?php
    }
    ?>
</header>

<nav>
    <li><a href="lala.php">Kasutaja Panel</a></li>
    <?php
    if (isAdmin()) {
    ?>
    <li><a href="adminleht.php">Admin Panel</a></li>
    <?php
    }
    ?>

</nav>


<h2>Punktide lisamine</h2>
<?php
if (isset($_SESSION["kasutaja"]))
{
?>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>Punktid</th>
        <th>Päev</th>
        <th>Komentaarid</th>
    </tr>
<?php
global $yhendus;
$kask=$yhendus->prepare("SELECT id, tantsupaar, punktid,ava_paev,komentaarid from tantsud WHERE avalik =1;");
$kask->bind_result($id,$tantsupaar,$punktid,$paev,$komentaarid);
$kask->execute();
while ($kask->fetch()){
    echo "<tr>";
    $tantsupaar=htmlspecialchars($tantsupaar);
    echo "<td>".$tantsupaar."</td>";
    echo "<td>".$punktid."</td>";
    echo "<td>".$paev."</td>";
    echo "<td>".nl2br(htmlspecialchars($komentaarid));
if (!isAdmin()) {
    echo "<td> <form action='?'>
       <input type='hidden' value='$id' name='komment'> 
       <label for='uuskomment'>Lisa uus komment:  </label>
        <input type='text' name='uuskomment' id='uuskomment'> <br>
        <input type='submit' value='Ok'>
    </form>";

    echo "<td><a href='?heatants=$id'>Lisa +1punkt</a></td>";
    echo "<td><a href='?halbtants=$id'>Lisa -1punkt</a></td>";
}
    echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
    echo "</tr>";
}


    if(!isAdmin()){
    ?>



    <form action="?">
        <label for="paarinimi">Lisa uus paar:  </label>
    <input type="text" name="paarinimi" id="paarinimi"> <br>
        <input type="submit" value="Lisa paar">
    </form>
    <?php }?>
</table>
</body>
</html>
<?php
}


?>
