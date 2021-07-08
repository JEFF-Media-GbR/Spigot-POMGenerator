<?php
require_once("funcs.php");

$dependencies = array();
$mcversion = get_bukkit_version($_GET['mcversion']);

$spigotDependency = new Dependency("spigot","Spigot","org.spigotmc",$_GET['api'],$mcversion . "-R0.1-SNAPSHOT","spigot-snapshots","https://hub.spigotmc.org/nexus/content/repositories/snapshots/","provided","");
array_push($dependencies,$spigotDependency);
$dependencies = load_dependencies($dependencies,false,get_post("dependencies"));

$repositories = load_repositories($dependencies);

$xml = file_get_contents("pom.xml");
$xml = str_replace("%dependencies%",array_to_string($dependencies),$xml);
$xml = str_replace("%repositories%",array_to_string($repositories),$xml);
$xml = str_replace("%relocations%",get_relocations($dependencies),$xml);
$xml = str_replace("%ftp%",get_post('ftp',false) == "" ? "" : "<distributionManagement><repository><id>jeff-ftp</id><url>ftps://ftp.jeff-media.de/maven2</url></repository></distributionManagement>", $xml);
$xml = replace_placeholders($xml);
$dom = new DOMDocument('1.0');
$dom->formatOutput = true;
$dom->preserveWhiteSpace = false;
$dom->loadXML($xml);
$output = $dom->saveXML();
$output = str_replace("<","&lt;",$output);

$yml = file_get_contents("plugin.yml");
$yml = str_replace("%depend%",getDepends($dependencies,get_post("depend",true),true),$yml);
$yml = str_replace("%softdepend%",getDepends($dependencies,get_post("depend",true),false),$yml);
$yml = replace_placeholders($yml);


?><h2>pom.xml</h2>
<pre><?=$output;?></pre>
<h2>plugin.yml</h2>
<pre><?=$yml;?></pre>


