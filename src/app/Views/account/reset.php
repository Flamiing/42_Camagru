<?php
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(35));
	}
?>

<main>
	<?php if (isset($data['success']) && $data['success']): ?>
		<div class="container">
			<img src="/public/img/status/password-changed-successfully.gif" alt="Animater GIF">
			<h1 style="font-size: 250%;">Password Changed Successfully</h1>
			<p id="countdown" style="font-size: 150%;">Redirecting in 5 seconds...</p>
		</div>
	<?php else: ?>
	<div class="proper-margin">
		<article class="grid form-card">
			<div>
				<h1>Create a new password</h1>
				<form name="resetPasswordForm" method="post" action="/account/password/reset">
					<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
					<?php if (!isset($data['forgotten'])): ?>
						<input class="input-box" type="password" name="current_password" id="current_password" placeholder="Current Password" aria-label="Current Password" autocomplete="password" required maxlength="16" minlength="8" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[+.\-_*$@!?%&])(?=.*\d)[A-Za-z\d+.\-_*$@!?%&]+$" title="Must be minimum 12 characters long and contain at least one uppercase letter, one number, and one special character like +.-_*$@!?%&" />
					<?php endif; ?>
					<input class="input-box" type="password" onchange="onChange()" name="new_password" id="new_password" placeholder="New Password" aria-label="New Password" autocomplete="password" required maxlength="16" minlength="8" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[+.\-_*$@!?%&])(?=.*\d)[A-Za-z\d+.\-_*$@!?%&]+$" title="Must be minimum 12 characters long and contain at least one uppercase letter, one number, and one special character like +.-_*$@!?%&" />
					<input class="input-box" type="password" onchange="onChange()" name="confirm_new_password" id="confirm_new_password" placeholder="Confirm New Password" aria-label="Confirm New Password" autocomplete="password" required maxlength="16" minlength="8" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[+.\-_*$@!?%&])(?=.*\d)[A-Za-z\d+.\-_*$@!?%&]+$" title="Must be minimum 12 characters long and contain at least one uppercase letter, one number, and one special character like +.-_*$@!?%&" />
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