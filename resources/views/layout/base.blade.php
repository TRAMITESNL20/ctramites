@php
	// dd($user->notary->notary_number);
@endphp
<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
	<head>
		<base href="{{ getenv("APP_URL") }}">
		<meta charset="utf-8" />
		<title>{{ isset($subtitle) ? "{$subtitle} | " : "" }}{{ $title ?? "" }}</title>
		<meta name="description" content="{{ $description ?? "" }}" />
		<meta name="keywords" content="{{ $keywords ?? "" }}">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, shrink-to-fit=no" />
		{{-- <meta http-equiv="Content-Security-Policy" content="prefetch-src '{{ getenv('TESORERIA_HOSTNAME') }}'"> --}}
		<!--begin::Fonts-->
		{{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" /> --}}
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,300;0,400;0,600;0,900;1,700&display=swap" rel="stylesheet">
		<!--end::Fonts-->
		<!--begin::Page Vendors Styles(used by this page)-->
		{{-- <link href="plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" /> --}}
		<!--end::Page Vendors Styles-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{ assets("plugins/global/plugins.bundle.css") }}" rel="stylesheet" type="text/css" />
		{{-- <link href="plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" /> --}}
		<link href="{{ assets("css/app.css") }}" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
		<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
		{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
		<script src="{{ assets("js/jquery.min.js") }}"></script>

		<link rel="apple-touch-icon" sizes="180x180" href="{{ assets("/images/favicon/apple-touch-icon.png") }}">
		<link rel="icon" type="image/png" sizes="32x32" href="{{ assets("/images/favicon/favicon-32x32.png") }}">
		<link rel="icon" type="image/png" sizes="194x194" href="{{ assets("/images/favicon/favicon-194x194.png") }}">
		<link rel="icon" type="image/png" sizes="192x192" href="{{ assets("/images/favicon/android-chrome-192x192.png") }}">
		<link rel="icon" type="image/png" sizes="16x16" href="{{ assets("/images/favicon/favicon-16x16.png") }}">
		<link rel="manifest" href="{{ assets("/images/favicon/site.webmanifest") }}">
		<link rel="mask-icon" href="{{ assets("/images/favicon/safari-pinned-tab.svg") }}" color="#088cbc">
		{{-- <link rel="shortcut icon" href="{{ assets("/images/favicon/favicon.ico") }}"> --}}
		<meta name="apple-mobile-web-app-title" content="Estado de Nuevo Le&oacute;n">
		<meta name="application-name" content="Estado de Nuevo Le&oacute;n">
		<meta name="msapplication-TileColor" content="#088cbc">
		<meta name="msapplication-TileImage" content="{{ assets("/images/favicon/mstile-144x144.png") }}">
		<meta name="msapplication-config" content="{{ assets("/images/favicon/browserconfig.xml") }}">
		<meta name="theme-color" content="#088cbc">
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body " class="header-fixed header-mobile-fixed page-loading">
		<!--begin::Main-->
		<!--begin::Header Mobile-->
		<div id="kt_header_mobile" class="header-mobile bg-primary header-mobile-fixed">
			<!--begin::Logo-->
			<button class="btn p-0 burger-icon burger-icon-left ml-4" id="kt_header_mobile_toggle">
					<span></span>
			</button>
			<a href="{{ url()->route("home") }}">
				<img alt="Logo" src="{{ assets('images/logo.svg') }}" class="max-h-30px" style="width: 70%; margin-left: auto;margin-right: auto;display: block;"/>
			</a>
			{{-- <a class="btn pl-0">
				<span> portal</span>
			</a> --}}

			<!--end::Logo-->
			<!--begin::Toolbar-->
			<div class="d-flex align-items-center">

				
				{{-- <button class="btn p-0 ml-2" id="kt_header_mobile_topbar_toggle">
					<span class="svg-icon svg-icon-xl">
						<!--begin::Svg Icon | path:media/svg/icons/General/User.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<polygon points="0 0 24 0 24 24 0 24" />
								<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
								<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>
				</button> --}}
				{{--  mobile quick user toogle--}}
				<button class="btn p-0 ml-2 kt_quick_user_toggle" id="kt_quick_user_toggle">
					<span class="svg-icon svg-icon-xl">
						<!--begin::Svg Icon | path:media/svg/icons/General/User.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" stroke="#0179b7" version="1.1">
							<g stroke="#0179b7" stroke-width="1" fill="#0179b7" fill-rule="">
								<polygon points="0 0 24 0 24 24 0 24" />
								<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#0179b7" />
								<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#0179b7"  />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>
				</button>


			</div>
			<!--end::Toolbar-->
		</div>
		<!--end::Header Mobile-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="d-flex flex-column flex-column-fluid page">
				<!--begin::Top-->
				<div class="header-top bg-white @if($empty_layout === true) d-none @endif">
					<!--begin::Container-->
					<div class="container-fluid px-0">
						<!--begin::Left-->
						<div class="d-none d-md-flex align-items-center align-items-stretch mx-auto">
							<!--begin::Logo-->
							{{-- <a href="{{ url()->route("home") }}" class="mx-auto d-flex align-items-center py-2">
								<img alt="Logo" src="{{ assets('images/icon.svg') }}" width="70" class="mr-4" />
								Un sitio oficial del Gobierno del Estado de Nuevo León
							</a> --}}
							<!--end::Logo-->

							<a href="{{ url()->route("home") }}" class="mr-2 d-lg-flex col-lg-1 col-xl-2 align-items-center justify-content-center text-center d-flex ">
								<img alt="Logo" src="{{ assets('images/logo.svg') }}" class="max-h-35px mr-3 w-50" width="200" />
							</a>
							<!--begin::Topbar-->
							<div class="topbar text-secondary ml-auto py-4 w-100 px-3 align-items-center">
								<div class="mr-auto header-menu header-menu-mobile header-menu-layout-default">
									<ul class="menu-nav">
										<li class="menu-item">
											<a href="#" class="menu-link">
												<span class="menu-text m-0">Guía de Usuario</span>
											</a>
										</li>
										<li class="menu-item">
											<a href="#" class="menu-link">
												<span class="menu-text m-0">Registro de Usuarios</span>
											</a>
										</li>
										<li class="menu-item">
											<a href="#" class="menu-link">
												<span class="menu-text m-0">Noticias</span>
											</a>
										</li>
										<li class="menu-item">
											<a href="#" class="menu-link">
												<span class="menu-text m-0">Nuevo Trámite</span>
											</a>
										</li>
									</ul>
								</div>
							    <form class="form-inline my-2 my-lg-0">
									<div class="input-group" id="busquedaInputGroup">
								        <span class="input-group-prepend">
								            <div class="input-group-text bg-transparent border-right-0"><i class="fa fa-search"></i></div>
								        </span>
								        <input class="form-control  mr-sm-2 " type="text" name="busqueda" id="busqueda"  placeholder="Ejemplo: Certificado de gravamen" style="border-left: none; transition: width 0.8s linear 0.2s; ">
								    </div>
							    </form>
								<!--begin::Search-->
								<div class="dropdown d-none">
									<!--begin::Toggle-->
									<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
										<div class="btn btn-icon btn-hover-transparent-white btn-lg btn-dropdown mr-1">
											<span class="svg-icon svg-icon-xl">
												<!--begin::Svg Icon | path:media/svg/icons/General/Search.svg-->
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
														<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
													</g>
												</svg>
												<!--end::Svg Icon-->
											</span>
										</div>
									</div>
									<!--end::Toggle-->
									<!--begin::Dropdown-->
									<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
										<div class="quick-search quick-search-dropdown" id="kt_quick_search_dropdown">
											<!--begin:Form-->
											<form method="get" class="quick-search-form">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">
															<span class="svg-icon svg-icon-lg">
																<!--begin::Svg Icon | path:media/svg/icons/General/Search.svg-->
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24" />
																		<path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
																		<path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero" />
																	</g>
																</svg>
																<!--end::Svg Icon-->
															</span>
														</span>
													</div>
													<input type="text" class="form-control" placeholder="Search..." />
													<div class="input-group-append">
														<span class="input-group-text">
															<i class="quick-search-close ki ki-close icon-sm text-muted"></i>
														</span>
													</div>
												</div>
											</form>
											<!--end::Form-->
											<!--begin::Scroll-->
											<div class="quick-search-wrapper scroll" data-scroll="true" data-height="325" data-mobile-height="200"></div>
											<!--end::Scroll-->
										</div>
									</div>
									<!--end::Dropdown-->
								</div>
								<!--end::Search-->
								<!--begin::User-->
								<div class="topbar-item">
									<div class="btn btn-icon btn-hover-transparent-dark w-auto d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle2" onclick="iehelper()">
										<div class="d-flex flex-column text-right pr-3">
											<span class="opacity-50 font-weight-bold font-size-sm d-none d-md-inline">{{ $user ? $user->name : "" }} {{ $user ? $user->fathers_surname : "" }} </span>
											<span class="font-weight-bolder font-size-sm d-none d-md-inline">{{ $user ? $user->role->description : "" }}</span>
										</div>
										<span class="symbol symbol-35">
											<span class="symbol-label font-size-h5 font-weight-bold bg-dark-o-20">{{ $user ? $user->name[0].$user->fathers_surname[0] : "" }}</span>
										</span>
									</div>
								</div>
								<!--end::User-->
								<!--begin::Notifications-->
								@if ($user && $user->role->name !== 'notary_capturist')
									<div class="dropdown mr-3 ml-2">
										<!--begin::Toggle-->
										<div class="btn btn-icon btn-hover-transparent-dark bg-secondary btn-dropdown btn-lg mr-1 pulse pulse-white">
											<a href="{{ url()->route("tramite.cart") }}" class="topbar-item" id="notifications">
												<span class="svg-icon svg-icon-xl">
													<!--begin::Svg Icon | path:media/svg/icons/Code/Compiling.svg-->
													<i class="flaticon2-shopping-cart-1"></i>
													<span id="totalTramitesCarrito"  class="badge badge-danger">{{ session()->get("tramites") }}</span>
												</span>
											</a>
										</div>
										<!--end::Toggle-->
									</div>
								@endif
								@if ($user && in_array($user->role->name, ["notary_titular", "notary_substitute"]))
									<div class="dropdown mr-3 ml-2">
										<!--begin::Toggle-->
										<div class="btn btn-icon btn-hover-transparent-dark bg-secondary btn-dropdown btn-lg mr-1 pulse pulse-white">
											<a href="{{ url()->route("pendiente-firma") }}" class="topbar-item" id="notifications">
												<span class="svg-icon svg-icon-xl">
													<!--begin::Svg Icon | path:media/svg/icons/Code/Compiling.svg-->
													<i class="flaticon2-pen"></i>
													<span id="totalTramitesFirma"  class="badge badge-danger">{{ session()->get("tramitesFirma") }}</span>
												</span>
											</a>
										</div>
										<!--end::Toggle-->
									</div>
								@endif
								<!--end::Notifications-->
								<!--begin::Notifications-->
								<!--end::Notifications-->
								<!--begin::Notifications-->
								<div class="dropdown mr-3 ml-2">
									<!--begin::Toggle-->
									<div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" id="notifications">
										<div class="btn btn-icon btn-hover-transparent-dark {{ $notifications["total"] > 0 && $notifications["total"] > $notifications["read"] ? "bg-primary" : "" }} btn-dropdown btn-lg mr-1 pulse pulse-white">
											<span class="svg-icon svg-icon-xl">
												<!--begin::Svg Icon | path:media/svg/icons/Code/Compiling.svg-->
												<i class="far fa-bell {{ $notifications["total"] > 0 && $notifications["total"] > $notifications["read"] ? "text-white" : "" }}"></i>
												<span class="badge badge-danger">{{ $notifications["total"] - $notifications["read"] }}</span>
											</span>
											@if($notifications["total"] > 0 && $notifications["total"] > $notifications["read"])
												<span class="pulse-ring"></span>
											@endif
										</div>
									</div>
									<!--end::Toggle-->
									<!--begin::Dropdown-->
									<div class="dropdown-menu p-0 m-0 mt-3 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
										<form>
											<!--begin::Header-->
											<div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url(media/misc/bg-1.jpg)">
												<!--begin::Title-->
												<h4 class="d-flex flex-center rounded-top">
													<span class="text-white">Notificaciones</span>
													<span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{ $notifications["total"] - $notifications["read"] }} nuevas</span>
												</h4>
												<!--end::Title-->
												<!--begin::Tabs-->
												<ul class="nav nav-bold nav-tabs nav-tabs-line nav-tabs-line-3x nav-tabs-line-transparent-white nav-tabs-line-active-border-success mt-3 px-8" role="tablist">
													<li class="nav-item">
														<a class="nav-link text-white active show" data-toggle="tab" href="#topbar_notifications_notifications">Notificaciones</a>
													</li>
													<li class="nav-item">
														<a class="nav-link text-white" data-toggle="tab" href="#topbar_notifications_events">Avisos</a>
													</li>
												</ul>
												<!--end::Tabs-->
											</div>
											<!--end::Header-->
											<!--begin::Content-->
											<div class="tab-content">
												<div class="tab-pane active show p-0" id="topbar_notifications_notifications" role="tabpanel">
													<!--begin::Scroll-->
													<div class="scroll mr-n7 w-100" data-scroll="true" data-height="300" data-mobile-height="200">
														@if(!isset($notifications["items"]["notificacion"]) || $notifications["notificacion_count"] == 0)
															<div class="d-flex flex-column font-weight-bold w-100 h-100 align-items-center justify-content-center text-center">
																<p>No se encuentran notificaciones <br> en este momento</p>
															</div>
														@else
															@foreach($notifications["items"]["notificacion"] as $key => $notification)
																@if($notification["type"] == 'notificacion')
																	<div class="d-flex align-items-center mb-6 border-bottom pb-4 px-4 {!! $key == 0 ? 'pt-4' : '' !!}">
																		@if(!empty($notification["icon"]))
																			<!--begin::Symbol-->
																			<div class="symbol symbol-40 symbol-light-primary mr-5">
																				<span class="symbol-label">
																					<span class="svg-icon svg-icon-lg svg-icon-primary">
																						<!--begin::Svg Icon | path:media/svg/icons/Home/Library.svg-->
																						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																								<rect x="0" y="0" width="24" height="24" />
																								<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
																								<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
																							</g>
																						</svg>
																						<!--end::Svg Icon-->
																					</span>
																				</span>
																			</div>
																			<!--end::Symbol-->
																		@endif
																		<!--begin::Text-->
																		<div class="d-flex flex-column font-weight-bold w-100">
																			<a href="detalle/{!! $notification["solicitud_id"] ?? "#" !!}" class="text-dark text-hover-primary mb-1 font-size-lg">{!! $notification["title"] ?? "" !!}</a>
																			<span class="text-muted">{!! $notification["description"] ?? "" !!}</span>
																			<span class="badge badge-secondary ml-auto mt-2">{!! $notification["type"] ?? "" !!}</span>
																		</div>
																		<!--end::Text-->
																	</div>
																@endif
															@endforeach
														@endif
													</div>
													<!--end::Scroll-->
												</div>
												<div class="tab-pane p-0" id="topbar_notifications_events" role="tabpanel">
													<!--begin::Scroll-->
													<div class="scroll mr-n7 w-100" data-scroll="true" data-height="300" data-mobile-height="200">
														@if(!isset($notifications["items"]["aviso"]) || $notifications["aviso_count"] == 0)
															<div class="d-flex flex-column font-weight-bold w-100 h-100 align-items-center justify-content-center text-center">
																<p>No se encuentran avisos <br> en este momento</p>
															</div>
														@else
															@foreach($notifications["items"]["aviso"] as $key => $notification)
																@if($notification["type"] == 'aviso')
																	<div class="d-flex align-items-center mb-6 border-bottom pb-4 px-4 {!! $key == 0 ? 'pt-4' : '' !!}">
																		@if(!empty($notification["icon"]))
																			<!--begin::Symbol-->
																			<div class="symbol symbol-40 symbol-light-primary mr-5">
																				<span class="symbol-label">
																					<span class="svg-icon svg-icon-lg svg-icon-primary">
																						<!--begin::Svg Icon | path:media/svg/icons/Home/Library.svg-->
																						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																								<rect x="0" y="0" width="24" height="24" />
																								<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
																								<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
																							</g>
																						</svg>
																						<!--end::Svg Icon-->
																					</span>
																				</span>
																			</div>
																			<!--end::Symbol-->
																		@endif
																		<!--begin::Text-->
																		<div class="d-flex flex-column font-weight-bold w-100">
																			<span class="text-dark text-hover-primary mb-1 font-size-lg">{!! $notification["title"] ?? "" !!}</span>
																			<span class="text-muted">{!! $notification["description"] ?? "" !!}</span>
																			<span class="badge badge-secondary ml-auto mt-2">{!! $notification["type"] ?? "" !!}</span>
																		</div>
																		<!--end::Text-->
																	</div>
																@endif
															@endforeach
														@endif
													</div>
													<!--end::Scroll-->
												</div>
											</div>
											<!--end::Content-->
										</form>
									</div>
									<!--end::Dropdown-->
								</div>
								<!--end::Notifications-->
							</div>
							<!--end::Topbar-->
							<!--begin::Tab Navs-->
						</div>
						<!--end::Left-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Top-->
				<!--begin::Wrapper-->
				<div class="d-flex flex-row flex-row-fluid wrapper " id="kt_wrapper">

					<!--begin::Header-->
					<div id="kt_header" class="col-lg-1 col-xl-2 flex-column header-fixed bg-white @if($empty_layout === true) d-none @else d-lg-flex @endif h-100 px-0 pt-0">
						<div id="close-header">
							<i class="fas fa-chevron-left"></i>
						</div>
						<!--begin::Bottom-->
						<div class="header-sidebar bg-dark-o-20 h-100" id="kt-header-sidebar">
							<!--begin::Container-->
							<div class="container px-0">
								<!--begin::Header Menu Wrapper-->
								<div class="header-navs header-navs-left" id="kt_header_navs">
									<!--begin::Tab Navs(for tablet and mobile modes)-->
									<!-- <ul class="header-tabs p-5 p-lg-0 d-flex d-lg-none nav nav-bold nav-tabs" role="tablist">
									
										<li class="nav-item mr-2">
											<a href="#" class="nav-link btn btn-clean active" data-toggle="tab" data-target="#kt_header_tab_1" role="tab">Home</a>
										</li>
									
										<li class="nav-item mr-2">
											<a href="#" class="nav-link btn btn-clean" data-toggle="tab" data-target="#kt_header_tab_2" role="tab">Reports</a>
										</li>
									
										<li class="nav-item mr-2">
											<a href="#" class="nav-link btn btn-clean" data-toggle="tab" data-target="#kt_header_tab_2" role="tab">Orders</a>
										</li>
										
										<li class="nav-item mr-2">
											<a href="#" class="nav-link btn btn-clean" data-toggle="tab" data-target="#kt_header_tab_2" role="tab">Help Ceter</a>
										</li>
									
									</ul> -->
									<!--begin::Tab Navs-->
									<!--begin::Tab Content-->
									<div class="tab-content">
										<!--begin::Tab Pane-->
										<div class="tab-pane p-lg-0 show active" id="kt_header_tab_1">
											<!--begin::Menu-->
											<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default flex-column align-items-center w-100 pt-4">
												<!--begin::Nav-->
												<ul class="menu-nav flex-column w-100 px-4">
													<li class="menu-item <?= ($currentPath == "/avisos") ? "menu-item-active" : "" ?>" aria-haspopup="true">
														<a href="{{ url()->route("tramite.nuevo") }}" class="menu-link bg-success w-100">
															<span class="menu-icon"><i class="fas fa-folder-plus text-white"></i></span>
															<span class="menu-text text-white d-flex d-md-none d-xl-flex">Iniciar Trámite</span>
														</a>
													</li>
													<li><hr></li>
													<?php
														foreach(config("layout.menu") as $item){
															echo "
																<li class=\"menu-item ".(($currentPath == $item["path"]) ? "menu-item-active" : "")."\" aria-haspopup=\"false\">
																	<a href=\"".url()->route($item["path"], $item["options"])."\" class=\"menu-link w-100\">";
																		if(isset($item["icon"]))
																			echo "<span class=\"menu-icon\"><i class=\"{$item["icon"]}\"></i></span>";
																		echo "<span class=\"menu-text d-flex d-md-none d-xl-flex\">{$item["name"]}</span>
																	</a>
																</li>
															";
														}
													?>
													{{-- <li class="menu-item <?= ($currentPath == "/dashboard") ? "menu-item-active" : "" ?>" aria-haspopup="true">
														<a href="{{ url()->route("dashboard") }}" class="menu-link w-100">
															<span class="menu-icon"><i class="fas fa-home"></i></span>
															<span class="menu-text d-flex d-md-none d-xl-flex">Inicio</span>
														</a>
													</li>
													<li class="menu-item <?= ($currentPath == "/tramites/pendientes") ? "menu-item-active" : "" ?>" aria-haspopup="true">
														<a href="{{ url()->route("tramites", ["pendientes"]) }}" class="menu-link w-100">
															<span class="menu-icon"><i class="fas fa-clock"></i></span>
															<span class="menu-text d-flex d-md-none d-xl-flex">Trámites Pendientes</span>
														</a>
													</li>
													<li class="menu-item <?= ($currentPath == "/tramites/curso") ? "menu-item-active" : "" ?>" aria-haspopup="true">
														<a href="{{ url()->route("tramites", ["curso"]) }}" class="menu-link w-100">
															<span class="menu-icon"><i class="fas fa-play-circle"></i></span>
															<span class="menu-text d-flex d-md-none d-xl-flex">Trámites en Curso</span>
														</a>
													</li>
													<li class="menu-item <?= ($currentPath == "/tramites/finalizados") ? "menu-item-active" : "" ?>" aria-haspopup="true">
														<a href="{{ url()->route("tramites", ["finalizados"]) }}" class="menu-link w-100">
															<span class="menu-icon"><i class="fas fa-check-circle"></i></span>
															<span class="menu-text d-flex d-md-none d-xl-flex">Trámites Finalizados</span>
														</a>
													</li>
													<li class="menu-item <?= ($currentPath == "/tramites/finalizados") ? "menu-item-active" : "" ?>" aria-haspopup="true">
														<a href="{{ url()->route("tramites", ["por-pagar"]) }}" class="menu-link w-100">
															<span class="menu-icon"><i class="fas fa-money-bill"></i></span>
															<span class="menu-text d-flex d-md-none d-xl-flex">Trámites Por Pagar</span>
														</a>
													</li> --}}
												</ul>
												<!--end::Nav-->
											</div>
											<!--end::Menu-->
										</div>
										<!--begin::Tab Pane-->
									</div>
									<!--end::Tab Content-->
								</div>
								<!--end::Header Menu Wrapper-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Bottom-->
					</div>
					<!--end::Header-->
					<!--begin::Content-->
					<div class="content col-12  @if($empty_layout === false) col-lg-11 col-xl-10 @endif d-flex flex-column flex-column-fluid py-0" @if(isset($background_content)) style="background-color: {{$background_content}}" @endif id="kt_content">
						<!--begin::Entry-->
							<div id="app" class="d-flex flex-column-fluid justify-content-center {{ $fluid_container ? '' : 'container-xl' }}  @if($empty_layout === true) align-items-center @endif">
								<?= view($viewPath, $args) ?>
							</div>
							<!--end::Entry-->
					</div>
					<!--end::Content-->
				</div>
				<!--end::Wrapper-->
				<!--begin::Footer-->
				<div class="footer bg-white py-4 flex-lg-column @if($empty_layout === true) d-none @else d-flex @endif" id="kt_footer">
					<!--begin::Container-->
					<div class="container-xl pt-20 pb-10 px-0 <?= isset($footer["style"]) && $footer["style"] == "minify" ? "d-none" : ""?>">
						<div class="row">
							<a href="tel:911" class="col text-center text-white text-hover-primary" target="_blank">
								<h1><strong>911</strong></h1>
								<h4><strong>Emergencias</strong></h4>
							</a>
							<a href="tel:089" class="col text-center text-white text-hover-primary" target="_blank">
								<h1><strong>089</strong></h1>
								<h4><strong>Denuncia anónima</strong></h4>
							</a>
							<a href="tel:070" class="col text-center text-white text-hover-primary" target="_blank">
								<h1><strong>070</strong></h1>
								<h4><strong>Informatel y Locatel</strong></h4>
							</a>
							<a href="tel:073" class="col text-center text-white text-hover-primary" target="_blank">
								<h1><strong>073</strong></h1>
								<h4><strong>Agua y Drenaje</strong></h4>
							</a>
							<a href="http://aire.nl.gob.mx/" class="col text-center text-white text-hover-primary" target="_blank">
								<h1><strong><span class="fas fa-seedling"></span></strong></h1>
								<h4><strong>Monitoreo ambiental</strong></h4>
							</a>
						</div>
					</div>
					<div class="container-xl pt-10 pb-20 px-0 <?= isset($footer["style"]) && $footer["style"] == "minify" ? "d-none" : ""?>">
						<div class="row d-flex align-items-center">
							<div class="col">
								<img src="{{ assets("images/escudo.svg") }}" alt="">
								<p class="mt-5 ml-15"><a target="_blank" class="text-white text-hover-primary" href="https://www.nl.gob.mx/sobre-tratamiento-datos-personales">Sobre el tratamiento de los datos personales</a></p>
							</div>
							<div class="col">
								<div class="row align-items-center">
									<div class="col text-right border-right">
										<ul class="list-group list-group-flush">
											<li class="list-group-item text-white"><a target="_blank" class="text-white text-hover-primary" href="https://www.nl.gob.mx/gobierno">GOBIERNO</a></li>
											<li class="list-group-item text-white"><a target="_blank" class="text-white text-hover-primary" href="https://www.nl.gob.mx/tramites-y-servicios">TRÁMITES Y SERVICIOS</a></li>
											<li class="list-group-item text-white"><a target="_blank" class="text-white text-hover-primary" href="https://www.nl.gob.mx/actualidad">ACTUALIDAD</a></li>
											<li class="list-group-item text-white"><a target="_blank" class="text-white text-hover-primary" href="https://www.nl.gob.mx/transparencia">TRANSPARENCIA</a></li>
										</ul>
									</div>
									<div class="col">
										<ul class="list-group list-group-flush">
											<li class="list-group-item text-white">
												<a target="_blank" class="text-white text-hover-primary" href="https://www.facebook.com/gobiernonuevoleon/"><i class="fab fa-facebook-square"></i></a>
												<a target="_blank" class="text-white text-hover-primary" href="https://twitter.com/nuevoleon"><i class="fab fa-twitter"></i></a>
												<a target="_blank" class="text-white text-hover-primary" href="https://www.instagram.com/nuevoleonmx/"><i class="fab fa-instagram"></i></a>
												<a target="_blank" class="text-white text-hover-primary" href="https://www.youtube.com/user/GobiernoNuevoLeon"><i class="fab fa-youtube"></i></a>
												<a target="_blank" class="text-white text-hover-primary" href="https://t.me/gobnl"><i class="fab fa-telegram-plane"></i></a>
											</li>
											<li class="list-group-item text-white"><a target="_blank" class="text-white text-hover-primary" href="https://www.nl.gob.mx/campanas/codigo-de-etica-del-gobierno-del-estado-de-nuevo-leon">CÓDIGO DE ÉTICA</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="container d-flex flex-column flex-md-row align-items-center justify-content-center">
						<!--begin::Copyright-->
						<div class="order-2 order-md-1">
							<span class="font-weight-bold mr-2"><?= date("Y")?> © Todos los Derechos Reservados</span>
							<span class="ml-2 mr-2">|</span>
							<a href="{{ getenv("APP_URL") }}" target="_blank" class="text-white text-hover-secondary">Gobierno del Estado de Nuevo León</a>
						</div>
						<!--end::Copyright-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Footer-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Main-->
		<!-- begin::User Panel-->
		<!--============= celular ==============-->
		<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
			<!--begin::Header-->
			<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
				<div class="col-10">
					<h3 class="font-weight-bold m-0">Perfil de Usuario
				</div>
				<div class="col-2">
					<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
						<i class="ki ki-close icon-xs text-muted"></i>
					</a>
				</div>
			</div>
			<!--end::Header-->
			<!--begin::Content-->
			<div class="offcanvas-content pr-5 mr-n5">
				<!--begin::Header-->
				<div class="d-flex align-items-center mt-5">
					<div class="symbol symbol-100 mr-5">
						{{-- <div class="symbol-label" style="background-image:url('media/users/300_21.jpg')"></div> --}}
						<span class="symbol-label font-size-h2 font-weight-bold bg-dark-o-30">{{ $user ? $user->name[0].$user->fathers_surname[0] : "" }}</span>
						<i class="symbol-badge bg-success"></i>
					</div>
					<div class="d-flex flex-column">
						<a href="{{ url()->route("perfil") }}" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ $user ? $user->name : "" }} {{ $user ? $user->fathers_surname : "" }} </a>
						<div class="text-muted mt-1">{{ $user ? $user->role->description : '' }} -- {{ isset($user->notary) ?  "Notaría #" .  $user->notary->notary_number : ''}}</div>
						<div class="navi mt-2">
							<a href="mailto:{{ $user ? $user->email : "" }}" class="navi-item">
								<span class="navi-link p-0 pb-2">
									<span class="navi-icon mr-1">
										<span class="svg-icon svg-icon-lg svg-icon-primary">
											<!--begin::Svg Icon | path:media/svg/icons/Communication/Mail-notification.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000" />
													<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</span>
									<span class="navi-text text-muted text-hover-primary text-truncate col-8 pl-0">{{ $user ? $user->email : "" }}</span>
								</span>
							</a>
							<div style="display:block" >
										<span class="text-muted text-hover-primary text-truncate" style="font-size: 11px;">{{ $user ? "RFC: " . $user->rfc : "" }}</span>
									</div>
									<div style="display:block">
										<span class="text-muted text-hover-primary text-truncate" style="font-size: 11px;">{{ $user ? "CURP: " . $user->curp : "" }}</span>
							</div>
							<!-- <a href="{{ url()->route("logout") }}" class="btn btn-sm btn-light-danger font-weight-bolder text-danger text-hover-white py-2 px-5">Cerrar Sesión</a> -->
						</div>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Separator-->
				<div class="separator separator-dashed mt-8 mb-5"></div>
				<!--end::Separator-->
				<!--begin::Nav-->
				<div class="navi navi-spacer-x-0 p-0">
					<!--begin::Item-->
					<a href="{{ url()->route("dashboard") }}" class="navi-item pt-5">
					<span style="float:left" class="svg-icon svg-icon-xl pt-3 pr-5">
						<i class="flaticon2-architecture-and-city"></i>
					</span>
						<div class="navi-link">
							{{-- <div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<!--begin::Svg Icon | path:media/svg/icons/General/Notification2.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div> --}}
							<div class="navi-text">
								<div class="text-uppercase font-weight-bold"><strong>Inicio</strong></div>
								<!-- <div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div> -->
							</div>
						</div>
					</a>
					<!--end:Item-->
					<a href="{{ url()->route("perfil") }}" class="navi-item pt-5">
					<span style="float:left" class="svg-icon svg-icon-xl pt-3 pr-5">
						<i class="flaticon2-user-outline-symbol"></i>
					</span>
						<div class="navi-link">
							{{-- <div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<!--begin::Svg Icon | path:media/svg/icons/General/Notification2.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div> --}}
							<div class="navi-text">
								<div class="text-uppercase font-weight-bold"><strong>perfil</strong></div>
								<!-- <div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div> -->
							</div>
						</div>
					</a>
					<!--end:Item-->
					<a href="{{ url()->route("tramite.cart") }}" class="navi-item pt-5">
					<span style="float:left" class="svg-icon svg-icon-xl pt-3 pr-5">
						<i class="flaticon2-shopping-cart-1"></i>
						<span id="totalTramitesCarrito"  style="padding: 5px !important; vertical-align: super !important" class="badge badge-danger">{{ session()->get("tramites") }}</span>
					</span>
						<div class="navi-link">
							{{-- <div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
											</g>
										</svg>
									</span>
								</div>
							</div> --}}
							<div class="navi-text">
								<div class="text-uppercase font-weight-bold"><strong>Carrito</strong></div>
								<!-- <div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div> -->
							</div>
						</div>
					</a>
					<!--end:Item-->
					<a href="{{ url()->route("pendiente-firma") }}" class="navi-item pt-5">
					<span style="float:left" class="svg-icon svg-icon-xl pt-3 pr-5">
						<i class="flaticon2-pen"></i>
						<span id="totalTramitesFirma" style="padding: 5px !important; vertical-align: super !important"  class="badge badge-danger">{{ session()->get("tramitesFirma") }}</span>
					</span>
						<div class="navi-link">
							{{-- <div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<!--begin::Svg Icon | path:media/svg/icons/General/Notification2.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div> --}}
							<div class="navi-text">
								<div class="text-uppercase font-weight-bold"><strong>Firma</strong></div>
								<!-- <div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div> -->
							</div>
						</div>
					</a>
					
					
				</div>
				<!--end::Nav-->
				{{-- <!--begin::Separator-->
				<div class="separator separator-dashed my-7"></div>
				<!--end::Separator-->
				<!--begin::Notifications-->
				<div>
					<!--begin:Heading-->
					<h5 class="mb-5">Recent Notifications</h5>
					<!--end:Heading-->
					<!--begin::Item-->
					<div class="d-flex align-items-center bg-light-warning rounded p-5 gutter-b">
						<span class="svg-icon svg-icon-warning mr-5">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:media/svg/icons/Home/Library.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
										<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</span>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Another purpose persuade</a>
							<span class="text-muted font-size-sm">Due in 2 Days</span>
						</div>
						<span class="font-weight-bolder text-warning py-1 font-size-lg">+28%</span>
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-center bg-light-success rounded p-5 gutter-b">
						<span class="svg-icon svg-icon-success mr-5">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:media/svg/icons/Communication/Write.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
										<path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</span>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Would be to people</a>
							<span class="text-muted font-size-sm">Due in 2 Days</span>
						</div>
						<span class="font-weight-bolder text-success py-1 font-size-lg">+50%</span>
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-center bg-light-danger rounded p-5 gutter-b">
						<span class="svg-icon svg-icon-danger mr-5">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:media/svg/icons/Communication/Group-chat.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000" />
										<path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</span>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<a href="#" class="font-weight-normel text-dark-75 text-hover-primary font-size-lg mb-1">Purpose would be to persuade</a>
							<span class="text-muted font-size-sm">Due in 2 Days</span>
						</div>
						<span class="font-weight-bolder text-danger py-1 font-size-lg">-27%</span>
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-center bg-light-info rounded p-5">
						<span class="svg-icon svg-icon-info mr-5">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:media/svg/icons/General/Attachment2.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path d="M11.7573593,15.2426407 L8.75735931,15.2426407 C8.20507456,15.2426407 7.75735931,15.6903559 7.75735931,16.2426407 C7.75735931,16.7949254 8.20507456,17.2426407 8.75735931,17.2426407 L11.7573593,17.2426407 L11.7573593,18.2426407 C11.7573593,19.3472102 10.8619288,20.2426407 9.75735931,20.2426407 L5.75735931,20.2426407 C4.65278981,20.2426407 3.75735931,19.3472102 3.75735931,18.2426407 L3.75735931,14.2426407 C3.75735931,13.1380712 4.65278981,12.2426407 5.75735931,12.2426407 L9.75735931,12.2426407 C10.8619288,12.2426407 11.7573593,13.1380712 11.7573593,14.2426407 L11.7573593,15.2426407 Z" fill="#000000" opacity="0.3" transform="translate(7.757359, 16.242641) rotate(-45.000000) translate(-7.757359, -16.242641)" />
										<path d="M12.2426407,8.75735931 L15.2426407,8.75735931 C15.7949254,8.75735931 16.2426407,8.30964406 16.2426407,7.75735931 C16.2426407,7.20507456 15.7949254,6.75735931 15.2426407,6.75735931 L12.2426407,6.75735931 L12.2426407,5.75735931 C12.2426407,4.65278981 13.1380712,3.75735931 14.2426407,3.75735931 L18.2426407,3.75735931 C19.3472102,3.75735931 20.2426407,4.65278981 20.2426407,5.75735931 L20.2426407,9.75735931 C20.2426407,10.8619288 19.3472102,11.7573593 18.2426407,11.7573593 L14.2426407,11.7573593 C13.1380712,11.7573593 12.2426407,10.8619288 12.2426407,9.75735931 L12.2426407,8.75735931 Z" fill="#000000" transform="translate(16.242641, 7.757359) rotate(-45.000000) translate(-16.242641, -7.757359)" />
										<path d="M5.89339828,3.42893219 C6.44568303,3.42893219 6.89339828,3.87664744 6.89339828,4.42893219 L6.89339828,6.42893219 C6.89339828,6.98121694 6.44568303,7.42893219 5.89339828,7.42893219 C5.34111353,7.42893219 4.89339828,6.98121694 4.89339828,6.42893219 L4.89339828,4.42893219 C4.89339828,3.87664744 5.34111353,3.42893219 5.89339828,3.42893219 Z M11.4289322,5.13603897 C11.8194565,5.52656326 11.8194565,6.15972824 11.4289322,6.55025253 L10.0147186,7.96446609 C9.62419433,8.35499039 8.99102936,8.35499039 8.60050506,7.96446609 C8.20998077,7.5739418 8.20998077,6.94077682 8.60050506,6.55025253 L10.0147186,5.13603897 C10.4052429,4.74551468 11.0384079,4.74551468 11.4289322,5.13603897 Z M0.600505063,5.13603897 C0.991029355,4.74551468 1.62419433,4.74551468 2.01471863,5.13603897 L3.42893219,6.55025253 C3.81945648,6.94077682 3.81945648,7.5739418 3.42893219,7.96446609 C3.0384079,8.35499039 2.40524292,8.35499039 2.01471863,7.96446609 L0.600505063,6.55025253 C0.209980772,6.15972824 0.209980772,5.52656326 0.600505063,5.13603897 Z" fill="#000000" opacity="0.3" transform="translate(6.014719, 5.843146) rotate(-45.000000) translate(-6.014719, -5.843146)" />
										<path d="M17.9142136,15.4497475 C18.4664983,15.4497475 18.9142136,15.8974627 18.9142136,16.4497475 L18.9142136,18.4497475 C18.9142136,19.0020322 18.4664983,19.4497475 17.9142136,19.4497475 C17.3619288,19.4497475 16.9142136,19.0020322 16.9142136,18.4497475 L16.9142136,16.4497475 C16.9142136,15.8974627 17.3619288,15.4497475 17.9142136,15.4497475 Z M23.4497475,17.1568542 C23.8402718,17.5473785 23.8402718,18.1805435 23.4497475,18.5710678 L22.0355339,19.9852814 C21.6450096,20.3758057 21.0118446,20.3758057 20.6213203,19.9852814 C20.2307961,19.5947571 20.2307961,18.9615921 20.6213203,18.5710678 L22.0355339,17.1568542 C22.4260582,16.76633 23.0592232,16.76633 23.4497475,17.1568542 Z M12.6213203,17.1568542 C13.0118446,16.76633 13.6450096,16.76633 14.0355339,17.1568542 L15.4497475,18.5710678 C15.8402718,18.9615921 15.8402718,19.5947571 15.4497475,19.9852814 C15.0592232,20.3758057 14.4260582,20.3758057 14.0355339,19.9852814 L12.6213203,18.5710678 C12.2307961,18.1805435 12.2307961,17.5473785 12.6213203,17.1568542 Z" fill="#000000" opacity="0.3" transform="translate(18.035534, 17.863961) scale(1, -1) rotate(45.000000) translate(-18.035534, -17.863961)" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</span>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<a href="#" class="font-weight-normel text-dark-75 text-hover-primary font-size-lg mb-1">The best product</a>
							<span class="text-muted font-size-sm">Due in 2 Days</span>
						</div>
						<span class="font-weight-bolder text-info py-1 font-size-lg">+8%</span>
					</div>
					<!--end::Item-->
				</div>
				<!--end::Notifications--> --}}
			</div>
			<div style="width:100%; height:100px; background-color: red; position:abosulite; bottom: 0;">  <a href="{{ url()->route("logout") }}" class="text-center" style="color:white !important;">Cerrar Sesión</a>  </div>
			<!--end::Content-->
		</div>
		<!-- Desktop -->
		<div id="kt_quick_user2" class="offcanvas offcanvas-right p-10">
			<!--begin::Header-->
			<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
				<h3 class="font-weight-bold m-0">Perfil de Usuario 
				<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
					<i class="ki ki-close icon-xs text-muted"></i>
				</a>
			</div>
			<!--end::Header-->
			<!--begin::Content-->
			<div class="offcanvas-content pr-5 mr-n5">
				<!--begin::Header-->
				<div class="d-flex align-items-center mt-5">
					<div class="symbol symbol-100 mr-5">
						{{-- <div class="symbol-label" style="background-image:url('media/users/300_21.jpg')"></div> --}}
						<span class="symbol-label font-size-h2 font-weight-bold bg-dark-o-30">{{ $user ? $user->name[0].$user->fathers_surname[0] : "" }}</span>
						<i class="symbol-badge bg-success"></i>
					</div>
					<div class="d-flex flex-column">
						<a href="{{ url()->route("perfil") }}" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ $user ? $user->name : "" }} {{ $user ? $user->fathers_surname : "" }} </a>
						<div class="text-muted mt-1">{{ $user ? $user->role->description : '' }}</div>
						@if ($user && isset($user->notary))
							<div class="text-muted">{{$user->notary ? "Notaría #" : ''}}{{ $user->notary->notary_number ?? '' }}</div>
						@endif
						<div class="navi mt-2">
							<a href="mailto:{{ $user ? $user->email : "" }}" class="navi-item">
								<span class="navi-link p-0 pb-2">
									<span class="navi-icon mr-1">
										<span class="svg-icon svg-icon-lg svg-icon-primary">
											<!--begin::Svg Icon | path:media/svg/icons/Communication/Mail-notification.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000" />
													<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</span>
									<span class="navi-text text-muted text-hover-primary text-truncate col-8 pl-0">{{ $user ? $user->email : "" }}</span>
								</span>
							</a>
							<a href="{{ url()->route("logout") }}" class="btn btn-sm btn-light-danger font-weight-bolder text-danger text-hover-white py-2 px-5">Cerrar Sesión</a>
						</div>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Separator-->
				<div class="separator separator-dashed mt-8 mb-5"></div>
				<!--end::Separator-->
				<!--begin::Nav-->
				<div class="navi navi-spacer-x-0 p-0">
					<!--begin::Item-->
					<a href="{{ url()->route("dashboard") }}" class="navi-item">
					<span style="float:left" class="svg-icon svg-icon-xl pt-3 pr-5">
						<i class="flaticon2-architecture-and-city"></i>
					</span>
						<div class="navi-link">
							{{-- <div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<!--begin::Svg Icon | path:media/svg/icons/General/Notification2.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div> --}}
							<div class="navi-text">
								<div class="text-uppercase font-weight-bold"><strong>Inicio</strong></div>
								<!-- <div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div> -->
							</div>
						</div>
					</a>
					<!--end:Item-->
					<a href="{{ url()->route("perfil") }}" class="navi-item">
					<span style="float:left" class="svg-icon svg-icon-xl pt-3 pr-5">
						<i class="flaticon2-user-outline-symbol"></i>
					</span>
						<div class="navi-link">
							{{-- <div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<!--begin::Svg Icon | path:media/svg/icons/General/Notification2.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div> --}}
							<div class="navi-text">
								<div class="text-uppercase font-weight-bold"><strong>perfil</strong></div>
								<!-- <div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div> -->
							</div>
						</div>
					</a>
					<!--end:Item-->
					<a href="{{ url()->route("tramite.cart") }}" class="navi-item">
					<span style="float:left" class="svg-icon svg-icon-xl pt-3 pr-5">
						<i class="flaticon2-shopping-cart-1"></i>
					</span>
						<div class="navi-link">
							{{-- <div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
											</g>
										</svg>
									</span>
								</div>
							</div> --}}
							<div class="navi-text">
								<div class="text-uppercase font-weight-bold"><strong>Carrito</strong></div>
								<!-- <div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div> -->
							</div>
						</div>
					</a>
					<!--end:Item-->
					<a href="{{ url()->route("pendiente-firma") }}" class="navi-item">
					<span style="float:left" class="svg-icon svg-icon-xl pt-3 pr-5">
						<i class="flaticon2-pen"></i>
					</span>
						<div class="navi-link">
							{{-- <div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<!--begin::Svg Icon | path:media/svg/icons/General/Notification2.svg-->
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</div>
							</div> --}}
							<div class="navi-text">
								<div class="text-uppercase font-weight-bold"><strong>Firma</strong></div>
								<!-- <div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div> -->
							</div>
						</div>
					</a>
					<!--end:Item-->
				</div>
				<!--end::Nav-->
				{{-- <!--begin::Separator-->
				<div class="separator separator-dashed my-7"></div>
				<!--end::Separator-->
				<!--begin::Notifications-->
				<div>
					<!--begin:Heading-->
					<h5 class="mb-5">Recent Notifications</h5>
					<!--end:Heading-->
					<!--begin::Item-->
					<div class="d-flex align-items-center bg-light-warning rounded p-5 gutter-b">
						<span class="svg-icon svg-icon-warning mr-5">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:media/svg/icons/Home/Library.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
										<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</span>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Another purpose persuade</a>
							<span class="text-muted font-size-sm">Due in 2 Days</span>
						</div>
						<span class="font-weight-bolder text-warning py-1 font-size-lg">+28%</span>
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-center bg-light-success rounded p-5 gutter-b">
						<span class="svg-icon svg-icon-success mr-5">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:media/svg/icons/Communication/Write.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
										<path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</span>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Would be to people</a>
							<span class="text-muted font-size-sm">Due in 2 Days</span>
						</div>
						<span class="font-weight-bolder text-success py-1 font-size-lg">+50%</span>
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-center bg-light-danger rounded p-5 gutter-b">
						<span class="svg-icon svg-icon-danger mr-5">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:media/svg/icons/Communication/Group-chat.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000" />
										<path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</span>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<a href="#" class="font-weight-normel text-dark-75 text-hover-primary font-size-lg mb-1">Purpose would be to persuade</a>
							<span class="text-muted font-size-sm">Due in 2 Days</span>
						</div>
						<span class="font-weight-bolder text-danger py-1 font-size-lg">-27%</span>
					</div>
					<!--end::Item-->
					<!--begin::Item-->
					<div class="d-flex align-items-center bg-light-info rounded p-5">
						<span class="svg-icon svg-icon-info mr-5">
							<span class="svg-icon svg-icon-lg">
								<!--begin::Svg Icon | path:media/svg/icons/General/Attachment2.svg-->
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path d="M11.7573593,15.2426407 L8.75735931,15.2426407 C8.20507456,15.2426407 7.75735931,15.6903559 7.75735931,16.2426407 C7.75735931,16.7949254 8.20507456,17.2426407 8.75735931,17.2426407 L11.7573593,17.2426407 L11.7573593,18.2426407 C11.7573593,19.3472102 10.8619288,20.2426407 9.75735931,20.2426407 L5.75735931,20.2426407 C4.65278981,20.2426407 3.75735931,19.3472102 3.75735931,18.2426407 L3.75735931,14.2426407 C3.75735931,13.1380712 4.65278981,12.2426407 5.75735931,12.2426407 L9.75735931,12.2426407 C10.8619288,12.2426407 11.7573593,13.1380712 11.7573593,14.2426407 L11.7573593,15.2426407 Z" fill="#000000" opacity="0.3" transform="translate(7.757359, 16.242641) rotate(-45.000000) translate(-7.757359, -16.242641)" />
										<path d="M12.2426407,8.75735931 L15.2426407,8.75735931 C15.7949254,8.75735931 16.2426407,8.30964406 16.2426407,7.75735931 C16.2426407,7.20507456 15.7949254,6.75735931 15.2426407,6.75735931 L12.2426407,6.75735931 L12.2426407,5.75735931 C12.2426407,4.65278981 13.1380712,3.75735931 14.2426407,3.75735931 L18.2426407,3.75735931 C19.3472102,3.75735931 20.2426407,4.65278981 20.2426407,5.75735931 L20.2426407,9.75735931 C20.2426407,10.8619288 19.3472102,11.7573593 18.2426407,11.7573593 L14.2426407,11.7573593 C13.1380712,11.7573593 12.2426407,10.8619288 12.2426407,9.75735931 L12.2426407,8.75735931 Z" fill="#000000" transform="translate(16.242641, 7.757359) rotate(-45.000000) translate(-16.242641, -7.757359)" />
										<path d="M5.89339828,3.42893219 C6.44568303,3.42893219 6.89339828,3.87664744 6.89339828,4.42893219 L6.89339828,6.42893219 C6.89339828,6.98121694 6.44568303,7.42893219 5.89339828,7.42893219 C5.34111353,7.42893219 4.89339828,6.98121694 4.89339828,6.42893219 L4.89339828,4.42893219 C4.89339828,3.87664744 5.34111353,3.42893219 5.89339828,3.42893219 Z M11.4289322,5.13603897 C11.8194565,5.52656326 11.8194565,6.15972824 11.4289322,6.55025253 L10.0147186,7.96446609 C9.62419433,8.35499039 8.99102936,8.35499039 8.60050506,7.96446609 C8.20998077,7.5739418 8.20998077,6.94077682 8.60050506,6.55025253 L10.0147186,5.13603897 C10.4052429,4.74551468 11.0384079,4.74551468 11.4289322,5.13603897 Z M0.600505063,5.13603897 C0.991029355,4.74551468 1.62419433,4.74551468 2.01471863,5.13603897 L3.42893219,6.55025253 C3.81945648,6.94077682 3.81945648,7.5739418 3.42893219,7.96446609 C3.0384079,8.35499039 2.40524292,8.35499039 2.01471863,7.96446609 L0.600505063,6.55025253 C0.209980772,6.15972824 0.209980772,5.52656326 0.600505063,5.13603897 Z" fill="#000000" opacity="0.3" transform="translate(6.014719, 5.843146) rotate(-45.000000) translate(-6.014719, -5.843146)" />
										<path d="M17.9142136,15.4497475 C18.4664983,15.4497475 18.9142136,15.8974627 18.9142136,16.4497475 L18.9142136,18.4497475 C18.9142136,19.0020322 18.4664983,19.4497475 17.9142136,19.4497475 C17.3619288,19.4497475 16.9142136,19.0020322 16.9142136,18.4497475 L16.9142136,16.4497475 C16.9142136,15.8974627 17.3619288,15.4497475 17.9142136,15.4497475 Z M23.4497475,17.1568542 C23.8402718,17.5473785 23.8402718,18.1805435 23.4497475,18.5710678 L22.0355339,19.9852814 C21.6450096,20.3758057 21.0118446,20.3758057 20.6213203,19.9852814 C20.2307961,19.5947571 20.2307961,18.9615921 20.6213203,18.5710678 L22.0355339,17.1568542 C22.4260582,16.76633 23.0592232,16.76633 23.4497475,17.1568542 Z M12.6213203,17.1568542 C13.0118446,16.76633 13.6450096,16.76633 14.0355339,17.1568542 L15.4497475,18.5710678 C15.8402718,18.9615921 15.8402718,19.5947571 15.4497475,19.9852814 C15.0592232,20.3758057 14.4260582,20.3758057 14.0355339,19.9852814 L12.6213203,18.5710678 C12.2307961,18.1805435 12.2307961,17.5473785 12.6213203,17.1568542 Z" fill="#000000" opacity="0.3" transform="translate(18.035534, 17.863961) scale(1, -1) rotate(45.000000) translate(-18.035534, -17.863961)" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</span>
						<div class="d-flex flex-column flex-grow-1 mr-2">
							<a href="#" class="font-weight-normel text-dark-75 text-hover-primary font-size-lg mb-1">The best product</a>
							<span class="text-muted font-size-sm">Due in 2 Days</span>
						</div>
						<span class="font-weight-bolder text-info py-1 font-size-lg">+8%</span>
					</div>
					<!--end::Item-->
				</div>
				<!--end::Notifications--> --}}
			</div>
			<!--end::Content-->
		</div>
			<div id="iehelper" class="ie-sidenav p-10" >
				<!--begin::Header-->
				<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
					<h3 class="font-weight-bold m-0">Perfil de Usuario
					<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close" onclick="destroyBackground()" >
						<i class="ki ki-close icon-xs text-muted"></i>
					</a>
				</div>
				<!--end::Header-->
				<!--begin::Content-->
				<div class="offcanvas-content pr-5 mr-n5">
					<!--begin::Header-->
					<div class="d-flex align-items-center mt-5">
						<div class="symbol symbol-100 mr-5">
							{{-- <div class="symbol-label" style="background-image:url('media/users/300_21.jpg')"></div> --}}
							<span class="symbol-label font-size-h2 font-weight-bold bg-dark-o-30">{{ $user ? $user->name[0].$user->fathers_surname[0] : "" }}</span>
							<i class="symbol-badge bg-success"></i>
						</div>
						<div class="d-flex flex-column">
							<a href="{{ url()->route("perfil") }}" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ $user ? $user->name : "" }} {{ $user ? $user->fathers_surname : "" }} </a>
							{{-- <div class="text-muted mt-1">Frontend Development</div> --}}
							<div class="navi mt-2">
								<a href="mailto:{{ $user ? $user->email : "" }}" class="navi-item">
									<span class="navi-link p-0 pb-2">
										<span class="navi-icon mr-1">
											<span class="svg-icon svg-icon-lg svg-icon-primary">
												<!--begin::Svg Icon | path:media/svg/icons/Communication/Mail-notification.svg-->
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000" />
														<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5" />
													</g>
												</svg>
												<!--end::Svg Icon-->
											</span>
										</span>
										<span class="navi-text text-muted text-hover-primary text-truncate col-8 pl-0">{{ $user ? $user->email : "" }}</span>
									</span>
								</a>
								<a href="{{ url()->route("logout") }}" class="btn btn-sm btn-light-danger font-weight-bolder text-danger text-hover-white py-2 px-5">Cerrar Sesión</a>
							</div>
						</div>
					</div>
					<!--end::Header-->
					<!--begin::Separator-->
					<div class="separator separator-dashed mt-8 mb-5"></div>
					<!--end::Separator-->
					<!--begin::Nav-->
					<div class="navi navi-spacer-x-0 p-0">
						<!--begin::Item-->
						<a href="{{ url()->route("dashboard") }}" class="navi-item">
							<div class="navi-link">
								{{-- <div class="symbol symbol-40 bg-light mr-3">
									<div class="symbol-label">
										<span class="svg-icon svg-icon-md svg-icon-success">
											<!--begin::Svg Icon | path:media/svg/icons/General/Notification2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
													<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</div>
								</div> --}}
								<div class="navi-text">
									<div class="text-uppercase font-weight-bold"><strong>Inicio</strong></div>
									<div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
								</div>
							</div>
						</a>
						<!--end:Item-->
						<!--begin::Item-->
						{{-- <a href="{{ url()->route("tramites", ["pendientes"]) }}" class="navi-item">
							<div class="navi-link">
								<div class="symbol symbol-40 bg-light mr-3">
									<div class="symbol-label">
										<span class="svg-icon svg-icon-md svg-icon-warning">
											<!--begin::Svg Icon | path:media/svg/icons/Shopping/Chart-bar1.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5" />
													<rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5" />
													<path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero" />
													<rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</div>
								</div>
								<div class="navi-text">
									<div class="text-uppercase font-weight-bold"><strong>Trámites Pendientes</strong></div>
									<div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
								</div>
							</div>
						</a> --}}
						<!--end:Item-->
						<!--begin::Item-->
						{{-- <a href="{{ url()->route("tramites", ["curso"]) }}" class="navi-item">
							<div class="navi-link">
								<div class="symbol symbol-40 bg-light mr-3">
									<div class="symbol-label">
										<span class="svg-icon svg-icon-md svg-icon-danger">
											<!--begin::Svg Icon | path:media/svg/icons/Files/Selected-file.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<path d="M4.85714286,1 L11.7364114,1 C12.0910962,1 12.4343066,1.12568431 12.7051108,1.35473959 L17.4686994,5.3839416 C17.8056532,5.66894833 18,6.08787823 18,6.52920201 L18,19.0833333 C18,20.8738751 17.9795521,21 16.1428571,21 L4.85714286,21 C3.02044787,21 3,20.8738751 3,19.0833333 L3,2.91666667 C3,1.12612489 3.02044787,1 4.85714286,1 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<path d="M6.85714286,3 L14.7364114,3 C15.0910962,3 15.4343066,3.12568431 15.7051108,3.35473959 L20.4686994,7.3839416 C20.8056532,7.66894833 21,8.08787823 21,8.52920201 L21,21.0833333 C21,22.8738751 20.9795521,23 19.1428571,23 L6.85714286,23 C5.02044787,23 5,22.8738751 5,21.0833333 L5,4.91666667 C5,3.12612489 5.02044787,3 6.85714286,3 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fill-rule="nonzero" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</div>
								</div>
								<div class="navi-text">
									<div class="text-uppercase font-weight-bold"><strong>Trámites en Curso</strong></div>
									<div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
								</div>
							</div>
						</a> --}}
						<!--end:Item-->
						<!--begin::Item-->
						{{-- <a href="{{ url()->route("tramites", ["finalizados"]) }}" class="navi-item">
							<div class="navi-link">
								<div class="symbol symbol-40 bg-light mr-3">
									<div class="symbol-label">
										<span class="svg-icon svg-icon-md svg-icon-primary">
											<!--begin::Svg Icon | path:media/svg/icons/Communication/Mail-opened.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z" fill="#000000" opacity="0.3" />
													<path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z" fill="#000000" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</div>
								</div>
								<div class="navi-text">
									<div class="text-uppercase font-weight-bold"><strong>Trámites Finalizados</strong></div>
									<div class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.</div>
								</div>
							</div>
						</a> --}}
						<!--end:Item-->
					</div>
					<!--end::Nav-->
					{{-- <!--begin::Separator-->
					<div class="separator separator-dashed my-7"></div>
					<!--end::Separator-->
					<!--begin::Notifications-->
					<div>
						<!--begin:Heading-->
						<h5 class="mb-5">Recent Notifications</h5>
						<!--end:Heading-->
						<!--begin::Item-->
						<div class="d-flex align-items-center bg-light-warning rounded p-5 gutter-b">
							<span class="svg-icon svg-icon-warning mr-5">
								<span class="svg-icon svg-icon-lg">
									<!--begin::Svg Icon | path:media/svg/icons/Home/Library.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000" />
											<rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)" x="16.3255682" y="2.94551858" width="3" height="18" rx="1" />
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</span>
							<div class="d-flex flex-column flex-grow-1 mr-2">
								<a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Another purpose persuade</a>
								<span class="text-muted font-size-sm">Due in 2 Days</span>
							</div>
							<span class="font-weight-bolder text-warning py-1 font-size-lg">+28%</span>
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="d-flex align-items-center bg-light-success rounded p-5 gutter-b">
							<span class="svg-icon svg-icon-success mr-5">
								<span class="svg-icon svg-icon-lg">
									<!--begin::Svg Icon | path:media/svg/icons/Communication/Write.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
											<path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</span>
							<div class="d-flex flex-column flex-grow-1 mr-2">
								<a href="#" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Would be to people</a>
								<span class="text-muted font-size-sm">Due in 2 Days</span>
							</div>
							<span class="font-weight-bolder text-success py-1 font-size-lg">+50%</span>
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="d-flex align-items-center bg-light-danger rounded p-5 gutter-b">
							<span class="svg-icon svg-icon-danger mr-5">
								<span class="svg-icon svg-icon-lg">
									<!--begin::Svg Icon | path:media/svg/icons/Communication/Group-chat.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000" />
											<path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3" />
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</span>
							<div class="d-flex flex-column flex-grow-1 mr-2">
								<a href="#" class="font-weight-normel text-dark-75 text-hover-primary font-size-lg mb-1">Purpose would be to persuade</a>
								<span class="text-muted font-size-sm">Due in 2 Days</span>
							</div>
							<span class="font-weight-bolder text-danger py-1 font-size-lg">-27%</span>
						</div>
						<!--end::Item-->
						<!--begin::Item-->
						<div class="d-flex align-items-center bg-light-info rounded p-5">
							<span class="svg-icon svg-icon-info mr-5">
								<span class="svg-icon svg-icon-lg">
									<!--begin::Svg Icon | path:media/svg/icons/General/Attachment2.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path d="M11.7573593,15.2426407 L8.75735931,15.2426407 C8.20507456,15.2426407 7.75735931,15.6903559 7.75735931,16.2426407 C7.75735931,16.7949254 8.20507456,17.2426407 8.75735931,17.2426407 L11.7573593,17.2426407 L11.7573593,18.2426407 C11.7573593,19.3472102 10.8619288,20.2426407 9.75735931,20.2426407 L5.75735931,20.2426407 C4.65278981,20.2426407 3.75735931,19.3472102 3.75735931,18.2426407 L3.75735931,14.2426407 C3.75735931,13.1380712 4.65278981,12.2426407 5.75735931,12.2426407 L9.75735931,12.2426407 C10.8619288,12.2426407 11.7573593,13.1380712 11.7573593,14.2426407 L11.7573593,15.2426407 Z" fill="#000000" opacity="0.3" transform="translate(7.757359, 16.242641) rotate(-45.000000) translate(-7.757359, -16.242641)" />
											<path d="M12.2426407,8.75735931 L15.2426407,8.75735931 C15.7949254,8.75735931 16.2426407,8.30964406 16.2426407,7.75735931 C16.2426407,7.20507456 15.7949254,6.75735931 15.2426407,6.75735931 L12.2426407,6.75735931 L12.2426407,5.75735931 C12.2426407,4.65278981 13.1380712,3.75735931 14.2426407,3.75735931 L18.2426407,3.75735931 C19.3472102,3.75735931 20.2426407,4.65278981 20.2426407,5.75735931 L20.2426407,9.75735931 C20.2426407,10.8619288 19.3472102,11.7573593 18.2426407,11.7573593 L14.2426407,11.7573593 C13.1380712,11.7573593 12.2426407,10.8619288 12.2426407,9.75735931 L12.2426407,8.75735931 Z" fill="#000000" transform="translate(16.242641, 7.757359) rotate(-45.000000) translate(-16.242641, -7.757359)" />
											<path d="M5.89339828,3.42893219 C6.44568303,3.42893219 6.89339828,3.87664744 6.89339828,4.42893219 L6.89339828,6.42893219 C6.89339828,6.98121694 6.44568303,7.42893219 5.89339828,7.42893219 C5.34111353,7.42893219 4.89339828,6.98121694 4.89339828,6.42893219 L4.89339828,4.42893219 C4.89339828,3.87664744 5.34111353,3.42893219 5.89339828,3.42893219 Z M11.4289322,5.13603897 C11.8194565,5.52656326 11.8194565,6.15972824 11.4289322,6.55025253 L10.0147186,7.96446609 C9.62419433,8.35499039 8.99102936,8.35499039 8.60050506,7.96446609 C8.20998077,7.5739418 8.20998077,6.94077682 8.60050506,6.55025253 L10.0147186,5.13603897 C10.4052429,4.74551468 11.0384079,4.74551468 11.4289322,5.13603897 Z M0.600505063,5.13603897 C0.991029355,4.74551468 1.62419433,4.74551468 2.01471863,5.13603897 L3.42893219,6.55025253 C3.81945648,6.94077682 3.81945648,7.5739418 3.42893219,7.96446609 C3.0384079,8.35499039 2.40524292,8.35499039 2.01471863,7.96446609 L0.600505063,6.55025253 C0.209980772,6.15972824 0.209980772,5.52656326 0.600505063,5.13603897 Z" fill="#000000" opacity="0.3" transform="translate(6.014719, 5.843146) rotate(-45.000000) translate(-6.014719, -5.843146)" />
											<path d="M17.9142136,15.4497475 C18.4664983,15.4497475 18.9142136,15.8974627 18.9142136,16.4497475 L18.9142136,18.4497475 C18.9142136,19.0020322 18.4664983,19.4497475 17.9142136,19.4497475 C17.3619288,19.4497475 16.9142136,19.0020322 16.9142136,18.4497475 L16.9142136,16.4497475 C16.9142136,15.8974627 17.3619288,15.4497475 17.9142136,15.4497475 Z M23.4497475,17.1568542 C23.8402718,17.5473785 23.8402718,18.1805435 23.4497475,18.5710678 L22.0355339,19.9852814 C21.6450096,20.3758057 21.0118446,20.3758057 20.6213203,19.9852814 C20.2307961,19.5947571 20.2307961,18.9615921 20.6213203,18.5710678 L22.0355339,17.1568542 C22.4260582,16.76633 23.0592232,16.76633 23.4497475,17.1568542 Z M12.6213203,17.1568542 C13.0118446,16.76633 13.6450096,16.76633 14.0355339,17.1568542 L15.4497475,18.5710678 C15.8402718,18.9615921 15.8402718,19.5947571 15.4497475,19.9852814 C15.0592232,20.3758057 14.4260582,20.3758057 14.0355339,19.9852814 L12.6213203,18.5710678 C12.2307961,18.1805435 12.2307961,17.5473785 12.6213203,17.1568542 Z" fill="#000000" opacity="0.3" transform="translate(18.035534, 17.863961) scale(1, -1) rotate(45.000000) translate(-18.035534, -17.863961)" />
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</span>
							<div class="d-flex flex-column flex-grow-1 mr-2">
								<a href="#" class="font-weight-normel text-dark-75 text-hover-primary font-size-lg mb-1">The best product</a>
								<span class="text-muted font-size-sm">Due in 2 Days</span>
							</div>
							<span class="font-weight-bolder text-info py-1 font-size-lg">+8%</span>
						</div>
						<!--end::Item-->
					</div>
					<!--end::Notifications--> --}}
				</div>
				<!--end::Content-->
			</div>
			<!-- end::User Panel-->
			<!--begin::Quick Cart-->
			{{-- <div id="kt_quick_cart" class="offcanvas offcanvas-right p-10">
				<!--begin::Header-->
				<div class="offcanvas-header d-flex align-items-center justify-content-between pb-7">
					<h4 class="font-weight-bold m-0">Shopping Cart</h4>
					<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_cart_close">
						<i class="ki ki-close icon-xs text-muted"></i>
					</a>
				</div>
				<!--end::Header-->
				<!--begin::Content-->
				<div class="offcanvas-content">
					<!--begin::Wrapper-->
					<div class="offcanvas-wrapper mb-5 scroll-pull">
						<!--begin::Item-->
						<div class="d-flex align-items-center justify-content-between py-8">
							<div class="d-flex flex-column mr-2">
								<a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">iBlender</a>
								<span class="text-muted">The best kitchen gadget in 2020</span>
								<div class="d-flex align-items-center mt-2">
									<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 350</span>
									<span class="text-muted mr-1">for</span>
									<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">5</span>
									<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
										<i class="ki ki-minus icon-xs"></i>
									</a>
									<a href="#" class="btn btn-xs btn-light-success btn-icon">
										<i class="ki ki-plus icon-xs"></i>
									</a>
								</div>
							</div>
							<a href="#" class="symbol symbol-70 flex-shrink-0">
								<img src="media/stock-600x400/img-1.jpg" title="" alt="" />
							</a>
						</div>
						<!--end::Item-->
						<!--begin::Separator-->
						<div class="separator separator-solid"></div>
						<!--end::Separator-->
						<!--begin::Item-->
						<div class="d-flex align-items-center justify-content-between py-8">
							<div class="d-flex flex-column mr-2">
								<a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">SmartCleaner</a>
								<span class="text-muted">Smart tool for cooking</span>
								<div class="d-flex align-items-center mt-2">
									<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 650</span>
									<span class="text-muted mr-1">for</span>
									<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">4</span>
									<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
										<i class="ki ki-minus icon-xs"></i>
									</a>
									<a href="#" class="btn btn-xs btn-light-success btn-icon">
										<i class="ki ki-plus icon-xs"></i>
									</a>
								</div>
							</div>
							<a href="#" class="symbol symbol-70 flex-shrink-0">
								<img src="media/stock-600x400/img-2.jpg" title="" alt="" />
							</a>
						</div>
						<!--end::Item-->
						<!--begin::Separator-->
						<div class="separator separator-solid"></div>
						<!--end::Separator-->
						<!--begin::Item-->
						<div class="d-flex align-items-center justify-content-between py-8">
							<div class="d-flex flex-column mr-2">
								<a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">CameraMax</a>
								<span class="text-muted">Professional camera for edge cutting shots</span>
								<div class="d-flex align-items-center mt-2">
									<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 150</span>
									<span class="text-muted mr-1">for</span>
									<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">3</span>
									<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
										<i class="ki ki-minus icon-xs"></i>
									</a>
									<a href="#" class="btn btn-xs btn-light-success btn-icon">
										<i class="ki ki-plus icon-xs"></i>
									</a>
								</div>
							</div>
							<a href="#" class="symbol symbol-70 flex-shrink-0">
								<img src="media/stock-600x400/img-3.jpg" title="" alt="" />
							</a>
						</div>
						<!--end::Item-->
						<!--begin::Separator-->
						<div class="separator separator-solid"></div>
						<!--end::Separator-->
						<!--begin::Item-->
						<div class="d-flex align-items-center justify-content-between py-8">
							<div class="d-flex flex-column mr-2">
								<a href="#" class="font-weight-bold text-dark text-hover-primary">4D Printer</a>
								<span class="text-muted">Manufactoring unique objects</span>
								<div class="d-flex align-items-center mt-2">
									<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 1450</span>
									<span class="text-muted mr-1">for</span>
									<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">7</span>
									<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
										<i class="ki ki-minus icon-xs"></i>
									</a>
									<a href="#" class="btn btn-xs btn-light-success btn-icon">
										<i class="ki ki-plus icon-xs"></i>
									</a>
								</div>
							</div>
							<a href="#" class="symbol symbol-70 flex-shrink-0">
								<img src="media/stock-600x400/img-4.jpg" title="" alt="" />
							</a>
						</div>
						<!--end::Item-->
						<!--begin::Separator-->
						<div class="separator separator-solid"></div>
						<!--end::Separator-->
						<!--begin::Item-->
						<div class="d-flex align-items-center justify-content-between py-8">
							<div class="d-flex flex-column mr-2">
								<a href="#" class="font-weight-bold text-dark text-hover-primary">MotionWire</a>
								<span class="text-muted">Perfect animation tool</span>
								<div class="d-flex align-items-center mt-2">
									<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 650</span>
									<span class="text-muted mr-1">for</span>
									<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">7</span>
									<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
										<i class="ki ki-minus icon-xs"></i>
									</a>
									<a href="#" class="btn btn-xs btn-light-success btn-icon">
										<i class="ki ki-plus icon-xs"></i>
									</a>
								</div>
							</div>
							<a href="#" class="symbol symbol-70 flex-shrink-0">
								<img src="media/stock-600x400/img-8.jpg" title="" alt="" />
							</a>
						</div>
						<!--end::Item-->
					</div>
					<!--end::Wrapper-->
					<!--begin::Purchase-->
					<div class="offcanvas-footer">
						<div class="d-flex align-items-center justify-content-between mb-4">
							<span class="font-weight-bold text-muted font-size-sm mr-2">Total</span>
							<span class="font-weight-bolder text-dark-50 text-right">$1840.00</span>
						</div>
						<div class="d-flex align-items-center justify-content-between mb-7">
							<span class="font-weight-bold text-muted font-size-sm mr-2">Sub total</span>
							<span class="font-weight-bolder text-primary text-right">$5640.00</span>
						</div>
						<div class="text-right">
							<button type="button" class="btn btn-primary text-weight-bold">Place Order</button>
						</div>
					</div>
					<!--end::Purchase-->
				</div>
				<!--end::Content-->
			</div> --}}
			<!--end::Quick Cart-->
			<!--begin::ScrollToTop-->
			<div id="kt_scrolltop" class="scrolltop">
				<span class="svg-icon">
					<!--begin::Svg Icon | path:media/svg/icons/Navigation/Up-2.svg-->
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<polygon points="0 0 24 0 24 24 0 24" />
							<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
							<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
						</g>
					</svg>
					<!--end::Svg Icon-->
				</span>
			</div>
			<!--end::ScrollToTop-->
			<!--begin::Sticky Toolbar-->
			{{-- <ul class="sticky-toolbar d-none nav flex-column pl-2 pr-2 pt-3 pb-3 mt-4">
				<!--begin::Item-->
				<li class="nav-item mb-2" id="kt_demo_panel_toggle" data-toggle="tooltip" title="Check out more demos" data-placement="right">
					<a class="btn btn-sm btn-icon btn-bg-light btn-text-success btn-hover-success" href="#">
						<i class="flaticon2-drop"></i>
					</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="nav-item mb-2" data-toggle="tooltip" title="Layout Builder" data-placement="left">
					<a class="btn btn-sm btn-icon btn-bg-light btn-text-primary btn-hover-primary" href="https://keenthemes.com/metronic/preview/demo7/builder.html" target="_blank">
						<i class="flaticon2-gear"></i>
					</a>
				</li>
				end::Item
				<!--begin::Item-->
				<li class="nav-item mb-2" data-toggle="tooltip" title="Documentation" data-placement="left">
					<a class="btn btn-sm btn-icon btn-bg-light btn-text-warning btn-hover-warning" href="https://keenthemes.com/metronic/?page=docs" target="_blank">
						<i class="flaticon2-telegram-logo"></i>
					</a>
				</li>
				<!--end::Item-->
				<!--begin::Item-->
				<li class="nav-item" id="kt_sticky_toolbar_chat_toggler" data-toggle="tooltip" title="Chat Example" data-placement="left">
					<a class="btn btn-sm btn-icon btn-bg-light btn-text-danger btn-hover-danger" href="#" data-toggle="modal" data-target="#kt_chat_modal">
						<i class="flaticon2-chat-1"></i>
					</a>
				</li>
				<!--end::Item-->
			</ul> --}}
			<!--end::Sticky Toolbar-->
			{{-- <script>var HOST_URL = "https://keenthemes.com/metronic/tools/preview";</script> --}}
			<!--begin::Global Config(global config for global JS scripts)-->
			<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1200 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#6993FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#F3F6F9", "dark": "#212121" }, "light": { "white": "#ffffff", "primary": "#E1E9FF", "secondary": "#ECF0F3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#212121", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#ECF0F3", "gray-300": "#E5EAEE", "gray-400": "#D6D6E0", "gray-500": "#B5B5C3", "gray-600": "#80808F", "gray-700": "#464E5F", "gray-800": "#1B283F", "gray-900": "#212121" } }, "font-family": "Poppins" };</script>
			<!--end::Global Config-->
			<!--begin::Global Theme Bundle(used by all pages)-->
			<script src="{{ assets("js/scripts.bundle.js") }}"></script>
			<script src="{{ assets("plugins/global/plugins.bundle.js") }}"></script>
			<script src="{{ assets("plugins/custom/prismjs/prismjs.bundle.js") }}"></script>
			<script>
				$(document).ready(() => {
					$('[data-toggle=tooltip]').tooltip()
				})

				function iehelper() {
					var isAtLeastIE11 = !!(navigator.userAgent.match(/Trident/) && !navigator.userAgent.match(/MSIE/));
					if (isAtLeastIE11) {
						document.getElementById('iehelper').style.display ="block";
						var div = document.createElement('div');
						div.style.background = "rgba(0, 0, 0, 0.1)";
						div.style.position ="-ms-page";
						div.style.top ="0px";
						div.style.left ="0px";
						div.style.right ="0px";
						div.style.bottom ="0px";
						div.style.overflow ="hidden";
						div.style.zIndex ="1000";
						div.id = "background-ie";
						document.getElementById('iehelper').appendChild(div);
					}
				};
				function destroyBackground(){
					document.getElementById('background-ie').remove();
				}
			</script>
			<script>
				const APP_URL = '{{ getenv("APP_URL") }}';
				window.user = <?= json_encode(session()->get("user"))?>;
				function redirect(path, _blank=false) {
					let newPath = path;
					if (!(new RegExp('^(http(s)?[:]//)','i')).test(path)) {
						newPath = APP_URL + (path.search(/\//) != 0 ? "/" : "") + path;
					}
					if(!_blank) window.location = newPath;
					else window.open(newPath, '_blank')
				}

				$.ajaxSetup({
					data: {
						'_token': "{{ csrf_token() }}"
					},
					beforeSend: function(xhr, options) {
						if(options.url.search(/http(s)?:\/\//i) < 0)
							options.url = APP_URL + options.url;
					}
				});
			</script>
			<!--end::Global Theme Bundle-->
			<!--begin::Page Vendors(used by this page)-->
			{{-- <script src="plugins/custom/fullcalendar/fullcalendar.bundle.js"></script> --}}
			<!--end::Page Vendors-->
			<!--begin::Page Scripts(used by this page)-->
			<script src="{{ assets("js/pages/widgets.js") }}"></script>
			<!--end::Page Scripts-->
			<?php
				if(isset($script)){
					foreach ($script as $js) {
						preg_match("/http(s)?:\/\//", $js, $matches);
						echo '<script src="'.(!empty($matches) ? $js : assets($js)).'" type="text/javascript"></script>';
					}
				}
			?>
			<!-- dataTable -->
			{{-- <script src="https://unpkg.com/@popperjs/core@2"></script>
			<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
			<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script> --}}
			<script src="{{ assets("js/popper.min.js") }}"></script>
			<script src="{{ assets("js/jquery.dataTables.min.js") }}"></script>
			<script src="{{ assets("js/dataTables.bootstrap4.min.js") }}"></script>

			<script src="{{ assets("js/bundle.js") }}"></script>
		</body>
	<!--end::Body-->
</html>
