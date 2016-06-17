<?php
if(!defined('INITIALIZED'))
	exit;

$playersOnline = $config['status']['serverStatus_players'];
$casts = $SQL->query("SELECT COUNT(1), IFNULL(SUM(`spectators`), 0) FROM `live_casts`;")->fetch();

$cacheSec = 30;
$cacheFile = 'cache/topplayers.tmp';
if (file_exists($cacheFile) && filemtime($cacheFile) > (time() - $cacheSec)) {
	$topData = file_get_contents($cacheFile);
} else {
	$topData = '';
	$i = 0;
	foreach($SQL->query("SELECT `name`, `level` FROM `players` WHERE `group_id` < 2 AND `account_id` != 3 ORDER BY `level` DESC LIMIT 5")->fetchAll() as $player) {
		$i++;
		$topData .= '<tr><td style="width: 80%"><strong>'.$i.'.</strong> <a href="?view=characters&name='.urlencode($player['name']).'">'.$player['name'].'</a></td><td><span class="label label-primary">Lvl. '.$player['level'].'</span></td></tr>';
	}

	file_put_contents($cacheFile, $topData);
}

$today = strtotime('today 10:00');
$tomorrow = strtotime('tomorrow 10:00');
$now = time();
$remaining = ($now > $today ? $tomorrow : $today) - $now;
?>

