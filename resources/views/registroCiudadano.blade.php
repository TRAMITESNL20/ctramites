<div class="container-fluid p-0">
	<div class="card bg-transparent border-0" id="kt_login_signin_form">
		<div class="card-body justify-content-center align-items-center text-center">
			<img style="margin-left: auto;margin-right: auto; display: block;" src="{{ assets("images/logo.svg") }}" alt="" width="200">
			<h3 class="mt-5"><strong>Datos de la cuenta</strong></h3>
			<p>Ingresa tus datos de acceso:</p>
			<form>
				<div class="form-row">
					<div class="form-group col-md-6">
					<label for="inputEmail4">Nombre de usuario</label>
					<input type="email" class="form-control" id="inputEmail4">
					</div>
					<div class="form-group col-md-6">
					<label for="exampleFormControlSelect1">Tipo de Persona</label>
					<select class="form-control" id="exampleFormControlSelect1">
						<option>Fisica</option>
						<option>Moral</option>
					</select>
					</div>
				</div>
				<div class="form-group">
					<label for="inputAddress">Correo electronico</label>
					<input type="text" class="form-control" id="inputAddress" placeholder="">
				</div>
				<div class="form-group">
					<label for="inputAddress2">Contraseña</label>
					<input type="text" class="form-control" id="passwordCiudadano" placeholder="">
				</div>
				<div class="form-group">
					<label for="inputAddress2">Confirmar Contraseña</label>
					<input type="text" class="form-control" id="confirmPasswordCiudadano" placeholder="">
				</div>
				<button type="submit" class="btn btn-primary">Sign in</button>
			</form>
		</div>
	</div>
	<footer style="position: fixed;bottom: 0px; width:100%" class="bg-light align-items-center px-3">
		<!-- <div class="col"><strong>Gobierno del Estado de Nuevo León</strong></div>
		<div class="col text-center"><img src="{{ assets("images/escudo-gris.svg") }}" height="30"></div> -->
		<div class="col text-center">
			<strong class="mr-2"><?= date("Y") ?></strong>
			<strong class="mr-2">Aviso de Privacidad</strong>
			<strong>Contacto</strong>
		</div>
	</footer>
</div>