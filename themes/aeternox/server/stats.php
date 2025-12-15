<?php if (!defined('FLUX_ROOT')) exit; ?>

<h2>Who's Online?</h2>
<h3>Showing players on-line <?php echo htmlspecialchars($server->serverName) ?>.</h3>
<?php if ($auth->allowedToSearchWhosOnline): ?>
	<p class="toggler"><a href="javascript:toggleSearchForm()">Search...</a></p>
	<form action="<?php echo $this->url ?>" method="get" class="search-form">
		<?php echo $this->moduleActionFormInputs($params->get('module'), $params->get('action')) ?>
		<p>
			<label for="char_name">Character Name:</label>
			<input type="text" name="char_name" id="char_name" value="<?php echo htmlspecialchars($params->get('char_name') ?: '') ?>" />
			...
			<label for="char_class">Job Class:</label>
			<input type="text" name="char_class" id="char_class" value="<?php echo htmlspecialchars($params->get('char_class') ?: '') ?>" />
			...
			<label for="guild_name">Guild:</label>
			<input type="text" name="guild_name" id="guild_name" value="<?php echo htmlspecialchars($params->get('guild_name') ?: '') ?>" />

			<input type="submit" value="Search" />
			<input type="button" value="Reset" onclick="reload()" />
		</p>
	</form>
<?php endif ?>
<?php if ($chars): ?>
<?php echo $paginator->infoText() ?>

<?php if ($hiddenCount): ?>
<p><?php echo number_format($hiddenCount) ?> <?php echo ((int)$hiddenCount === 1) ? 'person has' : 'people have' ?> chosen to hide themselves from this list.</p>
<?php endif ?>

<table class="horizontal-table">
	<tr>
		<th><?php echo $paginator->sortableColumn('char_name', 'Character Name') ?></th>
		<th>Job Class</th>
		<th><?php echo $paginator->sortableColumn('base_level', 'Base Level') ?></th>
		<th><?php echo $paginator->sortableColumn('job_level', 'Job Level') ?></th>
		<th colspan="2"><?php echo $paginator->sortableColumn('guild_name', 'Guild') ?></th>
		<?php if ($auth->allowedToViewOnlinePosition): ?>
			<th><?php echo $paginator->sortableColumn('last_map', 'Map') ?></th>
		<?php else: ?>
			<th>Map</th>
		<?php endif ?>
	</tr>
	<?php foreach ($chars as $char): ?>
	<tr>
		<td align="right">
			<?php if ($auth->actionAllowed('character', 'view') && $auth->allowedToViewCharacter): ?>
				<?php echo $this->linkToCharacter($char->char_id, $char->char_name) ?>
			<?php else: ?>
				<?php echo htmlspecialchars($char->char_name) ?>
			<?php endif ?>
		</td>
		<td><?php echo $this->jobClassText($char->char_class) ?></td>
		<td><?php echo number_format($char->base_level) ?></td>
		<td><?php echo number_format($char->job_level) ?></td>
		<?php if ($char->guild_name): ?>
			<?php if ($char->emblem): ?>
			<td width="20"><img src="<?php echo $this->emblem($char->guild_id) ?>" /></td>
			<?php endif ?>
			<td<?php if (!$char->emblem) echo ' colspan="2"' ?>>
				<?php if ($auth->actionAllowed('guild', 'view') && $auth->allowedToViewGuild): ?>
					<?php echo $this->linkToGuild($char->guild_id, $char->guild_name) ?>
				<?php else: ?>
					<?php echo htmlspecialchars($char->guild_name) ?>
				<?php endif ?>
			</td>
		<?php else: ?>
			<td colspan="2"><span class="not-applicable">None</span></td>
		<?php endif ?>
		
		<td>
		<?php if (!$char->hidemap && $auth->allowedToViewOnlinePosition): ?>
			<?php echo htmlspecialchars(basename($char->last_map, '.gat')) ?>
		<?php else: ?>
			<span class="not-applicable">Hidden</span>
		<?php endif ?>
		</td>
	</tr>
	<?php endforeach ?>
</table>
<?php echo $paginator->getHTML() ?>
<?php else: ?>
<p>No characters found on <?php echo htmlspecialchars($server->serverName) ?>. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>

<h2>Map Statistics</h2>
<?php if ($maps): ?>
<?php $playerTotal = 0; foreach ($maps as $map) $playerTotal += $map->player_count ?>
<p>This page shows how many online players are located a specific map, for all maps that have <em>any</em> online players at all.</p>
<p><strong><?php echo number_format($playerTotal) ?></strong> online player(s) were found
distributed across <strong><?php echo number_format(count($maps)) ?></strong> map(s).</p>
<div class="generic-form-div">
	<table class="generic-form-table">
		<?php foreach ($maps as $map): ?>
		<tr>
			<td align="right"><p class="important"><strong><?php echo htmlspecialchars(basename($map->map_name, '.gat')) ?></strong></p></td>
			<td><p><strong><em><?php echo number_format($map->player_count) ?></em></strong> player(s)</p></td>
		</tr>
		<?php endforeach ?>
	</table>
</div>
<?php else: ?>
<p>No players found on any maps. <a href="javascript:history.go(-1)">Go back</a>.</p>
<?php endif ?>

<h2>Server Statistics</h2>
<p><?php echo htmlspecialchars(Flux::message('ServerInfoText')) ?></p>

<h3><?php echo htmlspecialchars(sprintf(Flux::message('ServerInfoSubHeading'), $server->serverName)) ?></h3>
<div class="generic-form-div">
	<table class="generic-form-table">
		<tr>
			<th><label><?php echo htmlspecialchars(Flux::message('ServerInfoAccountLabel')) ?></label></th>
			<td><p><?php echo number_format($info['accounts']) ?></p></td>
		</tr>
		<tr>
			<th><label><?php echo htmlspecialchars(Flux::message('ServerInfoCharLabel')) ?></label></th>
			<td><p><?php echo number_format($info['characters']) ?></p></td>
		</tr>
		<tr>
			<th><label><?php echo htmlspecialchars(Flux::message('ServerInfoGuildLabel')) ?></label></th>
			<td><p><?php echo number_format($info['guilds']) ?></p></td>
		</tr>
		<tr>
			<th><label><?php echo htmlspecialchars(Flux::message('ServerInfoPartyLabel')) ?></label></th>
			<td><p><?php echo number_format($info['parties']) ?></p></td>
		</tr>
		<tr>
			<th><label><?php echo htmlspecialchars(Flux::message('ServerInfoZenyLabel')) ?></label></th>
			<td><p><?php echo number_format($info['zeny']) ?></p></td>
		</tr>
	</table>
</div>

<h3><?php echo htmlspecialchars(sprintf(Flux::message('ServerInfoSubHeading2'), $server->serverName)) ?></h3>
<div class="generic-form-div">
	<table class="generic-form-table job-classes">
		<tr>
		<?php $i = 1; $x = 5 ?>
		<?php foreach ($info['classes'] as $class => $total): ?>
			<th><label><?php echo htmlspecialchars($class) ?></label></th>
			<td><p class="important"><?php echo number_format($total) ?></p></td>
		<?php if ($i++ % $x === 0): ?>
		</tr>
		<tr>
		<?php endif ?>
		<?php endforeach ?>
		<?php --$i ?>
		<?php while (($i++) % $x): ?>
			<th>&nbsp;</th>
			<td>&nbsp;</td>
		<?php endwhile ?>
		</tr>
	</table>
</div>
