<?php
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(35));
	}
?>

<main>
	<div class="proper-margin">
		<article class="grid form-card">
			<div>
				<h1>Create your account</h1>
				<form name="signUpForm" method="post" action="/account/signup">
					<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
					<input class="input-box" type="email" name="email" id="email" placeholder="Email" aria-label="Email" autocomplete="email" required maxlength="50" minlength="6" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" />
					<input class="input-box" type="text" name="username" id="username" placeholder="Username" aria-label="Username" autocomplete="username" required maxlength="30" minlength="6" pattern="^[A-Za-z0-9_.]*$" />
					<input class="input-box" type="password" name="password" id="password" placeholder="Password" aria-label="Password" autocomplete="current-password" required maxlength="16" minlength="8" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[+.\-_*$@!?%&])(?=.*\d)[A-Za-z\d+.\-_*$@!?%&]+$" title="Must be minimum 12 characters long and contain at least one uppercase letter, one number, and one special character like +.-_*$@!?%&" />
					<?php if (isset($data['error'])): ?>
                        <div class="error-message-general">
                            <?= $data['error'] ?>
                        </div>
                    <?php endif; ?>
					<button class="input-box signup-button" name="submit" type="submit" value="Submit">Sign up</button>
				</form>
			</div>
		</article>
		<article class="grid change-form-card">
			<p class="simple-p">
				Have an account?
				<a class="no-decoration" href="/account/login">Sign in</a>
			</p>
		</article>
	</div>
</main>