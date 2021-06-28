<?php

if (!isset($_POST['dependencies'])) {
    $_POST['dependencies'] = array();
}

if (!isset($_POST['depend'])) {
    $_POST['depend'] = array();
}

require_once("dependency.php");
require_once("funcs.php");

function isChecked(string $toCheck, array $parameters): bool
{
    return in_array($toCheck, $parameters);
}

function getApiList(): string
{
    $output = "";
    $apis = array("spigot-api", "spigot");
    foreach ($apis as $api) {
        $selected = "";
        if (get_post("api") == $api) {
            $selected = "selected=\"selected\"";
        }
        $output .= "<option value=\"" . $api . "\" $selected>$api</option>";
    }
    return $output;
}

function get_relocations($dependencies): string
{
    $relocations = "";
    foreach ($dependencies as $dependency) {
        $id = $dependency->id;
        if (($handle = fopen("relocations.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                if ($data[0] !== $id) continue;
                $relocations .= "<relocation>";
                $relocations .= "<pattern>" . $data[1] . "</pattern>";
                $relocations .= "<shadedPattern>%groupId%.%artifactIdLowerCase%.libs.$data[1]</shadedPattern>";
                $relocations .= "</relocation>";
            }
        }
    }
    return $relocations;
}

function replace_placeholders(string $xml): string
{
    $xml = str_replace("%groupId%", get_post("groupId"), $xml);
    $xml = str_replace("%artifactId%", get_post("artifactId"), $xml);
    $xml = str_replace("%artifactIdLowerCase%", strtolower(get_post("artifactId")), $xml);
    return str_replace("%description%", get_post("description"), $xml);
}

function load_dependencies(array $dependencies, bool $getall, $enabledDependencies = null): array
{
    $row = 0;
    if (($handle = fopen("dependencies.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            $row++;
            if ($row === 1) continue;
            $dependency = Dependency::fromCSV($data);

            if ($getall === true
                || ($enabledDependencies !== null
                    && is_array($enabledDependencies)
                    && in_array($dependency->id, $enabledDependencies))) {
                array_push($dependencies, $dependency);
            }
            //echo "Added Dependency: $dependency";
        }
        fclose($handle);
    }
    usort($dependencies, function ($a, $b) {
        return strcmp(strtolower($a->name), strtolower($b->name));
    });
    return $dependencies;
}

function getDepends($dependencies, $harddepends, bool $hard)
{
    $depends = array();

    //echo "Hard Depend: " . $harddepends;

    foreach ($dependencies as $dependency) {
        if($dependency->depend == "") continue;
            if (($hard && in_array($dependency->id, $harddepends))
                || (!$hard && !in_array($dependency->id, $harddepends))) {
                if(!in_array($dependency->id, $depends)) {
                    array_push($depends, $dependency->depend);
                }
            }
    }

    $output = "";

    foreach($depends as $dependency) {
        $output .= "  - " . $dependency . "\n";
    }

    return $output;
}

function get_post($name, bool $isArray = false)
{
    if (isset($_GET[$name])) return $_GET[$name];
    if ($isArray) return array();
    return "";
}

function load_repositories($dependencies): array
{
    $repos = array();
    foreach ($dependencies as $dependency) {
        if ($dependency->repositoryName !== "") {
            if (!in_array($dependency->getRepository(), $repos)) {
                array_push($repos, $dependency->getRepository());
            }
        }
    }
    return $repos;
}

function get_mc_versions()
{
    $versions = array();
    if (($handle = fopen("mcversions.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            array_push($versions, $data[0]);
        }
    }
    return $versions;
}

function array_to_string($array)
{
    $output = "";
    foreach ($array as $entry) {
        $output .= $entry;
    }
    return $output;
}

function get_bukkit_version($version)
{
    if (($handle = fopen("mcversions.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
            if ($data[0] !== $version) {
                continue;
            }
            if (count($data) === 1) {
                return $version;
            }
            return $data[1];
        }
        fclose($handle);
    }
}