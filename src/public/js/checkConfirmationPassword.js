function onChange() {
	const newPassword = document.querySelector('input[name=new_password]');
	const confirmPassword = document.querySelector('input[name=confirm_new_password]');

	if (confirmPassword.value === newPassword.value) {
		confirmPassword.setCustomValidity('');
	} else {
		confirmPassword.setCustomValidity('Passwords do not match');
	}
  }
