function reloadScripts() {
	// Gets all the scripts in the html document
	var scripts = document.querySelectorAll('script');

	// Gets each script from all the scripts
	for (var scriptsIndex = 0; scriptsIndex < scripts.length; scriptsIndex++) {
		var script = scripts[scriptsIndex];
		var newScript = document.createElement('script');

		// Gets each attribute for current script and adds them to the new script
		for (var attributesIndex = 0; attributesIndex < script.attributes.length; attributesIndex++) {
			var attr = script.attributes[attributesIndex];
			newScript.setAttribute(attr.name, attr.value);
		}

		// Replaces the old script with the new script to reload it
		script.parentNode.replaceChild(newScript, script);
	}
}

function imageSelectorEvent() {
	let images = document.querySelectorAll('.selectableImages');
	let deleteButton = document.querySelector('#deleteButton');

	for (let count = 0; count < images.length; count++) {
		images[count].addEventListener('click', function() {

			
			let removeIcon = document.querySelector('#svg-' + this.id);
			if (this.classList.contains('selected')) {
				this.classList.remove('selected');
				deleteButton.disabled = true;
				removeIcon.style.display = 'none';
			} else {
				for (let i = 0; i < images.length; i++) {
					let currentRemoveIcon = document.querySelector('#svg-' + images[i].id);
					images[i].classList.remove('selected');
					currentRemoveIcon.style.display = 'none';
				}

				this.classList.add('selected');
				deleteButton.disabled = false;
				removeIcon.style.display = 'block';
			}
		});
	}
}

function sendCallToBackend(data) {
	let url = window.location.href;

	fetch(url, {
		method: 'DELETE',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify(data),
	})
	.then(response => response.text())
	.then(data => {
		document.body.innerHTML = data;
		reloadScripts();
	})
	.catch((error) => {
		console.error('Error:', error);
	});
}

function imageDeletionEvent() {
	let deleteButton = document.getElementById('deleteButton');
	let csrfToken = document.querySelector('input').value;
	
	deleteButton.addEventListener('click', () => {
		deleteButton.disabled = true;

		let imageToDelete = document.querySelector('.selected');
	
		var data = {
			csrf_token: csrfToken,
			image_id: imageToDelete.id
		};

		sendCallToBackend(data);
		imageToDelete.remove();
	})
}

function deleteImageEvent() {
	imageSelectorEvent();
	imageDeletionEvent();
}

deleteImageEvent();