<?php
include "simulator/Simulator.php";

if (isset($_POST["up"]) && $_POST["up"] == "y") {
    $tester = TesterInfo::FromDB($_POST["tester_id"]);

    $sim = new Simulator($tester);

    mkdir("/tmp/Simulator/");
    $target_dir = "/tmp/Simulator/TestAt" . $sim->Moment() . "/";
    mkdir($target_dir);
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $simID = $sim->Simulate(basename($_FILES["file"]["name"]), md5_file($target_file));
        header("Location: Result.php?id={$simID}");
    } else {
        echo "dosyani yukleyemedim, bunun pek cok nedeni olabilir";
    }
    return;
}

$id = $_GET["id"];
$tester = TesterInfo::FromDB($id);
?>

<?php include("structure/header.php") ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Bilgiler</h3>
                    </div>
                    <div class="panel-body">
                        <strong>Ne bu:</strong> <?= $tester->name ?>
                        <br/>
                        <strong>Kim yapmis:</strong> <?= $tester->author ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">tester as a service</h3>
                    </div>
                    <div class="panel-body"><form action="#" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="up" value="y"/>
                            <input type="hidden" name="tester_id" value="<?= $_GET["id"] ?>"/>

                            <div class="form-group">
                                <label for="exampleInputFile">test et</label>
                                <input type="file" name="file"/>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">test edeyim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">dosyam kiymetli</h3>
                    </div>
                    <div class="panel-body">
                        <blockquote>oncelikle, odevini yemedik</blockquote>
                        <hr />
                        <strong>bu testerda asagidaki dosyalar bulunuyor: </strong>
                        <ul>
                            <?php
                            foreach ($tester->files as $file)
                            {
                                ?>
                                <li><a href="simulator/Files/Testers/<?=$file ?>"><?=$file ?></a></li>
                            <?php
                            }
                            ?>
                        </ul>
                        bunlari ayni klasore odevinle beraber koyman tavsiye edilir
                        <hr />
                        <strong>su komutla da calisiyor: </strong>
                        <pre><?=$tester->cmd ?></pre>
                        yukaridaki dosyalarin oldugu dizine gecip, bu komutu calistir
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include("structure/footer.php") ?>