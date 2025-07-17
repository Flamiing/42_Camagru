var filter;
var selectedFilters = [];

function isFilterSelected() {
	let cells = document.querySelectorAll('.selectable');
	for (let count = 0; count < cells.length; count++) {
		if (cells[count].classList.contains('selected')) {
			return true;
		}
	}
	return false;
}

function isCanvasBlank(canvas) {
	if (canvas.toDataURL() == document.getElementById('blank').toDataURL()){
		return true;
	} else {
		return false;
	}
}

function calculatePosition(canvas, img) {
	// Calculate the aspect ratio
	let aspectRatio = img.width / img.height;
	
	// Calculate the new height and width
	let newWidth = canvas.width;
	let newHeight = newWidth / aspectRatio;

	// If the new height is greater than the canvas height, adjust the width instead
	if (newHeight > canvas.height) {
		newHeight = canvas.height;
		newWidth = newHeight * aspectRatio;
	}

	// Calculate the position to center the image
	let posX = (canvas.width - newWidth) / 2;
	let posY = (canvas.height - newHeight) / 2;

	return {
		posX: posX,
		posY: posY,
		newWidth: newWidth,
		newHeight: newHeight
	};
}

function printUploadError(imageInput, errorMsg, msg) {
	imageInput.value = '';
	errorMsg.textContent = msg;
	errorMsg.hidden = false;
}

function handleUploadedImage(file, imageInput, errorMsg) {
	var camera = document.querySelector('#camera');
	var canvas = document.querySelector('#canvas');
	var context = context = canvas.getContext('2d');
	var noCamera = document.querySelector('#no-camera');
	
	let img = new Image();
	img.src = URL.createObjectURL(file);
	img.onload = function() {
		if (this.naturalWidth < 640 || this.naturalHeight < 480) {
			printUploadError(imageInput, errorMsg, 'The image should be at least 640x480px.');
			let uploadPictureButton = document.querySelector('#uploadPictureButton');
			uploadPictureButton.disabled = false;
		} else {
			if (isFilterSelected()) {
				let takePictureButton = document.querySelector('#takePicture');
				takePictureButton.disabled = false;
			}
			camera.style.display = 'none';
			noCamera.style.display = 'none';
			canvas.hidden = false;

			// Fill with white before drawing the uploaded image in case is a png
			context.fillStyle = 'white';
			context.fillRect(0, 0, canvas.width, canvas.height);

			// Draw the image to the canvas
			context.drawImage(this, 0, 0, canvas.width, canvas.height);
		}
	};
}

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

function sendPictureToBackend(data) {
	let url = window.location.href;

	fetch(url, {
		method: 'POST',
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

function getFiltersIds() {
	var filters = [];
	for (let count = 0; count < selectedFilters.length; count++) {
		filter_id = selectedFilters[count].split('/').pop().replace('.png', '');
		filters.push(filter_id);
	}
	return filters;
}

function takePictureEvent(camera, canvas, context) {
	let filterPreview = document.getElementById('filterPreview');
	let takePictureButton = document.getElementById('takePicture');
	let uploadPictureButton = document.getElementById('uploadPictureButton');
	let csrfToken = document.querySelector('input').value;
	
	document.getElementById('takePicture').addEventListener('click', () => {
		takePictureButton.disabled = true;
		uploadPictureButton.disabled = true;
		camera.style.display = 'none';
		filterPreview.style.display = 'none';
		canvas.hidden = false;

		let filters = getFiltersIds();

		if (isCanvasBlank(canvas)) {
			context.drawImage(camera, 0, 0, 640, 480);
		}
	
		var data = {
			csrf_token: csrfToken,
			image: canvas.toDataURL('image/png'),
			filters_ids: filters
		};

		setTimeout(() => {
			sendPictureToBackend(data);
		}, 100)
	})
}

function filterSelectorEvent() {
	let cells = document.querySelectorAll('.selectable');

	for (let count = 0; count < cells.length; count++) {
		cells[count].addEventListener('click', function() {
			let takePictureButton = document.querySelector('#takePicture');
			let filterPreview = document.querySelector('#filterPreview');
			let img = this.querySelector('img');

			if (this.classList.contains('selected')) {
				this.classList.remove('selected');
				let index = selectedFilters.indexOf(img.src);
				if (index !== -1) {
					selectedFilters.splice(index, 1);
				}

				let filterPreviewToRemove = document.querySelector(`.filter-preview[data-filter-src="${img.src}"]`);
				filterPreviewToRemove.parentNode.removeChild(filterPreviewToRemove);
			
				if (selectedFilters.length === 0) {
					takePictureButton.disabled = true;
				}

				if (selectedFilters.length === 0) {
					takePictureButton.disabled = true;
					filterPreview.style.display = 'none';
				}
			} else {
				this.classList.add('selected');
				selectedFilters.push(img.src);

				let newFilterPreview = document.createElement('img');
				newFilterPreview.src = img.src;
				newFilterPreview.style.display = 'block';
				newFilterPreview.classList.add('filter-preview');
				newFilterPreview.dataset.filterSrc = img.src;

				let filterPreviewContainer = document.querySelector('#filterPreviewContainer');
				filterPreviewContainer.appendChild(newFilterPreview);

				takePictureButton.disabled = false;
			}

			localStorage.setItem('selectedFilters', JSON.stringify(selectedFilters));
		});
	}
}

function uploadPictureEvent(camera, canvas, context, noCamera) {
	let imageInput = document.querySelector('#uploadPicture');
	let uploadPictureButton = document.querySelector('#uploadPictureButton');
	let errorMsg = document.querySelector('#error-message');

	imageInput.addEventListener('change', function() {
		if (this.files && this.files[0]) {
			let file = this.files[0];
			if (file.type === 'image/jpeg' || file.type === 'image/png' || file.type === 'image/jpg') {
				//uploadPictureButton.disabled = true;
				errorMsg.hidden = true;

				handleUploadedImage(file, imageInput, errorMsg);

			} else {
				printUploadError(imageInput, errorMsg,
					'The file you uploaded was not a JPEG,\
					PNG, or JPG image. Please try again with\
					a correct image file.')
			}
		} else {
			printUploadError(imageInput, errorMsg, 'Nothing was uploaded.')
		}
	});
}

function handleCamera() {
	var camera = document.querySelector('#camera');
	var canvas = document.querySelector('#canvas');
	var context = context = canvas.getContext('2d');
	var noCamera = document.querySelector('#no-camera');
	filter = {
		id: null,
		path: null
	};

	if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
		navigator.mediaDevices.getUserMedia({ video: true })
			.then((stream) => {
				camera.srcObject = stream;
				camera.play();
			})
	} else {
		camera.style.display = 'none';
		noCamera.style.display = 'block';
	}

	uploadPictureEvent(camera, canvas, context, noCamera);
	takePictureEvent(camera, canvas, context);
	filterSelectorEvent();
}

handleCamera();
