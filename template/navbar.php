<nav class="navbar bg-primary m-0 navbar-expand-sm navbar-dark bg-dark">
	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#qrcdrNavbar" aria-controls="qrcdrNavbar" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="qrcdrNavbar">
		<ul class="navbar-nav ms-auto">
<!--
		<li class="nav-item">
			<a class="nav-link" href="#">Link 1</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#">Link 2</a>
		</li>
-->
			<li style="margin-top:5px; margin-right:15px;"><a href="login.php"  style="color:white;text-decoration:none;font-weight:bolder">Área de Login</a></li>
			<?php echo qrcdr()->langMenu('menu'); ?>
		</ul>
	</div>
</nav>