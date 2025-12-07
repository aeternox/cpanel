<?php
if (!defined('FLUX_ROOT')) exit;

$title     = Flux::message('WoeTitle');
$dayNames  = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
$woeTimes  = array();

foreach ($session->loginAthenaGroup->athenaServers as $athenaServer) {
	$times = $athenaServer->woeDayTimes;
	if ($times) {
		$woeTimes[$athenaServer->serverName] = array();
		foreach ($times as $time) {
			$woeTimes[$athenaServer->serverName][] = array(
				'startingDay'  => $dayNames[$time['startingDay']],
				'startingHour' => $time['startingTime'],
				'endingDay'    => $dayNames[$time['endingDay']],
				'endingHour'   => $time['endingTime']
			);
		}
	}
}

$castleNames = Flux::config('CastleNames')->toArray();
$ids = implode(',', array_fill(0, count($castleNames), '?'));

$sql  = "SELECT castles.castle_id, castles.guild_id, castles.economy, guild.name AS guild_name, guild.emblem_id as emblem ";
$sql .= "FROM {$server->charMapDatabase}.guild_castle AS castles ";
$sql .= "LEFT JOIN guild ON guild.guild_id = castles.guild_id ";
$sql .= "WHERE castles.castle_id IN ($ids)";
$sql .= "ORDER BY castles.castle_id ASC";
$sth  = $server->connection->getStatement($sql);
$sth->execute(array_keys($castleNames));

$castles = $sth->fetchAll();
?>
