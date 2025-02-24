
<!-- Layout Header -->
<table style="background-color:#e1e8ed" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center">
			<table style="margin:20px 0;background-color:#fff;border:solid 1px #dcdcdc;width:640px;" align="center" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
					<!-- Fin Layout Header -->
					<div style="background-color:#111c55;text-align:left;">
						<img style="padding:15px;max-height:45px;" src="#" alt="logo"/>
					</div>

					<!-- Contenido -->
					<div style="padding:0 30px;">
						<p>Hola,</p>

						<p>Has recibido un mensaje de contacto de: <?= ucfirst(@$_GET['first_name'])." ".ucfirst(@$_GET['last_name']) ?></p>
						
						<p><strong>Mensaje:</strong><br> <?= ucfirst(strtolower(@$_GET['message'])) ?></p>

						<p><strong>Email:</strong><br> <?= ucfirst(strtolower(@$_GET['email'])) ?></p>
					</div>
					<!-- Fin Contenido -->

					<!-- Layout Footer -->
					<!-- Fin Layout Footer -->
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>