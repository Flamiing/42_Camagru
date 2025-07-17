<?php
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(35));
	}
?>

<main>
	<?php if (isset($data['success']) && $data['success']): ?>
		<div class="container">
			<img src="/public/img/status/account-details-updated.gif" alt="Animater GIF">
			<h1 style="font-size: 250%;">Account Details Updated!</h1>
			<p id="countdown" style="font-size: 150%;">Redirecting in 5 seconds...</p>
		</div>
	<?php else: ?>
		<div class="proper-margin">
			<article class="grid account-details-card">
				<h1 class="account-details-title">Account Information</h1>
				<?php if ($data['edit_mode']): ?>
					<div>
						<form name="accountDetailsForm" method="post" action="/account/settings/edit">
							<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
							<table class="account-details-table">
								<tr>
									<td>Username</td>
									<td class="account-details-spacing">
										<input class="input-box" style="margin-bottom: initial;" type="text" name="new_username" id="new_username" placeholder="<?= $data['username'] ?>" aria-label="Username" autocomplete="username" maxlength="30" minlength="6" pattern="^[A-Za-z0-9_.]*$" />
									</td>
								</tr>
								<tr>
									<td>Email</td>
									<td class="account-details-spacing">
										<input class="input-box" style="margin-bottom: initial;" type="new_email" name="new_email" id="new_email" placeholder="<?= $data['email'] ?>" aria-label="Email" autocomplete="email" maxlength="50" minlength="6" pattern="^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$" />
									</td>
								</tr>
								<tr>
									<td>Comment Notifications</td>
									<td class="account-details-spacing">
										<input name="notifications_activated" type="checkbox" role="switch" <?= $data['notifications_activated'] ? "checked" : null; ?> />
									</td>
								</tr>
							</table>
							<?php if (isset($data['error'])): ?>
								<div class="error-message-general">
									<?= $data['error'] ?>
								</div>
							<?php endif; ?>
							<button class="outline account-save-changes" name="submit" type="submit" value="Submit">Save Changes</button>
						</form>
					</div>
				<?php else: ?>
					<div>
						<table class="account-details-table">
						<tr>
							<td>Username</td>
							<td class="account-details-spacing"><?= $data['username'] ?></td>
						</tr>
						<tr>
							<td>Email</td>
							<td class="account-details-spacing"><?= $data['email'] ?></td>
						</tr>
						<tr>
							<td>Comment Notifications</td>
							<td class="account-details-spacing">
								<input name="notifications_activated" type="checkbox" role="switch" <?= $data['notifications_activated'] ? "checked" : null; ?> disabled />
							</td>
						</tr>
						</table>
					</div>
					<div class="grid account-details-buttons">
						<button class="outline" onclick="window.location.href='/account/password/reset';">Change Password</button>
						<button class="outline" onclick="window.location.href='/account/settings/edit';">Edit Account Information</button>
					</div>
				<?php endif; ?>
			</article>
		</div>
	<?php endif; ?>
</main>