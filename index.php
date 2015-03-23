<?php include("structure/header.php") ?>
<?php include("simulator/Database.php") ?>
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Bilgiler</h3>
                </div>
                <div class="panel-body">
                    Bu sitede testerlar falan var (henuz cok yok)
                    <br />
                    <br />
                    Sag taraftan en son yuklenen 10 tester ve yapilan son 10 testin sonucuna ulasabilirsiniz
                    <br />
                    <br />
                    Testler anonim olarak yapiliyor (ip yada kim oldugunuz zaten tutulmuyor ve dosyalariniz falan test
                    bitince siliniyor), korkmaniza gerek yok, neler yapiliyor ediliyor hepsi zaten sonuc sayfasinin
                    altinda yaziyor
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <h3>Son Testerlar:</h3>
            <ul>
            <?php
                foreach (Database::AllTesters() as $tester)
                {
                    ?>
                    <li><a href="Simulate.php?id=<?=$tester["id"] ?>"><?=$tester["name"] ?></a> (<?=$tester["author"] ?> yapmis)</li>
                    <?php
                }
            ?>
            </ul>
            <hr />
            <h3>Son Testler:</h3>
            <ul>
                <?php
                foreach (Database::AllTests() as $test)
                {
                    ?>
                    <li><a href="Result.php?id=<?=$test["id"] ?>">Test #<?=$test["id"] ?></a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<?php include("structure/footer.php") ?>