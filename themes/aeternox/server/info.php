<?php if (!defined('FLUX_ROOT')) exit; ?>
<h2><?php echo htmlspecialchars(Flux::message('ServerInfoHeading')) ?></h2>
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
