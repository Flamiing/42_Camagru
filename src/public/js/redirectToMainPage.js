function redirectToMainPage() {
	var counter = 5;
	var interval = setInterval(function() {
		counter--;
		if (counter <= 0) {
			clearInterval(interval);
			window.location.href = '/';
		} else {
			document.getElementById('countdown').innerText = "Redirecting in " + counter + " seconds...";
		}
	}, 1000);
}

redirectToMainPage();