<!DOCTYPE html>
<html>
	<head>
		<title><?PHP echo $title ?></title>

		<meta charset="utf-8">
		<meta http-equiv="content-language" content="en">
		<meta name="description" content="Tibia is a free massively multiplayer online role-playing game (MMORPG)">
		<meta name="keywords" content="burmourne, free online rpg, free mmorpg, mmorpg, mmog, online role playing game, online multiplayer game, internet game, online rpg, rpg">

		<!-- Icons -->
		<link rel="shortcut icon" href="<?php echo $layout_name; ?>/images/favicon.gif" />

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="<?php echo $layout_name; ?>/css/style.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $layout_name; ?>/css/metro-bootstrap.css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

		<!-- JS -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	</head>

	<body>
		<div id="container">
			<div class="header"></div>
			<nav class="navbar navbar-default" role="navigation" style="margin-top:-24px;">
				<div class="container-fluid">
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li><a href="?view=news"><i class="fa fa-home"></i> Home</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users"></i> Community <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="?view=casts">Live Casts</a></li>
									<li><a href="?view=online">Online</a></li>
									<li><a href="?view=highscores">Highscores</a></li>
									<li><a href="?view=houses">Houses</a></li>
									<li><a href="?view=guilds">Guilds</a></li>
									<li><a href="?view=wars">Guild Wars</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-book"></i> Library <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="?view=info">Server Information</a></li>
								</ul>
							</li>
							<li><a href="?view=shop"><i class="fa fa-shopping-cart"></i> Shop</a></li>
							<li><a href="?view=forum"><i class="fa fa-comment"></i> Forum</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question-circle"></i> Help <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="?view=support">Support</a></li>
									<li><a href="?view=faq">FAQ</a></li>
									<li><a href="?view=rules">Rules</a></li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-left">
							<form class="navbar-form navbar-left" role="search" type="submit" action="?view=characters" method="post">
								<div class="form-group">
									<input type="text" maxlength="45" name="name" class="form-control" style="max-width: 150px;" placeholder="Search character..." required />
								</div>
							</form>
						</ul>

						<ul class="nav navbar-nav navbar-right">
              				<?php if (!$logged) { ?>
              				<li> <a href="?view=register"><i class="fa fa-share"></i> Sign Up</a></li>
                  			<li class="dropdown">
                  				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Sign in <b class="caret"></b></a>
								<ul class="dropdown-menu" style="padding: 15px;min-width: 250px;">
								<li>
									<div class="row">
										<div class="col-md-12">
											<form class="form" role="form" action="?view=account" method="post">
												<div class="form-group">
													<input type="password" maxlength="35" name="account_login" class="form-control" id="alloptions" placeholder="Account Name" required />
												</div>
												<div class="form-group">
													<input type="password" maxlength="35" name="password_login" class="form-control" id="alloptions" placeholder="Password" required />
												</div>
												<div class="form-group">
													<button type="submit" class="btn btn-primary btn-block">Sign in</button>
												</div>
											</form>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li><p><a href="?view=lostaccount" class="btn btn-danger form-control">Account Lost?</a></p></li>
							</li>
							<?PHP } else { ?>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"><strong><?PHP echo $account_logged->getName(); ?></strong> <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="?view=account">Account Management</a></li>
										<li><a href="?view=account&action=logout">Sign out</a></li>
									</ul>
								</li>
							<?PHP } ?>
						</ul>
					</div>
				</div>
			</nav>

			<div class="sidebar">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Top 5 Level</h3>
					</div>
					<div class="panel-body">
						<table class="table table-condensed table-content table-striped">
							<tbody>
								<?php echo $topData; ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Information</h3>
					</div>
					<div class="panel-body">
						<table class="table table-condensed table-content table-striped">
							<tbody>
								<tr>
									<td><b>IP:</b></td> <td>burmourne.net</td>
								</tr>
								<tr>
									<td><b>Client:</b></td> <td>10.80-10.82</td>
								</tr>
								<tr>
									<td><b>Type:</b></td> <td>PvP</td>
								</tr>
							</tbody>
						</table>
						<p><a href="http://static.otland.net/ipchanger.exe" class="btn btn-success form-control">Download IP Changer</a></p>
						<a href="http://tibia.sx/download/137/exe" class="btn btn-danger form-control">Download Client</a>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Server Status</h3>
					</div>
					<div class="panel-body">
						<table class="table table-condensed table-content table-striped">
							<tbody>
								<tr>
									<td colspan=2>Status: <span class="label label-success pull-right">Online!</span></td>
								</tr>
								<tr>
									<td><a href="?view=online"><?PHP echo $playersOnline; ?> player<?php echo ($playersOnline != 1 ? 's' : ''); ?> online</a></td>
								</tr>
								<tr>
									<td><a href="?view=casts"><?php echo $casts[0]; ?> cast<?php echo ($casts[0] != 1 ? 's' : ''); ?> with <?php echo $casts[1]; ?> spectator<?php echo ($casts[1] != 1 ? 's' : ''); ?></a></td>
								</tr>
								<tr>
									<td><b>The next server save is in:</b><br/></td>
								</tr>
								<tr>
									<td><p><span id="timeToServerSave"></span></p></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!--<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Promoted Websites</h3>
					</div>
					<div class="panel-body">
			            <div id="myCarousel" class="carousel slide">-->

			                <!-- Carousel items -->
			                <!--
			                <div class="carousel-inner">
			                    <div class="item active">
			                        <a href="http://otrealm.com" target="_blank"><img src="http://3.1m.yt/VJwwC2FIq.png" alt="Image" class="img-responsive"></a>
			                    </div>
			                    <div class="item">
		                           	<a href="http://otlist.net" target="_blank"><img src="http://otlist.net/otlist-ad.png" alt="Image" class="img-responsive"></a>
			                    </div>
			                </div>
			            </div>
					</div>
				</div>-->
			</div>

			<div class="content">
				<?php if ($subtopic == '' || $subtopic == 'news') { ?>

				<!-- green box-->
				<!--<div class="alert alert-success">-->
					<!--Burmourne will start in <span id="countdown"><span class="days">00</span> <span class="timeRefDays">days</span> <span class="hours">00</span><span class="timeRefHours">:</span><span class="minutes">00</span><span class="timeRefMinutes">:</span><span class="seconds">00</span><span class="timeRefSeconds"></span>.</span> <a class="alert-link" href="?view=register">Click here</a> to register!-->
					<!--Burmourne has already started. <a class="alert-link" href="?view=register">Click here</a> to register.
				</div>-->

				<!-- red box-->
				<!--<div class="alert alert-danger">
					Donec ullamcorper nulla non metus auctor fringilla.
				</div>-->
				<?php } ?>

				<?PHP echo $main_content; ?>
			</div>
		</div>

		<script>var secondsToServerSave = <?php echo json_encode($remaining); ?>;</script>
		<script src="<?php echo $layout_name; ?>/js/bootstrap.min.js"></script>
		<script src="<?php echo $layout_name; ?>/js/jquery.countdown.min.js"></script>
		<script src="<?php echo $layout_name; ?>/js/misc.js"></script>
	</body>
</html>