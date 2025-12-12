<?php
if (!defined('FLUX_ROOT')) exit;

$title = "Server Statistics";
$info  = array(
		'accounts'   => 0,
		'characters' => 0,
		'guilds'     => 0,
		'parties'    => 0,
		'zeny'       => 0,
		'classes'    => array()
);

// Accounts.
$sql = "SELECT COUNT(account_id) AS total FROM {$server->loginDatabase}.login WHERE sex != 'S' ";
if (Flux::config('HideTempBannedStats')) {
	$sql .= "AND unban_time <= UNIX_TIMESTAMP() ";
}
if (Flux::config('HidePermBannedStats')) {
	if (Flux::config('HideTempBannedStats')) {
		$sql .= "AND state != 5 ";
	} else {
		$sql .= "AND state != 5 ";
	}
}
$sth = $server->connection->getStatement($sql);
$sth->execute();
$info['accounts'] += $sth->fetch()->total;

// Characters.
$sql = "SELECT COUNT(`char`.char_id) AS total FROM {$server->charMapDatabase}.`char` ";
if (Flux::config('HideTempBannedStats')) {
	$sql .= "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = `char`.account_id ";
	$sql .= "WHERE login.unban_time <= UNIX_TIMESTAMP()";
}
if (Flux::config('HidePermBannedStats')) {
	if (Flux::config('HideTempBannedStats')) {
		$sql .= " AND login.state != 5";
	} else {
		$sql .= "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = `char`.account_id ";
		$sql .= "WHERE login.state != 5";
	}
}
$sth = $server->connection->getStatement($sql);
$sth->execute();
$info['characters'] += $sth->fetch()->total;

// Guilds.
$sql = "SELECT COUNT(guild_id) AS total FROM {$server->charMapDatabase}.guild";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$info['guilds'] += $sth->fetch()->total;

// Parties.
$sql = "SELECT COUNT(party_id) AS total FROM {$server->charMapDatabase}.party";
$sth = $server->connection->getStatement($sql);
$sth->execute();
$info['parties'] += $sth->fetch()->total;

// Zeny.
$bind = array();
$sql  = "SELECT SUM(`char`.zeny) AS total FROM {$server->charMapDatabase}.`char` ";
if ($hideGroupLevel=Flux::config('InfoHideZenyGroupLevel')) {
	$sql .= "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = `char`.account_id ";
	
	$groups = AccountLevel::getGroupID($hideGroupLevel, '<');
	if(!empty($groups)) {
		$ids   = implode(', ', array_fill(0, count($groups), '?'));
		$sql  .= "WHERE login.group_id IN ($ids) ";
		$bind  = array_merge($bind, $groups);
	}
}
if (Flux::config('HideTempBannedStats')) {
	if ($hideGroupLevel) {
		$sql .= " AND unban_time <= UNIX_TIMESTAMP()";
	} else {
		$sql .= "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = `char`.account_id ";
		$sql .= "WHERE unban_time <= UNIX_TIMESTAMP()";
	}
}
if (Flux::config('HidePermBannedStats')) {
	if ($hideGroupLevel || Flux::config('HideTempBannedStats')) {
		$sql .= " AND state != 5";
	} else {
		$sql .= "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = `char`.account_id ";
		$sql .= "WHERE state != 5";
	}
}

$sth = $server->connection->getStatement($sql);
$sth->execute($hideGroupLevel ? $bind : array());
$info['zeny'] += $sth->fetch()->total;

// Job classes.
$sql = "SELECT `char`.class, COUNT(`char`.class) AS total FROM {$server->charMapDatabase}.`char` ";
if (Flux::config('HideGMStats')) {
	$sql .= "LEFT JOIN {$server->loginDatabase}.login AS login ON login.account_id = `char`.account_id WHERE login.group_id < 99 ";
}
if (Flux::config('HideTempBannedStats')) {
	if (Flux::config('HideGMStats')) {
		$sql .= "AND login.unban_time <= UNIX_TIMESTAMP() ";
	} else {
		$sql .= "LEFT JOIN {$server->loginDatabase}.login AS login ON login.account_id = `char`.account_id ";
		$sql .= "WHERE login.unban_time <= UNIX_TIMESTAMP() ";
	}
}
if (Flux::config('HidePermBannedStats')) {
	if (Flux::config('HideGMStats') || Flux::config('HideTempBannedStats')) {
		$sql .= "AND login.state != 5 ";
	} else {
		$sql .= "LEFT JOIN {$server->loginDatabase}.login AS login ON login.account_id = `char`.account_id ";
		$sql .= "WHERE login.state != 5 ";
	}
}
$sql .= "GROUP BY `char`.class";
$sth = $server->connection->getStatement($sql);
$sth->execute();

$classes = $sth->fetchAll();
if ($classes) {
	foreach ($classes as $class) {
		$classnum = (int)$class->class;
		$info['classes'][Flux::config("JobClasses.$classnum")] = $class->total;
	}
}

if (Flux::config('SortJobsByAmount')) {
	arsort($info['classes']);
}



// MAP STATISTICS

$bind = array();
$sql  = "SELECT last_map AS map_name, COUNT(last_map) AS player_count FROM {$server->charMapDatabase}.`char` ";

