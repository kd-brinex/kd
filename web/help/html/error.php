<div id="error-block" class="block">
	<form method="POST" action="">
		<h2>Ошибки</h2>
		<ul>
		<?php
		foreach($errors as $error) {
			echo '<li>' . $error . '</li>';
		}?>
		</ul>
	</form>
</div>
