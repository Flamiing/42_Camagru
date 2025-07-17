<?php
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(35));
	}
?>

<main>
	<article class="grid gallery-image-card">
		<div class="container">
			<img class="gallery-image" src="data:image/png;base64, <?= base64_encode($data['image']) ?>" alt="PNG Image">
		</div>
		<div class="container">
        	<div class="comments-section">
				<h4 style="padding: 1rem;">Comment</h4>
				<hr>
				<ul class="comments">
					<li>
						<p><?= $data['comment'] ?></p>
						<span><?= $data['posted_by'] ?></span>
					</li>
				</ul>
				<hr>
			</div>
		</div>
	</article>
</main>