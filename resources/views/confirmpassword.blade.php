<div class="card bg-transparent border-0">
	@if($valid_token === true)
	<div   class="card-body justify-content-center align-items-center text-center" >
		<img src="{{ assets("images/logo.svg") }}" alt="" width="400">
		<h1 class="mt-20"><strong>RECUPERAR CONTRASEÑA</strong></h1>
		<p>Ingrese su nueva contraseña :</p>
		<form  class="form fv-plugins-bootstrap fv-plugins-framework pt-5" novalidate="novalidate" id="kt_confirm_password_form">
			<div class="form-group fv-plugins-icon-container" id="show_hide_password">
				<input
					id="password"
					name="password"
					class="form-control form-control-solid bg-light h-auto py-5 px-6"
					type="password"
					placeholder="ingrese su nueva contraseña"
					autocomplete="off"
					data-toggle="password"
				>
				<div class="fv-plugins-message-container"></div>
            </div>
            <div class="form-group fv-plugins-icon-container"  id="show_hide_password2">
				<input
					id="confirmPassword"
					class="form-control form-control-solid bg-light h-auto py-5 px-6"
					type="password"
					placeholder="Confirme su nueva contraseña"
					name="confirmPassword"
					autocomplete="off"
					data-fv-not-empty="true" 
					data-fv-not-empty___message="este campo es obligatorio" 
					data-toggle="password"
				>
				<div class="fv-plugins-message-container"></div>
			</div>

			<!--begin::Action-->
			<div class="form-group d-flex flex-wrap justify-content-between align-items-center">
				<a href="{{ url()->route("login") }}" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login">Regresar a iniciar sesion</a>
				<button type="submit" id="kt_recovery_submit_confirm" class="btn btn-primary font-weight-bold px-6 py-4 my-3">Actualizar contraseña</button>
			</div>
			<!--end::Action-->
		</form>
	</div>
	@else
	<div class="card-body justify-content-center align-items-center text-center">
		<img src="https://ciudadano-tramites-stage.herokuapp.com/images/logo.svg" alt="" width="400">
		<h1 class="mt-20"><strong>RECUPERAR CONTRASEÑA</strong></h1>
		<p class="alert alert-danger w-50 mx-auto my-5"><strong>¡LO SENTIMOS!</strong> El token de solicitud a vencido o no es válido. Por favor, intenta nuevamente reestablecer tu contraseña <a href="https://google.com" class="text-white" style="text-decoration: underline;">aquí</a></p>
		<a href="{{ url()->route("home") }}" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 text-white mt-2">Regresar</a>
	</div>
	@endif
</div>