<?php
include "simulator/Simulator.php";

$errorMessage = "";
if (isset($_POST["up"]) && $_POST["up"] == "y") {
    $tester = TesterInfo::FromDB($_POST["tester_id"]);
    $sim = new Simulator($tester);

    if(!file_exists("/tmp/Simulator/"))
        mkdir("/tmp/Simulator/");
    $target_dir = "/tmp/Simulator/TestAt" . $sim->Moment() . "/";
    mkdir($target_dir);
    $target_file = $target_dir . $tester->inputFile;
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);


    if(basename($_FILES["file"]["name"]) != $tester->inputFile){
        $errorMessage = "Dosya adı bu tester için '".$tester->inputFile."'' şeklinde olmalı, bi düzelt öyle deneyelim.";
    }
    /*if (!preg_match("/^\\w*\\.\\w*$/", $_FILES["file"]["name"]))
    {
        echo "dosya ismi 'isim.uzanti' seklinde olabilir";
    }*/

    if ($errorMessage == "" && move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $simID = $sim->Simulate($tester->inputFile, md5_file($target_file));
        header("Location: Result.php?id={$simID}");
        return;
    } else {
        $errorMessage =  "dosyani yukleyemedim, bunun pek cok nedeni olabilir";
    }
}

$id = $_GET["id"];
$tester = TesterInfo::FromDB($id);

if ($tester->id == -1)
    $errorMessage = "bence boyle bir tester yok";
?>

<?php include("structure/header.php") ?>
    <div class="container">
        <div class="row">
            <?php
            if($errorMessage != ""){
                /* keko kod vol1.0 */
                ?>
                <div class="alert alert-danger"><?=$errorMessage ?></div>
            <?php
            }
            ?>
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

                            <div class="alert alert-success">
                                dosyalariniz testerin isi bitince serverdan siliniyor
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
                                <li><a href="simulator/Files/Testers/Tester<?=$tester->id ?>/<?=$file ?>"><?=$file ?></a></li>
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
