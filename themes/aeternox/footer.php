<?php if (!defined('FLUX_ROOT')) exit; ?>
		</div>

		<div id="ax-footer">
			<?php if (count(Flux::$appConfig->get('ThemeName', false)) > 1): ?>
				<span>Theme:
					<select name="preferred_theme" onchange="updatePreferredTheme(this)">
						<?php foreach (Flux::$appConfig->get('ThemeName', false) as $themeName): ?>
						<option value="<?php echo htmlspecialchars($themeName) ?>"<?php if ($session->theme == $themeName) echo ' selected="selected"' ?>><?php echo htmlspecialchars($themeName) ?></option>
						<?php endforeach ?>
					</select>
				</span>
				
				<form action="<?php echo $this->urlWithQs ?>" method="post" name="preferred_theme_form" style="display: none">
					<input type="hidden" name="preferred_theme" value="" />
				</form>
			<?php endif ?>
		</div>
	</body>
</html>
