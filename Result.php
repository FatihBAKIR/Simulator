<?php
include "simulator/Simulator.php";
$id = $_GET["id"];
$sim = Database::GetTestInstanceByID($id);
$tester = Database::TesterByID($sim["tester_id"]);
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
                    <strong>Ne bu:</strong> <?=$tester["name"]?>
                    <br />
                    <strong>Kim yapmis:</strong> <?=$tester["author"]?>
                    <br />
                    <strong>Aynisindan:</strong> <a href="Simulate.php?id=<?=$tester["id"]?>">aliyorum bi dal</a>
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Simulasyon</h3>
                </div>
                <div class="panel-body">
                    <strong>Dosyan:</strong> <?=$sim["file"] ?>
                    <br />
                    <strong>MD5:</strong> <?=$sim["md5"] ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Tester Output</h3>
                </div>
                <pre><?=file_get_contents("simulator/Files/Results/TestAt{$sim["moment"]}/out.txt") ?></pre>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Neler ettim?</h3>
                </div>
                <pre><?=$sim["log"] ?></pre>
            </div>
        </div>
    </div>
</div>
<?php include("structure/footer.php") ?>
