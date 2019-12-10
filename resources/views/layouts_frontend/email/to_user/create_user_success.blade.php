<p>
	<p>Welcome to {{ url('/') }}!</p>

	<p>You're receiving this email because your email was used to register for a free account at {{ url('/') }}</p> 

	<p>This mail is automatically generated. Please don't answer this email. </p>

	<p>Access data</p>
	<p>User: {{ $email }}</p>
	<p>Password: {{ $password }}</p>

	<p>Thanks for registering!</p>

	<p>Your Sqlite Workbench team </p>
</p>
