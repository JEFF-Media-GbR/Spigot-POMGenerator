
<?php

require_once("funcs.php");
require_once("dependency.php");

$selectedApi = "spigot-api";
if(isset($_GET['api'])) {
    $selectedApi = $_GET['api'];
}

$dependencies = array();
$dependencies = load_dependencies($dependencies, true);

?>
<h1>Spigot POM Generator</h1>
<h2>General information</h2>
<form action="#pom" method="get">
    <input type="hidden" name="action" value="result">
    <table>
        <tr>
            <td>Group ID</td>
            <td><input type="text" name="groupId" value="<?= get_post('groupId'); ?>"></td>
        </tr>
        <tr>
            <td>Plugin Name</td>
            <td><input type="text" name="artifactId" value="<?= get_post('artifactId'); ?>"></td>
        </tr>
        <tr>
            <td>Description</td>
            <td><input type="text" name="description" value="<?= get_post('description'); ?>"></td>
        </tr>
        <tr>
            <td>Website</td>
            <td><input type="text" name="wesite" value="<?= get_post("website"); ?>"></td>
        </tr>
        <tr>
            <td>API Version</td>
            <td><select name="mcversion">
            <?php

                $versions = get_mc_versions();

                foreach($versions as $v) {
                    ?>
                    <option value="<?=$v;?>"><?=$v;?></option>
            <?php
                }
            ?>
                </select></td>
        </tr>
        <tr>
            <td>API</td>
            <td>
                <select name="api">
                    <?=getApiList();?>
                </select>
            </td>
        </tr>
        <tr>
            <td>Deploy to JEFF Repo</td>
            <td><input type="checkbox" id="ftp" <?php if(get_post('ftp',false)=="on") {?>checked="checked" <?php } ?> name="ftp"></input>
</td></tr>
    </table>
    <h2>Dependencies</h2>
    <script>
        function showOrHide(name)
        {
            if (document.getElementById(name).checked)
            {
                document.getElementById(name + "_depend").style.visibility = 'visible';
            } else {
                document.getElementById(name + "_depend").style.visibility = 'hidden';
                document.getElementById(name + "_depend").checked = false;

            }
        }
    </script>
    <style>
        tr:nth-child(even) {background: #CCC}
        tr:nth-child(odd) {background: #FFF}
    </style>
    <table>
        <thead style="font-weight: bold"><td></td><td>Name</td><td>Depend</td><td>Notes</td></thead>
        <?php
        foreach ($dependencies as $dependency) {
            $dependency->getCheckBox();
        }
        ?>
    </table>
        <input type="submit" value="Generate" />
    <input type="button" onclick="location.href='index.php';" value="Reset" />
</form>
