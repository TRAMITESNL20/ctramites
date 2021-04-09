<div class="card bg-transparent border-0">
	<div class="card-body justify-content-center align-items-center text-center">
		<img src="{{ assets("images/logo.svg") }}" alt="" width="400">
		<h1 class="mt-20"><strong>INICIA SESIÓN</strong></h1>
		<p>Ingrese tus datos de acceso:</p>
		<form class="form fv-plugins-bootstrap fv-plugins-framework" novalidate="novalidate" id="kt_login_signin_form">
			<div class="form-group fv-plugins-icon-container">
				<input
					class="form-control form-control-solid bg-light h-auto py-5 px-6"
					type="text"
					placeholder="Nombre de usuario o Correo electrónico"
					name="username"
					autocomplete="off"
					data-fv-not-empty="true"
					data-fv-not-empty___message="El nombre de usuario o correo electrónico es requerido" 
				>
				<div class="fv-plugins-message-container"></div>
			</div>
			<div class="form-group fv-plugins-icon-container">
				<input
					class="form-control form-control-solid bg-light h-auto py-5 px-6"
					type="password"
					placeholder="Contraseña"
					name="password"
					autocomplete="off"

					data-fv-not-empty="true"
					data-fv-not-empty___message="La contraseña es requerida" 

					data-fv-regexp="false"
					data-fv-regexp___regexp="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$"
					data-fv-regexp___message="La contraseña de ser de al menos 8 caracteres,<br>contener al menos una mayúscula, un número y un caracter especial."
				>
				<div class="fv-plugins-message-container"></div>
			</div>
			<!--begin::Action-->
			<div class="form-group d-flex flex-wrap justify-content-between align-items-center flex-column">
				<button type="submit" id="kt_login_signin_submit" class="btn font-weight-bold px-9 py-4 my-3"><i class="fas fa-spinner fa-spin mr-2 d-none"></i> Ingresar</button>
				<a href="{{ url()->route("password/recovery") }}" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login_forgot">¿Olvidaste tu contraseña?</a>
			</div>
			<!--end::Action-->
		</form> 
	</div>
</div>