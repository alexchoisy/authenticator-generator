<?php
	require __DIR__ . '/../vendor/autoload.php';

	function array_keys_exist($keys = array(), $haystack = array()) {
		return 0 === count(array_diff($keys, array_keys($haystack)));
	}

	$secret = false;
	$qr = false;

	if(isset($_POST['auth']) && !empty($_POST['auth'])) {

		$required = [
			'keylen',
			'passcodelen',
			'label',
			'userid'
		];

		if(array_keys_exist($required, $_POST['auth'])) {
			extract($_POST['auth']);

			$g = new \Google\Authenticator\GoogleAuthenticator($passcodelen, $keylen);

			$secret = $g->generateSecret();
			$qr = $g->getUrl($userid, $label, $secret);
		}
	}
?>

<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Générateur de codes authenticator</title>
  <style>
	  div.result {
	  	margin-top: 8px;
	  }
  </style>
</head>
<body>
  <h1>Bienvenue sur le générateur de codes authenticator</h1>

  <form class="my-form" method="POST">
		<div class="row">
			<label>Taille de la clé : </label>
			<input type="number" name="auth[keylen]" value="10" required="required" />
		</div>
		  <div class="row">
			<label>Taille du mot de passe : </label>
			<input type="number" name="auth[passcodelen]" value="6" required="required" />
		</div>
		  <div class="row">
			<label>Label : </label>
			<input type="text" name="auth[label]" required="required" />
		</div>
		<div class="row">
			<label>Utilisateur : </label>
			<input type="text" name="auth[userid]" required="required" />
		</div>

		<div class="row">
			<input type="submit" value="Générer"/>
 		</div>
  </form>

  <?php if($secret && $qr): ?>
  	<div class="result">
  		<div class="row">
	  		<label>Label : </label>
	  		<span> <?php echo $label; ?></span>
  		</div>

  		<div class="row">
	  		<label>Clé secrète : </label>
	  		<span> <?php echo $secret; ?></span>
  		</div>
  		<div class="row">
	  		<label>QR Code : </label>
	  		<a href="<?php echo $qr; ?>"><?php echo $qr; ?></a>
  		</div>
  	</div>
  <?php endif; ?>
</body>
</html>