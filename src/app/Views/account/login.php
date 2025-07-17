<?php
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(35));
	}
?>

<main>
	<div class="proper-margin">
		<article class="grid form-card">
			<div>
				<h1>Sign in</h1>
				<form name="loginForm" method="post" action="/account/login">
					<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
					<input class="input-box" type="text" name="username" id="username" placeholder="Username" aria-label="Username" autocomplete="username" required maxlength="30" minlength="6" pattern="^[A-Za-z0-9_.]*$" />
					<input class="input-box" type="password" name="password" id="password" placeholder="Password" aria-label="Password" autocomplete="current-password" required maxlength="16" minlength="8" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[+.\-_*$@!?%&])(?=.*\d)[A-Za-z\d+.\-_*$@!?%&]+$" title="Must be minimum 12 characters long and contain at least one uppercase letter, one number, and one special character like +.-_*$@!?%&" />
					<?php if (isset($data['error'])): ?>
                        <div class="error-message-general">
                            <?= $data['error'] ?>
                        </div>
                    <?php endif; ?>
					<button class="input-box" name="submit" type="submit" value="Submit">Sign in</button>
				</form>
				<div class="separator">
					<hr>
					<span>𝗢𝗥</span>
					<hr>
				</div>
				<a href="/account/password/reset/forgotten" class="reset-password">Forgotten your password?</a>
			</div>
		</article>
		<article class="grid change-form-card">
			<p class="simple-p">
				Don't have an account?
				<a class="no-decoration" href="/account/signup">Sign up</a>
			</p>
		</article>
	</div>
</main>