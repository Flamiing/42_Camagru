<?php
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(35));
	}
?>

<main>
	<?php if (isset($data['email_sent']) && $data['email_sent']): ?>
		<div class="container">
			<img src="/public/img/status/reset-password-email.gif" alt="Animater GIF">
			<h1 style="font-size: 250%;">Reset Password Email Sent</h1>
			<p id="countdown" style="font-size: 150%;">Please check your email to reset your password.</p>
		</div>
	<?php else: ?>
	<div class="proper-margin">
		<article class="grid forgotten-password-card">
			<div>
				<h1>Reset your password</h1>
				<p class="simple-p" style="margin-bottom: 1rem;">Enter your email address and we'll send you a link to reset your password.</p>
				<form name="forgottenPasswordForm" method="post" action="/account/password/reset/forgotten">
					<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
					<input class="input-box" type="email" name="email" id="email" placeholder="Email" aria-label="Email" autocomplete="email" required maxlength="50" minlength="6" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" />
					<?php if (isset($data['error'])): ?>
                        <div class="error-message-general">
                            <?= $data['error'] ?>
                        </div>
                    <?php endif; ?>
					<button class="input-box" name="submit" type="submit" value="Submit">Reset password</button>
				</form>
			</div>
		</article>
	</div>
	<?php endif; ?>
</main>