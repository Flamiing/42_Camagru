<main>
	<div class="container">
		<?php if (isset($data['pending']) && $data['pending']): ?>
			<img src="/public/img/status/pending-activation.gif" alt="Animater GIF">
			<h1 style="font-size: 250%;">Account Pending Activation</h1>
			<p id="countdown" style="font-size: 150%;">Please check your email to activate your account.</p>
		<?php endif; ?>
		<?php if (isset($data['success']) && $data['success']): ?>
			<img src="/public/img/status/account-activated.gif" alt="Animater GIF">
			<h1 style="font-size: 250%;">Account Activated</h1>
			<p id="countdown" style="font-size: 150%;">Redirecting in 5 seconds...</p>
		<?php endif; ?>
		<?php if (isset($data['success']) && !$data['success']): ?>
			<img src="/public/img/status/activation-error.gif" alt="Animater GIF">
			<h1 style="font-size: 500%;">400</h1>
			<p style="font-size: 250%;"><?= $data['error'] ?></p>
		<?php endif; ?>
	</div>
</main>