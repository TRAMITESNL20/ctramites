<?php
return [
	"args" => [
		"title" => env("APP_NAME") ?? "Gobierno del Estado de Nuevo León",
		"empty_layout" => false,
		"valid_token" => true,
		"fluid_container" => false,
		"footer" => [
			"style" => "minify"
		],
		"notifications" => [
			"total" => 3,
			"read" => 0,
			"items" => [
				[
					"title" => "En este momento <strong>AFIRME</strong> no esta notificando los pagos en línea.",
					"content" => "Si requiere ayuda con algún pago llama al 2020-5555.",
					"read" => false,
					"icon" => "",
					"date" => "2020-08-10 00:00:00"
				],
				[
					"title" => "Utiliza <strong>Banamex y Bancomer</strong> para pagar en línea.",
					"content" => "Si requiere ayuda con algún pago llama al 2020-5555.",
					"read" => false,
					"icon" => "",
					"date" => "2020-08-10 00:00:00"
				],
				[
					"title" => "En este momento AFIRME no esta notificando los pagos en línea.",
					"content" => "",
					"read" => false,
					"icon" => "",
					"date" => "2020-08-10 00:00:00"
				]
			]
		]
	],
	"session_whitelist" => ["login","password/recovery", "password", "password/recovery/(.+)" , "formato-declaracion/(.+)"],
	"menu" => [
		[
			"name" => "Inicio",
			"icon" => "fas fa-home",
			"path" => "dashboard",
			"options" => null
		],
		[
			"name" => "Borradores",
			"icon" => "fas fa-file",
			"path" => "tramites",
			"options" => ["borradores", 80]
		],
		[
			"name" => "Pendiente de pago",
			"icon" => "fas fa-money-bill",
			"path" => "tramites",
			"options" => ["pendiente-de-pago", 99]
		],
		[
			"name" => "Pendiente de pago con referencia",
			"icon" => "fas fa-file-invoice",
			"path" => "tramites",
			"options" => ["pendiente-de-pago-con-referencia", 5]
		],
		[
			"name" => "En espera de atención",
			"icon" => "fas fa-clock",
			"path" => "tramites",
			"options" => ["en-espera-de-atencion", 3]
		],
		[
			"name" => "En curso",
			"icon" => "fas fa-play-circle",
			"path" => "tramites",
			"options" => ["en-curso", 1]
		],
		[
			"name" => "Pendiente de Firmar",
			"icon" => "fas fa-file-signature",
			"path" => "tramites",
			"options" => ["por-firmar", 98]
		],
		[
			"name" => "Finalizado",
			"icon" => "fas fa-check-circle",
			"path" => "tramites",
			"options" => ["finalizado", 2]
		]
	]
];