<?php
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(35));
	}
?>

<main>
	<div class="container grid">
		<div class="container">
			<div class="flex-container">
				<article class="thumbnail-card">
					<h4 style="text-align: center;">Previous Photos</h4>
					<hr>
					<?php if (isset($data['images'])): ?>
					<div class="image-thumbnails">
						<table>
							<?php
								foreach ($data['images'] as $image) {
									if ($image->thumbnail != null) {
										$imageData = base64_encode($image->thumbnail);
										echo '<tr>' . PHP_EOL;
										echo '	<div class="image-wrapper">' . PHP_EOL;
										echo '		<svg id="svg-' . $image->image_id . '" class="delete-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="#f50000" d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6zm2.46-7.12l1.41-1.41L12 12.59l2.12-2.12l1.41 1.41L13.41 14l2.12 2.12l-1.41 1.41L12 15.41l-2.12 2.12l-1.41-1.41L10.59 14zM15.5 4l-1-1h-5l-1 1H5v2h14V4z"/></svg>' . PHP_EOL;
										echo '		<img id="' . $image->image_id . '" src="data:image/png;base64,' . $imageData . '" class="thumbnail selectableImages">' . PHP_EOL;
										echo '	</div>' . PHP_EOL;
										echo '</tr>' . PHP_EOL;
									}
								}
							?>
						</table>
					</div>
					<?php endif; ?>
				</article>
				<article class="camera-card">
					<div id="camera-preview" class="camera-preview">
						<div id="filterPreviewContainer"></div>
						<img id="filterPreview" class="filter-preview">
						<canvas id="canvas" width="640" height="480" class="photo-canvas" hidden></canvas>
						<video autoplay="true" id="camera" width="640" height="480" class="camera"></video>
						<svg id="no-camera" class="no-camera" xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 24 24"><path fill="currentColor" d="m21 6.5l-4 4V7c0-.55-.45-1-1-1H9.82L21 17.18zM3.27 2L2 3.27L4.73 6H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.21 0 .39-.08.54-.18L19.73 21L21 19.73z"/></svg>
					</div>
					<canvas id="blank" width="640" height="480" class="photo-canvas" hidden></canvas>
				</article>
				<article class="filters-card">
					<h4 style="text-align: center;">Filters</h4>
					<hr>
					<div class="filter-selector">
						<table>
							<?php
								if (isset($data['filters'])) {
									foreach ($data['filters'] as $key => $filter) {
										echo '<tr>';
										echo '	<td class="selectable">';
										echo '		<img id="' . $key . '" class="filter" src="/' . $filter . '" alt="' . $key . ' PNG image">';
										echo '	</td>';
										echo '</tr>';
									}
								}
							?>
						</table>
					</div>
				</article>
			</div>
			<div>
				<?php if (isset($data['error'])): ?>
					<div class="error-message-general">
						<?= $data['error'] ?>
					</div>
				<?php endif; ?>
				<div id="error-message" class="error-message-general" hidden></div>
			</div>
			<div>
				<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
				<button disabled type="button" id="takePicture" class="contrast photo-button hover-color">
					<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="currentColor" d="M15.854 6.146a2.84 2.84 0 0 1 .685 1.114l.448 1.377a.543.543 0 0 0 1.026 0l.448-1.377a2.84 2.84 0 0 1 1.798-1.796l1.378-.448a.544.544 0 0 0 0-1.025l-.028-.007l-1.378-.448a2.84 2.84 0 0 1-1.798-1.796L17.987.363a.544.544 0 0 0-1.027 0l-.448 1.377l-.011.034a2.84 2.84 0 0 1-1.759 1.762l-1.378.448a.544.544 0 0 0 0 1.025l1.378.448c.42.14.8.376 1.113.689m7.163 3.819l.766.248l.015.004a.303.303 0 0 1 .147.46a.3.3 0 0 1-.147.11l-.765.248a1.58 1.58 0 0 0-1 .999l-.248.764a.302.302 0 0 1-.57 0l-.249-.764a1.58 1.58 0 0 0-.999-1.002l-.765-.249a.303.303 0 0 1-.147-.46a.3.3 0 0 1 .147-.11l.765-.248a1.58 1.58 0 0 0 .984-.999l.249-.764a.302.302 0 0 1 .57 0l.249.764a1.58 1.58 0 0 0 .999.999M10.122 4.003h1.959a1.55 1.55 0 0 1 .95-.962l1.391-.453l.05-.017a2.3 2.3 0 0 0-.547-.068h-3.803a2.25 2.25 0 0 0-1.917 1.073L7.33 5H5.25A3.25 3.25 0 0 0 2 8.25v9.5A3.25 3.25 0 0 0 5.25 21h13.5A3.25 3.25 0 0 0 22 17.75v-3.852c-.406.164-.89.118-1.252-.137a1.3 1.3 0 0 1-.248-.229v4.218a1.75 1.75 0 0 1-1.75 1.75H5.25a1.75 1.75 0 0 1-1.75-1.75v-9.5c0-.966.784-1.75 1.75-1.75h2.5a.75.75 0 0 0 .64-.358l1.093-1.781l.065-.09a.75.75 0 0 1 .574-.268M16.5 12.5a4.5 4.5 0 1 0-9 0a4.5 4.5 0 0 0 9 0m-7.5 0a3 3 0 1 1 6 0a3 3 0 0 1-6 0"/></svg>
				</button>
				<input type="file" id="uploadPicture" hidden/>
				<button type="button" id="uploadPictureButton" class="contrast photo-button hover-color" onclick="document.getElementById('uploadPicture').click();">
					<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5"><path stroke-linejoin="round" d="M21.25 13V8.5a5 5 0 0 0-5-5h-8.5a5 5 0 0 0-5 5v7a5 5 0 0 0 5 5h6.26"/><path stroke-linejoin="round" d="m3.01 17l2.74-3.2a2.2 2.2 0 0 1 2.77-.27a2.2 2.2 0 0 0 2.77-.27l2.33-2.33a4 4 0 0 1 5.16-.43l2.47 1.91M8.01 10.17a1.66 1.66 0 1 0-.02-3.32a1.66 1.66 0 0 0 .02 3.32"/><path stroke-miterlimit="10" d="M18.707 15v5"/><path stroke-linejoin="round" d="m21 17.105l-1.967-1.967a.458.458 0 0 0-.652 0l-1.967 1.967"/></g></svg>
				</button>
				<button disabled type="button" id="deleteButton" class="contrast photo-button hover-color">
					<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="currentColor" d="M10 5h4a2 2 0 1 0-4 0M8.5 5a3.5 3.5 0 1 1 7 0h5.75a.75.75 0 0 1 0 1.5h-1.32l-1.17 12.111A3.75 3.75 0 0 1 15.026 22H8.974a3.75 3.75 0 0 1-3.733-3.389L4.07 6.5H2.75a.75.75 0 0 1 0-1.5zm2 4.75a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0zM14.25 9a.75.75 0 0 1 .75.75v7.5a.75.75 0 0 1-1.5 0v-7.5a.75.75 0 0 1 .75-.75m-7.516 9.467a2.25 2.25 0 0 0 2.24 2.033h6.052a2.25 2.25 0 0 0 2.24-2.033L18.424 6.5H5.576z"/></svg>
				</button>
			</div>
		</div>
	</div>
</main>