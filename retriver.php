<?php
require_once("include/bittorrent.php");
require("douban/douban.class.php");
dbconn();
loggedinorreturn();
global $updateextinfo_class;
if (get_user_class() < $updateextinfo_class) {
permissiondenied();
}
$id = 0 + $_GET["id"];
$type = 0 + $_GET["type"];
$siteid = 0 + $_GET["siteid"]; // 1 for IMDb

if (!isset($id) || !$id || !is_numeric($id) || !isset($type) || !$type || !is_numeric($type) || !isset($siteid) || !$siteid || !is_numeric($siteid))
die();

$r = sql_query("SELECT * from torrents WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
if(mysql_num_rows($r) != 1)
die();

$row = mysql_fetch_assoc($r);

switch ($siteid)
{
	case 1 : 
	{
		$imdb_id = parse_imdb_id($row["url"]);
		if ($imdb_id)
		{
			$thenumbers = $imdb_id;
			
			$movie = new Douban($imdb_id, 'imdb');
			$movie->clear_cache();
			set_cachetimestamp($id,"cache_stamp");
			$Cache->delete_value('imdb_id_'.$thenumbers.'_movie_name');
			$Cache->delete_value('imdb_id_'.$thenumbers.'_large', true);
			$Cache->delete_value('imdb_id_'.$thenumbers.'_median', true);
			$Cache->delete_value('imdb_id_'.$thenumbers.'_minor', true);
			header("Location: details.php?id=".htmlspecialchars($id));
		}
		break;
	}
	default :
	{
		die("Error!");
		break;
	}
}