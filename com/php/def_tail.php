			</tr></table>
		</div>
	</div>
	<div id="screen"></div>
	<div id="loginbox">
		<div class="exit"></div>
		<table class="wrapper"><tr>
			<td class="lcol">
				<h1>Login</h1>
				<form id="login_form">
					<label>Username</label>
					<input tabindex="0" type="text" name="username"/>
					<label>Password</label>
					<input tabindex="0" type="password" name="password"/>
					<input type="text" name="url" value="<?= $compath?>/php/user_login.php" class="hidden"/>
					<div class="sub" tabindex="0">Login&nbsp;&nbsp;&nbsp;&nbsp;&rarr;</div>
				</form>
			</td>
			<td class="mcol"><div></div></td>
			<td class="rcol">
				<h1>Join Welkynd and Sign Up</h1>
				<form id="signup_form">
					<table><tr>
						<td>
							<label class="req">@ Username</label>
							<input type="text" name="username" tabindex="0"/>
							<label>Email</label>
							<input type="email" name="email" tabindex="0"/>
							<input type="text" name="url" value="<?= $compath?>/php/user_signup.php" class="hidden"/>
						</td>
						<td>
							<label class="req">Password</label>
							<input type="password" name="password" tabindex="0"/>
							<label class="req">Verify Password</label>
							<input type="password" name="password2" tabindex="0"/>
							<div class="sub" tabindex="0">Sign Up&nbsp;&nbsp;&nbsp;&nbsp;&rarr;</div>
						</td>
					</tr></table>
				</form>
			</td>
		</tr></table>
		<div class="errorbox"></div>
	</div>
</body>
</html>