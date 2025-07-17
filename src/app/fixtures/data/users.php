<?php

return [
    [
		'user_id' => 'testuser1' . bin2hex(random_bytes(10)),
        'username' => 'testuser1',
        'email' => 'test1@example.com',
        'password' => password_hash('Password1.', PASSWORD_DEFAULT),
		'account_activated' => true
    ],
    [
		'user_id' => 'testuser2' . bin2hex(random_bytes(10)),
        'username' => 'testuser2',
        'email' => 'test2@example.com',
        'password' => password_hash('Password1.', PASSWORD_DEFAULT),
		'account_activated' => true
    ],
	[
		'user_id' => 'Flamiing' . bin2hex(random_bytes(10)),
        'username' => 'Flamiing',
        'email' => 'amirlaaouam@gmail.com',
        'password' => password_hash('Password1.', PASSWORD_DEFAULT),
		'account_activated' => true
    ],
];