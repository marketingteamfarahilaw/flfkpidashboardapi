<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>NowNa Daily Update</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	</head>
	<body id="emailApp" style="background: #000; color: #fff;">
		<div style="width: 80%; margin: 0 auto;padding: 100px 30px;">
			<h3>Hey <?= $first_name ?>,</h3>
			<div class="videos">
				<h1>Video</h1>

				<div style="display: inline-block;">
					<h2>NOW</h2>
					<?php foreach ($channel_id as $key => $value) : ?>
						<?php if($value['tag_id'] == '1'): ?>
						<h1><?= $value['title'] ?></h1>
						<p><?= $value['blurb'] ?></p>
					<?php endif;?>
					<?php endforeach; ?>
				</div>
				<div style="display: inline-block;">
					<h2>HOT</h2>
					<?php foreach ($channel_id as $key => $value) : ?>
						<?php if($value['tag_id'] == '2'): ?>
						<h1><?= $value['title'] ?></h1>
						<p><?= $value['blurb'] ?></p>
					<?php endif;?>
					<?php endforeach; ?>
				</div>
				<div style="display: inline-block;">
					<h2>WOW</h2>
					<?php foreach ($channel_id as $key => $value) : ?>
						<?php if($value['tag_id'] == '3'): ?>
						<h1><?= $value['title'] ?></h1>
						<p><?= $value['blurb'] ?></p>
					<?php endif;?>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="news">
				<h1>News</h1>
				<?php foreach ($news_id as $key => $value) : ?>
					<?php foreach ($value as $finkey => $fin_value) : ?>
						<h1><?= $fin_value['title'] ?></h1>
						<p><?= $fin_value['blurb'] ?></p>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</div>
			<a href="http://localhost:8080/v1/daily_update.php" target="_blank" style="display: inline-block; margin: 0 auto;text-align: center;">Check it out!</a>
		</div>

	</body>
</html>