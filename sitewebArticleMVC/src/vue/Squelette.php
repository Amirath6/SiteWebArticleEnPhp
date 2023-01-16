<!DOCTYPE html>
<html lang="fr">
<head>
	<title><?php echo $title ?></title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="/SiteWebArticleEnPhp/ProjetStage/sitewebArticleMVC/skin/article.css">
</head>
<body>
<nav class="menu">
		<ul>
<?php
	foreach ($menu as $text => $link) {
		echo "<li><a href=\"$link\">$text</a></li>";
	}
?>
		</ul>
	</nav>
<?php
	if ($this->feedback !== ''){?>
	<div class="feedback"><?php echo $this->feedback; ?></div>
<?php } ?>

	<main>
		<h1><?php echo $title; ?></h1>
		<?php echo $content; ?>
	</main>
</body>
</html>

