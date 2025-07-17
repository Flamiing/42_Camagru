function printError(errorMsg, msg) {
	errorMsg.textContent = msg;
	errorMsg.hidden = false;
}

function sendDataToBackend(data) {
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
	})
	.catch((error) => {
		console.error('Error:', error);
	});
}

function postData() {
	
	document.body.addEventListener('submit', function(event) {
		event.preventDefault();
		var form = event.target;
		var imageId = form.id.split('form-')[1];
		var csrfToken = document.getElementsByName('csrf_token')[0].value;
		var currentPage = document.getElementById('currentPage').value;
		try {
			var comment = form.elements['comment'].value;
			let errorMsg = document.querySelector('#error-message-' + imageId);
			errorMsg.hidden = true;

			if (comment.length > 280) {
				printError(errorMsg, 'The maximum number of characters is 280.');
			}
			
			var data = {
				csrf_token: csrfToken,
				image_id: imageId,
				comment: comment,
				page: currentPage
			};
		} catch {
			var like = document.getElementById('likeButton-' + imageId)
			var isLiked;
			
			if (like.classList.contains('image-liked')) {
				like.classList.remove('image-liked');
				like.classList.add('hover-color');
				isLiked = 'no';
			} else {
				like.classList.add('image-liked');
				like.classList.remove('hover-color');
				isLiked = 'yes';
			}

			var data = {
				csrf_token: csrfToken,
				image_id: imageId,
				like: isLiked,
				page: currentPage
			};
		}

		sendDataToBackend(data);
	});
}

postData();