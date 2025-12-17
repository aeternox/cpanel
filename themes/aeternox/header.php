<?php if (!defined('FLUX_ROOT')) exit; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php if (isset($metaRefresh)): ?>
		<meta http-equiv="refresh" content="<?php echo $metaRefresh['seconds'] ?>; URL=<?php echo $metaRefresh['location'] ?>" />
		<?php endif ?>
		<title><?php echo Flux::config('SiteTitle'); if (isset($title)) echo ": $title" ?></title>
        <link rel="icon" type="image/x-icon" href="./favicon.ico" />
		<link rel="stylesheet" href="<?php echo $this->themePath('css/flux.css') ?>" type="text/css" media="screen" title="" charset="utf-8" />
		<link rel="stylesheet" href="<?php echo $this->themePath('css/aeternox.css') ?>" type="text/css" media="screen" title="" charset="utf-8" />
		<link href="<?php echo $this->themePath('css/flux/unitip.css') ?>" rel="stylesheet" type="text/css" media="screen" title="" charset="utf-8" />
		<?php if (Flux::config('EnableReCaptcha')): ?>
		<link href="<?php echo $this->themePath('css/flux/recaptcha.css') ?>" rel="stylesheet" type="text/css" media="screen" title="" charset="utf-8" />
		<?php endif ?>
		<!--[if IE]>
		<link rel="stylesheet" href="<?php echo $this->themePath('css/flux/ie.css') ?>" type="text/css" media="screen" title="" charset="utf-8" />
		<![endif]-->	
		<!--[if lt IE 9]>
		<script src="<?php echo $this->themePath('js/ie9.js') ?>" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo $this->themePath('js/flux.unitpngfix.js') ?>"></script>
		<![endif]-->
		<script type="text/javascript" src="<?php echo $this->themePath('js/jquery-1.8.3.min.js') ?>"></script>
		<script type="text/javascript" src="<?php echo $this->themePath('js/flux.datefields.js') ?>"></script>
		<script type="text/javascript" src="<?php echo $this->themePath('js/flux.unitip.js') ?>"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				var inputs = 'input[type=text],input[type=password],input[type=file]';
				$(inputs).focus(function(){
					$(this).css({
						'background-color': '#f9f5e7',
						'border-color': '#dcd7c7',
						'color': '#726c58'
					});
				});
				$(inputs).blur(function(){
					$(this).css({
						'backgroundColor': '#ffffff',
						'borderColor': '#dddddd',
						'color': '#444444'
					}, 500);
				});
				$('.menuitem a').hover(
					function(){
						$(this).fadeTo(200, 0.85);
						$(this).css('cursor', 'pointer');
					},
					function(){
						$(this).fadeTo(150, 1.00);
						$(this).css('cursor', 'normal');
					}
				);
				$('.money-input').keyup(function() {
					var creditValue = parseInt($(this).val() / <?php echo Flux::config('CreditExchangeRate') ?>, 10);
					if (isNaN(creditValue))
						$('.credit-input').val('?');
					else
						$('.credit-input').val(creditValue);
				}).keyup();
				$('.credit-input').keyup(function() {
					var moneyValue = parseFloat($(this).val() * <?php echo Flux::config('CreditExchangeRate') ?>);
					if (isNaN(moneyValue))
						$('.money-input').val('?');
					else
						$('.money-input').val(moneyValue.toFixed(2));
				}).keyup();
				
				// In: js/flux.datefields.js
				processDateFields();
			});
			
			function reload(){
				window.location.href = '<?php echo $this->url ?>';
			}
		</script>
		
		<script type="text/javascript">
			function updatePreferredServer(sel){
				var preferred = sel.options[sel.selectedIndex].value;
				document.preferred_server_form.preferred_server.value = preferred;
				document.preferred_server_form.submit();
			}
			
			function updatePreferredTheme(sel){
				var preferred = sel.options[sel.selectedIndex].value;
				document.preferred_theme_form.preferred_theme.value = preferred;
				document.preferred_theme_form.submit();
			}

            function updatePreferredLanguage(sel){
                var preferred = sel.options[sel.selectedIndex].value;
                setCookie('language', preferred);
                reload();
            }

			// Preload spinner image.
			var spinner = new Image();
			spinner.src = '<?php echo $this->themePath('img/spinner.gif') ?>';
			
			function refreshSecurityCode(imgSelector){
				$(imgSelector).attr('src', spinner.src);
				
				// Load image, spinner will be active until loading is complete.
				var clean = <?php echo Flux::config('UseCleanUrls') ? 'true' : 'false' ?>;
				var image = new Image();
				image.src = "<?php echo $this->url('captcha') ?>"+(clean ? '?nocache=' : '&nocache=')+Math.random();
				
				$(imgSelector).attr('src', image.src);
			}
			function toggleSearchForm()
			{
				//$('.search-form').toggle();
				$('.search-form').slideToggle('fast');
			}

            function setCookie(key, value) {
                var expires = new Date();
                expires.setTime(expires.getTime() + expires.getTime()); // never expires
                document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
            }
		</script>
		
		<?php if (Flux::config('EnableReCaptcha')): ?>
			<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php endif ?>
		
	</head>
	<body>
		<div id="header">
			<div id="ax-header-content">
				<div id="ax-header-logo">
					<a href="<?php echo $this->basePath ?>"><img src="<?php echo $this->themePath('img/logo.webp') ?>" /></a>
				</div>
				<div id="ax-header-info">
					<div>1x / 1x / 1x</div>
					<div>99/50 2-2</div>
					<div>Pre-renewal</div>
				</div>
				<div id="ax-links">
					<a id="ax-link" href="https://discord.aeternox.gg" target="_blank"><img class="ax-link-img" alt="Discord" title="Discord" width="25px" src="https://cdn-icons-png.flaticon.com/512/5968/5968968.png" /></a>
					<a id="ax-link" href="https://facebook.com/aeternox" target="_blank"><img class="ax-link-img" alt="Facebook" title="Facebook" width="25px" src="https://cdn-icons-png.flaticon.com/512/1384/1384005.png" /></a>
					<a id="ax-link" href="https://github.aeternox.gg" target="_blank"><img class="ax-link-img" alt="Github" title="Github" width="25px" src="https://cdn-icons-png.flaticon.com/512/1051/1051326.png" /></a>
				</div>
				<div id="ax-header-review">
					<a href="https://ratemyserver.net/index.php?page=detailedlistserver&serid=23168&url_sname=Aeternox" target="_blank">Review us on RMS!</a>
				</div>
				<div id="ax-server-status">
					<?php
						function statusMessage($isUp) {
							return $isUp ? "Online" : "Offline";
						}

						$serverGroup = Flux::$loginAthenaGroupRegistry["Aeternox"];
						$loginServerUp =$serverGroup->loginServer->isUp();
						$athenaServer = $serverGroup->athenaServers[0];
						$charServerUp = $athenaServer->charServer->isUp();
						$mapServerUp = $athenaServer->mapServer->isUp();

						$sql = "SELECT COUNT(char_id) AS players_online FROM {$athenaServer->charMapDatabase}.char ";
						$sql .= "LEFT JOIN {$serverGroup->loginDatabase}.login AS login ON login.account_id = char.account_id ";
						$sql .= "WHERE  `online` > '0' AND login.group_id < 99";
						$sth = $serverGroup->connection->getStatement($sql);
						$sth->execute();
						$res = $sth->fetch();

						$peakSql = "SELECT * FROM {$serverGroup->loginDatabase}.`cp_onlinepeak` WHERE id = 1";
						$peakCon = $serverGroup->connection->getStatement($peakSql);
						$peakCon->execute();
						$peakRes = $peakCon->fetch();

						$lastPeak = $peakRes ? $peakRes->users : 0;
						if ($res && $res->players_online > $lastPeak) {
							$peakSql2 = "REPLACE INTO {$serverGroup->loginDatabase}.`cp_onlinepeak` (id, users, date) VALUES (1, ". $res->players_online .", NOW())";
							$peakCon2 = $serverGroup->connection->getStatement($peakSql2);
							$peakCon2->execute();
							if(Flux::config('DiscordUseWebhook')) {
								sendtodiscord(Flux::config('DiscordWebhookURL'), 'We have hit our new peak online players of '. $res->players_online . '!');
							}
						}

						$loginServerImg = $loginServerUp ? $this->themePath('img/status-on.gif') : $this->themePath('img/status-off.gif');
						$charServerImg = $charServerUp ? $this->themePath('img/status-on.gif') : $this->themePath('img/status-off.gif');
						$mapServerImg = $mapServerUp ? $this->themePath('img/status-on.gif') : $this->themePath('img/status-off.gif');
					?>
					<div class="ax-status-box">LOGIN <img src="<?php echo $loginServerImg; ?>" alt="<?php echo statusMessage($loginServerUp) ?>" title="<?php echo statusMessage($loginServerUp) ?>" /></div>
					<div class="ax-status-box">CHAR <img src="<?php echo $charServerImg; ?>" alt="<?php echo statusMessage($charServerImg) ?>" title="<?php echo statusMessage($charServerUp) ?>" /></div>
					<div class="ax-status-box">MAP <img src="<?php echo $mapServerImg; ?>" alt="<?php echo statusMessage($mapServerImg) ?>" title="<?php echo statusMessage($mapServerUp) ?>" /></div>
					<a href="/?module=server&action=stats"><div class="ax-status-box"><span id="ax-player-count"><?php echo intval($res ? $res->players_online : 0); ?></span> <img src="<?php echo $this->themePath('img/players.gif'); ?>" alt="Players Online" title="Players Online" /></div></a>
				</div>
				<div id="ax-nav">
					<?php
						$navsLeft = array(
							array(
								"link" => "/?module=pages&action=content&path=rules",
								"img" => $this->themePath('img/nav-rules.gif'),
								"label" => "RULES",
								"active-path" => array(
									"module" => "pages",
									"action" => "content",
									"path" => "rules",
								),
							),
							array(
								"link" => "/?module=pages&action=content&path=downloads",
								"img" => $this->themePath('img/nav-downloads.gif'),
								"label" => "DOWNLOAD",
								"active-path" => array(
									"module" => "pages",
									"action" => "content",
									"path" => "downloads",
								),
							),
							array(
								"link" => "/?module=server&action=stats",
								"img" => $this->themePath('img/nav-stats.gif'),
								"label" => "STATS",
								"active-path" => array(
									"module" => "server",
									"action" => "stats",
								),
							),
							array(
								"link" => "/?module=ranking&action=character",
								"img" => $this->themePath('img/nav-ranking.gif'),
								"label" => "RANKING",
								"active-path" => array(
									"module" => "ranking",
								),
							),
							array(
								"link" => "/?module=woe",
								"img" => $this->themePath('img/nav-woe.gif'),
								"label" => "WOE",
								"active-path" => array(
									"module" => "woe",
								),
							),
							array(
								"link" => "/?module=item",
								"img" => $this->themePath('img/nav-items.gif'),
								"label" => "ITEMS",
								"active-path" => array(
									"module" => "item",
								),
							),
							array(
								"link" => "/?module=monster",
								"img" => $this->themePath('img/nav-mobs.gif'),
								"label" => "MOBS",
								"active-path" => array(
									"module" => "monster",
								),
							),
						);

						$navsRight = array(
							array(
								"showLoggedIn" => true,
								"link" => "/?module=history",
								"img" => $this->themePath('img/nav-history.gif'),
								"label" => "HISTORY",
								"active-path" => array(
									"module" => "history",
								),
							),
							array(
								"showLoggedIn" => true,
								"link" => "/?module=servicedesk",
								"img" => $this->themePath('img/nav-support.gif'),
								"label" => "SUPPORT",
								"active-path" => array(
									"module" => "servicedesk",
								),
							),
							array(
								"showLoggedIn" => true,
								"link" => "/?module=account&action=view",
								"img" => $this->themePath('img/nav-account.gif'),
								"label" => "ACCOUNT",
								"active-path" => array(
									"module" => "account",
								),
							),
							array(
								"showLoggedIn" => true,
								"link" => "/?module=account&action=logout",
								"img" => $this->themePath('img/nav-logout.gif'),
								"label" => "LOGOUT",
							),
							array(
								"showLoggedIn" => false,
								"link" => "/?module=account&action=create",
								"img" => $this->themePath('img/nav-register.gif'),
								"label" => "REGISTER",
								"active-path" => array(
									"module" => "account",
									"action" => "create",
								),
							),
							array(
								"showLoggedIn" => false,
								"link" => "/?module=account&action=login",
								"img" => $this->themePath('img/nav-login.gif'),
								"label" => "LOGIN",
								"active-path" => array(
									"module" => "account",
									"action" => "login",
								),
							),
						);
					?>
					<div id="ax-nav-left">
						<?php foreach($navsLeft as $nav): ?>
							<?php
								$isActive = true;
								if (array_key_exists("active-path", $nav)) {
									foreach($nav["active-path"] as $key => $value) {
										if ($params->get($key) != $value) {
											$isActive = false;
											break;
										}
									}
								} else {
									$isActive = false;
								}
							?>
							<div class="ax-nav-item <?php echo $isActive ? "ax-nav-active" : "" ?>">
								<a href="<?php echo $nav["link"]; ?>">
									<div><img src="<?php echo $nav["img"]; ?>"></div>
									<div class="ax-nav-label"><?php echo $nav["label"]; ?></div>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
					
					<div id="ax-nav-right">
						<?php foreach($navsRight as $nav): ?>
							<?php
								$isActive = true;
								if (array_key_exists("active-path", $nav)) {
									foreach($nav["active-path"] as $key => $value) {
										if ($params->get($key) != $value) {
											$isActive = false;
											break;
										}
									}
								} else {
									$isActive = false;
								}
							?>
							<?php if($session->isLoggedIn() == $nav["showLoggedIn"]): ?>
								<div class="ax-nav-item <?php echo $isActive ? "ax-nav-active" : "" ?>">
									<a href="<?php echo $nav["link"]; ?>">
										<div><img src="<?php echo $nav["img"]; ?>"></div>
										<div class="ax-nav-label"><?php echo $nav["label"]; ?></div>
									</a>
								</div>
							<?php endif ?>
						<?php endforeach; ?>
					</div>
					
					
				</div>
			</div>
		</div>
		<div id="ax-content">

		<br />

		<?php include $this->themePath('main/loginbox.php', true) ?>

		<?php if (Flux::config('DebugMode') && @gethostbyname(Flux::config('ServerAddress')) == '127.0.0.1'): ?>
			<p class="notice">Please change your <strong>ServerAddress</strong> directive in your application config to your server's real address (e.g., myserver.com).</p>
		<?php endif ?>
		
		<!-- Messages -->
		<?php if ($message=$session->getMessage()): ?>
			<p class="message"><?php echo htmlspecialchars($message) ?></p>
		<?php endif ?>
		
		<!-- Sub menu -->
		<?php include $this->themePath('main/submenu.php', true) ?>
		
		<!-- Page menu -->
		<?php include $this->themePath('main/pagemenu.php', true) ?>
		
		<!-- Credit balance -->
		<?php if (in_array($params->get('module'), array('donate', 'purchase'))) include $this->themePath('main/balance.php', true) ?>