if (($hideGroupLevel=(int)Flux::config('HideFromMapStats')) > 0 && !$auth->allowedToSeeHiddenMapStats) {
	$sql .= "LEFT JOIN {$server->loginDatabase}.login ON `char`.account_id = login.account_id ";
}

$sql .= "WHERE online > 0 ";

if ($hideGroupLevel > 0 && !$auth->allowedToSeeHiddenMapStats) {
	$groups = AccountLevel::getGroupID($hideGroupLevel, '<');
	
	if(!empty($groups)) {
		$ids   = implode(', ', array_fill(0, count($groups), '?'));
		$sql  .= "AND login.group_id IN ($ids) ";
		$bind  = array_merge($bind, $groups);
	}
}

$sql .= " GROUP BY map_name, online HAVING player_count > 0 ORDER BY map_name ASC";
$sth  = $server->connection->getStatement($sql);

$sth->execute($bind);
$maps = $sth->fetchAll();


// WHO'S ONLINE

$charPrefsTable = Flux::config('FluxTables.CharacterPrefsTable');


$sqlpartial  = "LEFT JOIN {$server->loginDatabase}.login ON login.account_id = ch.account_id ";
$sqlpartial .= "LEFT JOIN {$server->charMapDatabase}.guild ON guild.guild_id = ch.guild_id ";

if (!$auth->allowedToIgnoreHiddenPref) {
	$sqlpartial .= "LEFT JOIN {$server->charMapDatabase}.$charPrefsTable AS pref1 ON ";
	$sqlpartial .= "(pref1.account_id = ch.account_id AND pref1.char_id = ch.char_id AND pref1.name = 'HideFromWhosOnline') ";
}

$sqlpartial .= "LEFT JOIN {$server->charMapDatabase}.$charPrefsTable AS pref2 ON ";
$sqlpartial .= "(pref2.account_id = ch.account_id AND pref2.char_id = ch.char_id AND pref2.name = 'HideMapFromWhosOnline') ";
$sqlpartial .= "WHERE ch.online > 0 ";

if (!$auth->allowedToIgnoreHiddenPref) {
	$sqlpartial .= "AND (pref1.value IS NULL) ";
}

$bind = array();

if ($auth->allowedToSearchWhosOnline) {
	$charName  = $params->get('char_name');
	$charClass = $params->get('char_class');
	$guildName = $params->get('guild_name');

	if ($charName) {
		$sqlpartial .= "AND (ch.name LIKE ? OR ch.name = ?) ";
		$bind[]      = "%$charName%";
		$bind[]      = $charName;
	}

	if ($guildName) {
		$sqlpartial .= "AND (guild.name LIKE ? OR guild.name = ?) ";
		$bind[]      = "%$guildName%";
		$bind[]      = $guildName;
	}

	if ($charClass) {
		$className = preg_quote($charClass, '/');
		$classIDs  = preg_grep("/.*?$className.*?/i", Flux::config('JobClasses')->toArray());

		if (count($classIDs)) {
			$classIDs    = array_keys($classIDs);
			$sqlpartial .= "AND (";
			$partial     = '';

			foreach ($classIDs as $id) {
				$partial .= "ch.class = ? OR ";
				$bind[]   = $id;
			}

			$partial     = preg_replace('/\s*OR\s*$/', '', $partial);
			$sqlpartial .= "$partial) ";
		}
		else {
			$sqlpartial .= 'AND ch.class IS NULL ';
		}
	}
}

// Hide groups greater than or equal to
if (($hideGroupLevel=Flux::config('HideFromWhosOnline')) && !$auth->allowedToIgnoreHiddenPref2) {
	$groups = AccountLevel::getGroupID($hideGroupLevel, '<');

	if(!empty($groups)) {
		$ids = implode(', ', array_fill(0, count($groups), '?'));
		$sqlpartial .= "AND login.group_id IN ($ids) ";
		$bind = array_merge($bind, $groups);
	}
}

$sql  = "SELECT COUNT(ch.char_id) AS total FROM {$server->charMapDatabase}.`char` AS ch $sqlpartial";
$sth  = $server->connection->getStatement($sql);

$sth->execute($bind);

$sortable = array('char_name' => 'asc', 'base_level', 'job_level', 'guild_name');
if ($auth->allowedToViewOnlinePosition) {
	$sortable[] = 'last_map';
}

$paginator = $this->getPaginator($sth->fetch()->total);
$paginator->setSortableColumns($sortable);

$sql  = "SELECT COUNT(ch.char_id) - {$paginator->total} AS total FROM {$server->charMapDatabase}.`char` AS ch ";
$sql .= "WHERE ch.online > 0";
$sth  = $server->connection->getStatement($sql);

$sth->execute();

// Number of hidden players (not including the ones hidden by the 'HideFromWhosOnline' app config).
$hiddenCount = (int)$sth->fetch()->total;

$col  = "ch.char_id, ch.name AS char_name, ch.class AS char_class, ch.base_level, ch.job_level, ";
$col .= "guild.name AS guild_name, guild.guild_id, ch.last_map, pref2.value AS hidemap, guild.emblem_id as emblem ";

$sql  = $paginator->getSQL("SELECT $col FROM {$server->charMapDatabase}.`char` AS ch $sqlpartial");
$sth  = $server->connection->getStatement($sql);

$sth->execute($bind);

$chars = $sth->fetchAll();

?>
