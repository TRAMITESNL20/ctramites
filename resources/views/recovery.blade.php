<div class="container-fluid p-0">
	<div class="card bg-transparent border-0" id="kt_login_recovery_form">
		<div class="card-body justify-content-center align-items-center text-center">
			<img src="{{ assets("images/logo.svg") }}" alt="" width="200">
			<h1 class="mt-20"><strong>RECUPERAR CONTRASEÑA</strong></h1>
			<p>Ingrese su correo electronico o nombre de usuario :</p>
			<form class="form fv-plugins-bootstrap fv-plugins-framework pt-5" novalidate="novalidate">
				<div class="form-group fv-plugins-icon-container">
					<input
							id="email"
							name="email" 
							class="form-control form-control-solid bg-light h-auto py-5 px-6"
							placeholder="Nombre de usuario o Correo electrónico"
	                        type="email"
							data-fv-not-empty="true" 
							data-fv-not-empty___message="El nombre de usuario o correo electrónico es requerido" 
					>
					<div class="fv-plugins-message-container"></div>
				</div>
				{{-- <div class="dropdown-divider"></div> --}}

				<!--begin::Action-->
				<div class="form-group d-flex flex-wrap justify-content-between align-items-center flex-column">
					<button type="submit" id="kt_recovery_submit" class="btn font-weight-bold px-6 py-4 my-3">Recuperar contraseña</button>
					<a href="{{ url()->route("login") }}" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login">Regresar a iniciar sesion</a>
				</div>
				<!--end::Action-->
			</form>
		</div>
	</div>
	<footer class="row bg-light align-items-center px-3">
		<div class="col"><strong>Gobierno del Estado de Nuevo León</strong></div>
		<div class="col text-center"><img src="{{ assets("images/escudo-gris.svg") }}" height="30"></div>
		<div class="col text-right">
			<strong class="mr-2"><?= date("Y") ?></strong>
			<strong class="mr-2">Aviso de Privacidad</strong>
			<strong>Contacto</strong>
		</div>
	</footer>
</div>