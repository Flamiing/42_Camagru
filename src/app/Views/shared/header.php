<header class="grid">
	<div>
		<nav class="main-navbar">
			<div class="logo-title-container">
				<ul class="logo">
					<a href="/" class="hover-color hover-color-logo menu-logo no-decoration">
						<img class="filter-white" src="/public/img/camagru-logo.svg" alt="Camagru Logo" width="128" height="128">
					</a>
				</ul>
				<ul class="general-menu">
					<h2><a href="/" class="contrast hover-color menu-title no-decoration">𝗖𝗮𝗺𝗮𝗴𝗿𝘂</a></h2>
				</ul>
			</div>
			<ul class="general-menu">
				<li class="hide-on-mobile menu-links"><a href="/media/gallery" class="contrast hover-color no-decoration">
					<div class="icon-with-text">
						<svg xmlns="http://www.w3.org/2000/svg" id="Filled" viewBox="0 0 24 24" width="64" height="64"><path d="M11.122,12.536a3,3,0,0,0-4.244,0l-6.84,6.84A4.991,4.991,0,0,0,5,24H19a4.969,4.969,0,0,0,2.753-.833Z"/><circle cx="18" cy="6" r="2"/><path d="M19,0H5A5.006,5.006,0,0,0,0,5V16.586l5.464-5.464a5,5,0,0,1,7.072,0L23.167,21.753A4.969,4.969,0,0,0,24,19V5A5.006,5.006,0,0,0,19,0ZM18,10a4,4,0,1,1,4-4A4,4,0,0,1,18,10Z"/></svg>
						<span>Gallery</span>
					</div>
				</a></li>
				<?php
					if ($data['userLogged']) {
						echo '<li class="hide-on-mobile menu-links"><a href="/media/camera" class="contrast hover-color no-decoration">
							<div class="icon-with-text">
								<svg xmlns="http://www.w3.org/2000/svg" id="Filled" viewBox="0 0 24 24" width="64" height="64"><path d="M17.721,3,16.308,1.168A3.023,3.023,0,0,0,13.932,0H10.068A3.023,3.023,0,0,0,7.692,1.168L6.279,3Z"/><circle cx="12" cy="14" r="4"/><path d="M19,5H5a5.006,5.006,0,0,0-5,5v9a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V10A5.006,5.006,0,0,0,19,5ZM12,20a6,6,0,1,1,6-6A6.006,6.006,0,0,1,12,20Z"/></svg>
								<span>Camera</span>
							</div>
						</a></li>
						<li class="hide-on-mobile menu-links"><a href="/account/settings" class="contrast hover-color no-decoration">
							<div class="icon-with-text">
								<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="64" height="64"><path d="M16.043,14H7.957A4.963,4.963,0,0,0,3,18.957V24H21V18.957A4.963,4.963,0,0,0,16.043,14Z"/><circle cx="12" cy="6" r="6"/></svg>
								<span>Account</span>
							</div>
						</a></li>
						<li class="hide-on-mobile menu-links"><a href="/account/logout" class="contrast hover-color no-decoration">
							<div class="icon-with-text">
								<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="64" height="64"><path d="m24,4v16c0,2.206-1.794,4-4,4h-3c-.552,0-1-.448-1-1s.448-1,1-1h3c1.103,0,2-.897,2-2V4c0-1.103-.897-2-2-2h-3c-.552,0-1-.448-1-1s.448-1,1-1h3c2.206,0,4,1.794,4,4Zm-7.015,10.45l-5.293,5.272c-.508.509-1.195.778-1.907.778-.369,0-.744-.072-1.104-.221-1.033-.425-1.677-1.352-1.681-2.418v-1.861H3c-1.654,0-3-1.346-3-3v-2c0-1.654,1.346-3,3-3h4v-1.859c.005-1.07.649-1.997,1.682-2.421,1.055-.433,2.238-.215,3.012.559l5.29,5.267c1.352,1.353,1.351,3.551,0,4.903Z"/></svg>
								<span>Logout</span>
							</div>
						</a></li>';
					}
					else 
					{
						echo '<li class="hide-on-mobile menu-links"><a href="/account/signin" class="contrast hover-color no-decoration">
							<div class="icon-with-text">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512.077 512.077" style="enable-background:new 0 0 512.077 512.077;" xml:space="preserve" width="64" height="64"><g><path d="M362.705,170.744H149.372v-21.333c-0.016-58.91,47.727-106.68,106.637-106.696c38.815-0.011,74.572,21.064,93.363,55.027   c5.72,10.303,18.71,14.019,29.013,8.299c10.303-5.72,14.019-18.71,8.299-29.013c-39.949-72.153-130.826-98.26-202.98-58.311   c-47.529,26.315-77.017,76.367-76.999,130.695v30.379c-38.826,16.945-63.944,55.259-64,97.621v128   c0.071,58.881,47.786,106.596,106.667,106.667h213.333c58.881-0.07,106.596-47.786,106.667-106.667v-128   C469.301,218.529,421.586,170.814,362.705,170.744z M277.372,362.744c0,11.782-9.551,21.333-21.333,21.333   s-21.333-9.551-21.333-21.333v-42.667c0-11.782,9.551-21.333,21.333-21.333s21.333,9.551,21.333,21.333V362.744z"/></g>
								<span>Sign in</span>
							</div>
						</a></li>';
					}
				?>
				<li class="menu-button menu-links" onclick=showSidebar()><a href="#" class="contrast hover-color no-decoration">
					<div class="icon-with-text">
						<svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 -960 960 960" width="64"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
					</div>
				</a></li>
			</ul>
			<ul class="sidebar">
				<li onclick=hideSidebar()><a href="#" class="contrast hover-color no-decoration">
					<div class="icon-with-text-sidebar">
						<svg xmlns="http://www.w3.org/2000/svg" height="64" viewBox="0 -960 960 960" width="64"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
						<span class="icon-text">Close</span>
					</div>
				</a></li>
				<li><a href="/media/gallery" class="contrast hover-color no-decoration">
					<div class="icon-with-text-sidebar">
						<svg xmlns="http://www.w3.org/2000/svg" id="Filled" viewBox="0 0 24 24" width="64" height="64"><path d="M11.122,12.536a3,3,0,0,0-4.244,0l-6.84,6.84A4.991,4.991,0,0,0,5,24H19a4.969,4.969,0,0,0,2.753-.833Z"/><circle cx="18" cy="6" r="2"/><path d="M19,0H5A5.006,5.006,0,0,0,0,5V16.586l5.464-5.464a5,5,0,0,1,7.072,0L23.167,21.753A4.969,4.969,0,0,0,24,19V5A5.006,5.006,0,0,0,19,0ZM18,10a4,4,0,1,1,4-4A4,4,0,0,1,18,10Z"/></svg>
						<span class="icon-text">Gallery</span>
					</div>
				</a></li>
				<?php
					if ($data['userLogged']) {
						echo '<li><a href="/media/camera" class="contrast hover-color no-decoration">
							<div class="icon-with-text-sidebar">
								<svg xmlns="http://www.w3.org/2000/svg" id="Filled" viewBox="0 0 24 24" width="64" height="64"><path d="M17.721,3,16.308,1.168A3.023,3.023,0,0,0,13.932,0H10.068A3.023,3.023,0,0,0,7.692,1.168L6.279,3Z"/><circle cx="12" cy="14" r="4"/><path d="M19,5H5a5.006,5.006,0,0,0-5,5v9a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V10A5.006,5.006,0,0,0,19,5ZM12,20a6,6,0,1,1,6-6A6.006,6.006,0,0,1,12,20Z"/></svg>
								<span class="icon-text">Camera</span>
							</div>
						</a></li>
						<li><a href="/account/settings" class="contrast hover-color no-decoration">
							<div class="icon-with-text-sidebar">
								<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="64" height="64"><path d="M16.043,14H7.957A4.963,4.963,0,0,0,3,18.957V24H21V18.957A4.963,4.963,0,0,0,16.043,14Z"/><circle cx="12" cy="6" r="6"/></svg>
								<span class="icon-text">Account</span>
							</div>
						</a></li>
						<li><a href="/account/logout" class="contrast hover-color no-decoration">
							<div class="icon-with-text-sidebar">
								<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="64" height="64"><path d="m24,4v16c0,2.206-1.794,4-4,4h-3c-.552,0-1-.448-1-1s.448-1,1-1h3c1.103,0,2-.897,2-2V4c0-1.103-.897-2-2-2h-3c-.552,0-1-.448-1-1s.448-1,1-1h3c2.206,0,4,1.794,4,4Zm-7.015,10.45l-5.293,5.272c-.508.509-1.195.778-1.907.778-.369,0-.744-.072-1.104-.221-1.033-.425-1.677-1.352-1.681-2.418v-1.861H3c-1.654,0-3-1.346-3-3v-2c0-1.654,1.346-3,3-3h4v-1.859c.005-1.07.649-1.997,1.682-2.421,1.055-.433,2.238-.215,3.012.559l5.29,5.267c1.352,1.353,1.351,3.551,0,4.903Z"/></svg>
								<span class="icon-text">Logout</span>
							</div>
						</a></li>';
					}
					else
					{
						echo '<li><a href="/account/signin" class="contrast hover-color no-decoration">
							<div class="icon-with-text-sidebar">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512.077 512.077" style="enable-background:new 0 0 512.077 512.077;" xml:space="preserve" width="64" height="64"><g><path d="M362.705,170.744H149.372v-21.333c-0.016-58.91,47.727-106.68,106.637-106.696c38.815-0.011,74.572,21.064,93.363,55.027   c5.72,10.303,18.71,14.019,29.013,8.299c10.303-5.72,14.019-18.71,8.299-29.013c-39.949-72.153-130.826-98.26-202.98-58.311   c-47.529,26.315-77.017,76.367-76.999,130.695v30.379c-38.826,16.945-63.944,55.259-64,97.621v128   c0.071,58.881,47.786,106.596,106.667,106.667h213.333c58.881-0.07,106.596-47.786,106.667-106.667v-128   C469.301,218.529,421.586,170.814,362.705,170.744z M277.372,362.744c0,11.782-9.551,21.333-21.333,21.333   s-21.333-9.551-21.333-21.333v-42.667c0-11.782,9.551-21.333,21.333-21.333s21.333,9.551,21.333,21.333V362.744z"/></g>
								<span class="icon-text">Sign in</span>
							</div>
						</a></li>';
					}
				?>
			</ul>
		</nav>
	</div>
</header>