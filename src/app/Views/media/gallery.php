<?php
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(35));
	}
?>

<main>
	<div class="container">
		<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
		<?php if (count($data['images']) > 0): ?>
			<div class="container gallery-container">
				<div class="gallery">
					<?php
						echo '<input type="hidden" id="currentPage" value="' . $data['currentPage'] . '">';
						foreach ($data['images'] as $image) {
							echo "<article class=\"grid gallery-image-card\">\n";
							echo "\t<div class=\"container\">\n";
							echo "\t\t<img class=\"gallery-image\" src=\"data:image/png;base64, " . base64_encode($image->image) . "\" alt=\"PNG Image\">\n";
							echo "\t\t<div class=\"grid image-footer\">\n";
							echo "\t\t\t<h6 class=\"created-by-text\">Created by $image->upload_by</h6>\n";
							if ($data['userLogged']) {
								echo "\t\t\t<form id=\"form-$image->image_id\" class=\"icon-button like-button image-liked\">\n";
								if ($data['likes'][$image->image_id]) {
									echo "\t\t\t\t<button id=\"likeButton-$image->image_id\" class=\"icon-button like-button image-liked\">\n";
								} else {
									echo "\t\t\t\t<button id=\"likeButton-$image->image_id\" class=\"icon-button hover-color like-button\">\n";
								}
								echo "\t\t\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" viewBox=\"0 0 24 24\"><path fill=\"currentColor\" d=\"M2 9.137C2 14 6.02 16.591 8.962 18.911C10 19.729 11 20.5 12 20.5s2-.77 3.038-1.59C17.981 16.592 22 14 22 9.138c0-4.863-5.5-8.312-10-3.636C7.5.825 2 4.274 2 9.137\"/></svg>\n";
								echo "\t\t\t\t</button>\n";
								echo "\t\t\t</form>\n";
							}
							echo "\t\t</div>\n";
							echo "\t</div>\n";
							echo "\t<div class=\"container\">\n";
							echo "\t\t<div class=\"comments-section\">\n";
							echo "\t\t\t<h4 style=\"padding: 1rem;\">Comments</h4>\n";
							echo "\t\t\t<hr>\n";
							echo "\t\t\t<ul class=\"comments\">\n";
							if (count($data['comments'][$image->image_id]) > 0) {
								foreach ($data['comments'][$image->image_id] as $comment) {
									echo "\t\t\t\t<li>\n";
									echo "\t\t\t\t\t<p>$comment->comment</p>\n";
									echo "\t\t\t\t\t<span>$comment->posted_by</span>\n";
									echo "\t\t\t\t</li>\n";
								}
							}
							echo "\t\t\t</ul>\n";
							echo "\t\t\t<hr>\n";
							if ($data['userLogged']) {
								echo "\t\t\t<form id=\"form-$image->image_id\" class=\"comment-box\">\n";
								echo "\t\t\t\t\t<input name=\"comment\" type=\"text\" id=\"comment-input\" placeholder=\"Add a comment...\" required maxlength=\"280\" title=\"Maximum length of a comment is 280 characters\">\n";
								echo "\t\t\t\t\t<button name=\"submit\" value=\"Submit\" id=\"sendComment\" class=\"icon-button hover-color\">\n";
								echo "\t\t\t\t\t\t<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"32\" height=\"32\" viewBox=\"0 0 15 16\"><path fill=\"currentColor\" d=\"M12.49 7.14L3.44 2.27c-.76-.41-1.64.3-1.4 1.13l1.24 4.34q.075.27 0 .54l-1.24 4.34c-.24.83.64 1.54 1.4 1.13l9.05-4.87a.98.98 0 0 0 0-1.72Z\"/></svg>\n";
								echo "\t\t\t\t\t</button>\n";
								echo "\t\t\t</form>\n";
								echo "\t\t\t<div id=\"error-message-$image->image_id\" class=\"error-message-general\" hidden></div>\n";
							}
							echo "\t\t</div>\n";
							echo "\t</div>\n";
							echo "</article>\n";
						}
					?>
				</div>

				<div class="grid">
					<?php if ($data['currentPage'] != 1): ?>
						<a href="?page=<?= $data['currentPage'] - 1?>">
							<button id="previousButton" class="icon-button hover-color">
								<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 32 32"><path fill="currentColor" d="M16 2a14 14 0 1 0 14 14A14 14 0 0 0 16 2m8 15H11.85l5.58 5.573L16 24l-8-8l8-8l1.43 1.393L11.85 15H24Z"/><path fill="none" d="m16 8l1.43 1.393L11.85 15H24v2H11.85l5.58 5.573L16 24l-8-8z"/></svg>
							</button>
						</a>
					<?php endif; ?>
					<?php if ($data['currentPage'] + 1 <= $data['numPages']): ?>
					<a href="?page=<?= $data['currentPage'] + 1?>">
						<button id="nextButton" class="icon-button hover-color">
							<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 32 32"><path fill="currentColor" d="M2 16A14 14 0 1 0 16 2A14 14 0 0 0 2 16m6-1h12.15l-5.58-5.607L16 8l8 8l-8 8l-1.43-1.427L20.15 17H8Z"/><path fill="none" d="m16 8l-1.43 1.393L20.15 15H8v2h12.15l-5.58 5.573L16 24l8-8z"/></svg>
						</button>
					</a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</main>