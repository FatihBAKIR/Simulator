<?php

include "Database.php";
class TesterInfo
{
    public $files;
    public $cmd;

    public $id;
    public $author;
    public $name;
    public $inputFile;

    public static function FromDB($id)
    {
        $meta = Database::TesterByID($id);
        $files = Database::TesterFiles($id);

        $t = new TesterInfo();
        $t->id = $meta["id"];
        if ($meta == null)
            $t->id = -1;
        $t->author = $meta["author"];
        $t->name = $meta["name"];
        $t->cmd = $meta["command"];
        $t->inputFile = $meta["file_type"];

        $t->files = [];
        foreach ($files as $file) {
            $t->files[] = $file["file"];
        }

        return $t;
    }
}

class Command
{
    private $cmd = "", $cCount = 0;

    public function Append($str)
    {
        if ($this->cCount > 0)
            $this->cmd .= " ; ";

        $this->cmd .= "{$str}";

        $this->cCount++;
    }

    public function Command()
    {
        return str_replace("; ", ";\n", $this->cmd);
    }

    public function Execute()
    {
        return shell_exec($this->cmd);
    }
}

class Simulator
{
    public $id;

    private $tester;

    private $input;
    private $md5;

    private $moment;

    public function __construct($tester)
    {
        $this->tester = $tester;
        $this->moment = time();
    }

    public function SetFile($file)
    {
        $this->input = $file;
    }

    public function Moment()
    {
        return $this->moment;
    }

    public function Simulate($name, $md5)
    {
        $dName = "TestAt" . $this->moment;

        $FilesPath = getcwd()."/simulator/Files";
        $TestersPath = $FilesPath."/Testers";
        $ResultsPath = $FilesPath."/Results";
        $UserPath = "/tmp/Simulator/".$dName;
        $TesterPath = $TestersPath."/Tester".$this->tester->id;

        $this->SetFile($UserPath."/".$name);

        $command = new Command();
        $command->Append("cd /tmp");
        $command->Append("mkdir {$dName}");
        $command->Append("cd {$dName}");

        foreach ($this->tester->files as $file)
            $command->Append("cp {$TesterPath}/{$file} {$file}");

        $command->Append("cp {$this->input} .");
        $command->Append("{$this->tester->cmd} > out.txt 2>&1");
        $command->Append("mkdir {$ResultsPath}/{$dName}");
        $command->Append("cp out.txt {$ResultsPath}/{$dName}/");
        $command->Append("rm -rf $UserPath");
        $command->Append("cd ..");
        $command->Append("rm -rf {$dName}");

        $out = $command->Execute();

        $this->md5 = $md5;

        return $this->Save($name, $command->Command()."\n\nOutput:\n".$out);
    }

    public function Save($name, $cmd)
    {
        return Database::MakeTestInstance($this->tester->id, $name, $this->md5, $this->moment, $cmd);
    }
}
