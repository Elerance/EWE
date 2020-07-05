<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=__("master:title")?></title>
	<link rel="shortcut icon" href="<?=img('brand/icon.ico')?>">
	<link rel="stylesheet" href="<?=css('index.css')?>">
	<meta name="description" content="<?=__("master:description")?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="menu">
		<a href="/" class="title"><?=__("master:title")?></a>
		<a target="_blank" href="//github.com/Elerance/EWE"><?=__("master:github")?></a>
		<a target="_blank" href="//github.com/Elerance/EWE/issues"><?=__("master:issues")?></a>
		<a target="_blank" href="//github.com/Elerance/EWE/wiki"><?=__("master:wiki")?></a>
	</div>
	<div class="container">
		<?=pagecontents()?>
	</div>
</body>
</html>