<?php
require_once("repository.php");

class Dependency
{
    public $id;
    public $name;
    public $groupId;
    public $artifact;
    public $version;
    public $repositoryUrl;
    public $repositoryName;
    public $scope;
    public $depend;

    public function __construct(string $id, string $name, string $groupId, string $artifact, string $version, string $repositoryName, string $repositoryUrl, string $scope, string $depend)
    {
        $this->id = $id;
        $this->name = $name;
        $this->groupId = $groupId;
        $this->artifact = $artifact;
        $this->version = $version;
        $this->repositoryName = $repositoryName;
        $this->repositoryUrl = $repositoryUrl;
        $this->scope = $scope;
        $this->depend = $depend;
    }

    public static function fromCSV($data)
    {
        return new Dependency($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8]);
    }

    public function __toString()
    {
        $text = "";//"<!-- " . $this->name . " -->\n";
        $text .= "<dependency>\n<groupId>$this->groupId</groupId>\n<artifactId>$this->artifact</artifactId>\n<version>$this->version</version>\n<scope>$this->scope</scope>\n</dependency>\n";
        return $text;
    }

    public function getCheckBox()
    {
        //print_r(get_post("depend",true));
        $checked = "";
        if (isChecked($this->id, get_post('dependencies', true))
            /*|| isChecked($this->depend, get_post('depend', true))*/) {
            $checked = "checked=\"checked\"";
        }
        $checked_depend = "";
        if (isChecked($this->depend, get_post('depend', true))) {
            $checked_depend = "checked=\"checked\"";
            //echo "$this->depend is checked";
        }
        ?>
        <input type="checkbox" id="<?= $this->id; ?>" value="<?= $this->id; ?>" name="dependencies[]" <?= $checked; ?>/>
        <label for="<?= $this->id; ?>" style="font-weight: bold"><?= $this->name; ?></label>
        <?php if ($this->depend !== "") { ?>
        <input type="checkbox" id="<?= $this->id; ?>_depend" value="<?= $this->depend; ?>"
               name="depend[]" <?= $checked_depend; ?>/>
        <label for="<?= $this->id; ?>_depend">Depend</label>
    <?php }
    }

    public function getRepository(): Repository
    {
        return new Repository($this->repositoryName, $this->repositoryUrl);
    }

}