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
    </table>
    <h2>Dependencies</h2>
    <table>
        <?php
        foreach ($dependencies as $dependency) {
            echo "<tr><td>";
            $dependency->getCheckBox();
            echo "</td></tr>";
        }
        ?>
    </table>
        <input type="submit" value="Generate" />
    <input type="button" onclick="location.href='index.php';" value="Reset" />
</form>