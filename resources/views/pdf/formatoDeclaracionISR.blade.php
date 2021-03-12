<!DOCTYPE html>
<html>
<head>
	<title>{{ $info->tramite }}</title>
	<style type="text/css">
		@page {
                margin-top: 0px ;
        }
		body{
			background-color: white;
			padding: 0;
			margin: 0;
			font-family: sans-serif;
			font-size: 10px;
		}

		p{
			font-size: .8em;
			color: #999 !important;
		}

		h1 {
			font-size: 1em;
		}

		thead th{
			vertical-align: middle;
			padding-top: 0px;
		}
		
		th {
			height: 80px;
			vertical-align: 0px;
		}

		td{
			padding: 8px;
		}

		.text-uppercase{
			text-transform: uppercase;
		}

		.text-left{
			text-align: left;
		}

		strong{
			color: #555;
			font-weight: bold;
		}

		.enajenante{
			background-color: #F2F3F7 !important;
			/*background-color: #f0f9fc !important;*/
		}

		.titulo1{
			text-align: left;
			padding-top: 30px;
			padding-bottom: 10px;
			font-size: 13px;
		}
		.columna{
			padding-top: 10px;
			padding-bottom: 4px;
			max-width: 33%;
			min-width: 33%;
		}
		.value{
			padding-bottom: 10px;
			padding-top: 0px;
			color: grey !important; 
		}
		.border_bottom{
			border-bottom: 1px solid grey;
		}
		table{
			border-collapse: collapse; 
		}
		.datos-enajenante{
			background-color: #F2F3F7;
		}
		.datos-enajenante.first td{
			padding-top: 15px;
		}
		.datos-enajenante.last td{
			padding-bottom: 15px;
		}
		.footer{
			position:fixed;
			left:0px;
			bottom:55px;
			height:65px;
			width:100%;
		}
		.text-center{
			text-align: center;
		}
		.motivo-fundamento{
			padding: 20px;
		}
		.motivo-fundamento:first-child{
			border-right: 1px solid softgray;
		}
	</style>
</head>
<body style="margin-top:0px">
	<table style="width: 100%; margin-top:0px" border="0" >
		<thead class="border_bottom" >
			<tr class="header">
				<th colspan="3" valign="middle" class="text-uppercase text-left"> <strong> DECLARACION DEL {{ $info->tramite }} <br> REALIZADAS EN EL ESTADO DE NUEVO LEÓN </strong> </th>
				<th class="" valign="middle"><img src="{{ public_path('images/escudo-de-armas-veda-color.jpg') }}" width="200"></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="titulo1" colspan="4"> <strong> DATOS DEL INMUEBLE(S) QUE SE ENAJENA(N) </strong></td>
			</tr>
				<tr>
					<td class="columna">NÚMERO DE ESCRITURA PÚBLICA O MINUTA</td>
					<td class="columna" colspan="2"></td>
					<td class="columna">FECHA DE ESCRITURA PÚBLICA O MINUTA</td>
				</tr>
				<tr>
					<td class="columna value" style="width: 33%;">{{ $info->solicitudes[0]->info->campos->{'Escritura'} }}</td>
					<td class="columna value" style="width: auto;" colspan="2"></td>
					<td class="columna value" style="width: 33%;">{{ $info->solicitudes[0]->info->campos->{'Fecha de escritura o minuta'} }}</td>
				</tr>
				<tr>
					<td class="columna" style="width: 1%">MUNICIPIO</td>
					<td class="columna" style="width: 1%" colspan="2">NO. EXP. CATASTRAL</td>
					<td class="columna" style="width: 1%" >DOMICILIO DE UBICACIÓN DEL INMUEBLE</td>
				</tr>			
				<tr>
					<td class="columna value" >{{ $info->solicitudes[0]->info->campos->Expedientes->expedientes[0]->municipio->{'nombre'} }}</td>
					<td class="columna value" colspan="2">{{ $info->solicitudes[0]->info->campos->Expedientes->expedientes[0]->{'expediente'} }}</td>
					<td class="columna value" >{{ $info->solicitudes[0]->info->campos->Expedientes->expedientes[0]->direccion->datos_direccion[0]->{'calle'} }} </td>
				</tr>
			<tr >
				<td class="titulo1" colspan="4"> <strong> DATOS DEL ENAJENANTE </strong></td>
			</tr>
				@if ($enajenante->tipoPersona == 'pf')
					<tr class="datos-enajenante first">
						<td class="columna">REGISTRO FEDERAL DEL CONTRIBUYENTE:</td>
						<td class="columna" colspan="2">CLAVE ÚNICA DE REGISTRO DE POBLACIÓN:</td>
						<td class="columna">CLAVE DE ELECTOR (INE):</td>
					</tr>
					<tr class="datos-enajenante">
						<td class="columna value">{{ $enajenante->datosPersonales->{'rfc'} ?? '' }}</td>
						<td class="columna value" colspan="2">CLAVE ÚNICA DE REGISTRO DE POBLACIÓN:</td>
						<td class="columna value">{{ $enajenante->datosPersonales->{'claveIne'} ?? '' }}</td>
					</tr>
					<tr class="datos-enajenante">
						<td class="columna">NOMBRE:</td>
						<td class="columna"colspan="2">APELLIDO PATERNO:</td>
						<td class="columna">APELLIDO MATERNO:</td>
					</tr>
					<tr class="datos-enajenante">
						<td class="columna value">{{ $enajenante->datosPersonales->{'nombre'} ?? '' }}</td>
						<td class="columna value" colspan="2">{{ $enajenante->datosPersonales->{'apPat'} ?? '' }} </td>
						<td class="columna value">{{ $enajenante->datosPersonales->{'apMat'} ?? '' }}</td>
					</tr>
					<tr class="datos-enajenante">
						<td class="columna">% DE CO-PROPIEDAD:</td>
						<td class="columna"colspan="2"></td>
						<td class="columna"></td>
					</tr>
					<tr class="datos-enajenante last">
						<td class="columna value">{{ $enajenante->{'porcentajeCompra'} ?? '' }}</td>
						<td class="columna value" colspan="2"> </td>
						<td class="columna value"></td>
					</tr>
				@elseif ($enajenante->tipoPersona == 'pm')
					<tr class="datos-enajenante fisrt">
						<td class="columna">REGISTRO FEDERAL DEL CONTRIBUYENTE:</td>
						<td class="columna" colspan="2">RAZÓN SOCIAL:</td>
						<td class="columna">% DE CO-PROPIEDAD:</td>
					</tr>
					<tr class="datos-enajenante last">
						<td class="columna value">{{ $enajenante->datosPersonales->{'rfc'} ?? '' }}</td>
						<td class="columna value" colspan="2">{{ $enajenante->datosPersonales->{'razonSocial'} ?? '' }}</td>
						<td class="columna value">{{ $enajenante->{'porcentajeCompra'} ?? '' }}%</td>
					</tr>
				@endif
				@if ($info->solicitudes[0]->info->{'tipoTramite'} === 'declaracionEn0')
					<tr class="datos-enajenante fisrt">
						<td class="columna">MONTO DE OPERACIÓN:</td>
						<td class="columna" colspan="2">&nbsp;</td>
						<td class="columna">&nbsp;</td>
					</tr>
					<tr class="datos-enajenante last">
						<td class="columna value">{{ $enajenante->datosParaDeterminarImpuesto->{'montoOperacion'} ?? '' }}</td>
						<td class="columna value" colspan="2">&nbsp;</td>
						<td class="columna value">&nbsp;</td>
					</tr>
				@endif

			@if ($info->solicitudes[0]->info->{'tipoTramite'} !== 'declaracionEn0')
				<tr >
					<td class="titulo1" colspan="4"> <strong> DATOS PARA DETERMINAR EL IMPUESTO DE LA ENTIDAD FEDERATIVA </strong></td>
				</tr>
					<tr >
						<td class="columna">TIPO DE DECLARACION</td>
						<td class="columna" colspan="2">GANANCIA OBTENIDA</td>
						<td class="columna">MONTO OBTENIDO CONFORME AL <br> ART 154 BIS DE LA LISR</td>
					</tr>
					<tr >
						<td class="columna value text-uppercase">{{ $info->solicitudes[0]->info->{'tipoTramite'} ?? '' }}</td>
						<td class="columna value" colspan="2"> {{ $enajenante->datosParaDeterminarImpuesto->{'gananciaObtenida'} ?? ''}}</td>
						<td class="columna value">MX${{ number_format($enajenante->detalle->Salidas->{'Monto obtenido conforme al art 127 LISR'} ?? 0,2) }}</td>
					</tr>
					<tr >
						<td class="columna">PAGO PROVISIONAL CONFORME AL <br>	ART 154 BIS DE LISR</td>
						<td class=" columna" colspan="2">IMPUESTO CORRESPONDIENTE A LA <br> ENTIDAD FEDERATIVA</td>
						<td class="columna">PARTE ACTUALZIADA DEL <br> IMPUESTO</td>
					</tr>
					<tr >
						<td class="columna value">MX${{ number_format($enajenante->detalle->Salidas->{'Pago provisional conforme al art 126 LISR'} ?? 0, 2) }}</td>
						<td class="columna value" colspan="2">MX${{ number_format($enajenante->detalle->Salidas->{'Impuesto correspondiente a la entidad federativa'} ?? 0, 2) }}</td>
						<td class="columna value">MX${{ number_format($enajenante->detalle->Salidas->{'Parte actualizada del impuesto'} ?? 0, 2) }}</td>
					</tr>
					<tr >
						<td class="columna">RECARGOS</td>
						<td class=" columna" colspan="2">MULTA POR CORRECCION FISCAL</td>
						<td class="columna">IMPORTE TOTAL</td>
					</tr>
					<tr >
						<td class="columna value">MX${{ number_format($enajenante->detalle->Salidas->{'Recargos'} ?? 0, 2) }}</td>
						<td class="columna value" colspan="2">MX${{ number_format($enajenante->detalle->Salidas->{'Multa corrección fiscal'} ?? 0, 2) }}</td>
						<td class="columna value">MX${{ number_format($enajenante->detalle->Salidas->{'Importe total'} ?? 0, 2) }}</td>
					</tr>
			@endif
			<tr >
				<td class="titulo1" colspan="4"> <strong> CONTROL Y SEGUIMIENTO </strong></td>
			</tr>
				<tr class="{{ $info->solicitudes[0]->info->{'tipoTramite'} !== 'declaracionEn0' ? 'datos-enajenante' : '' }} first">
					<td class="columna">ESTATUS</td>
					<td class=" columna" colspan="2">FECHA LIMITE DE PAGO</td>
					<td class="columna">NO. FOLIO DE TESORERIA VIRTUAL</td>
				</tr>
				<tr class="{{ $info->solicitudes[0]->info->{'tipoTramite'} !== 'declaracionEn0' ? 'datos-enajenante' : '' }}">
					<td class="columna value">{{ $control->operaciones->{'estatus_tramite'} ?? "-" }}</td>
					<td class="columna value" colspan="2">{{ $control->operaciones->{'fecha_limite_referencia'} ?? "-"}}</td>
					<td class="columna value">{{ $control->operaciones->{'id_transaccion_motor'} ?? "-" }}</td> 
				</tr>
				<tr class="{{ $info->solicitudes[0]->info->{'tipoTramite'} !== 'declaracionEn0' ? 'datos-enajenante' : '' }}">
					<td class="columna">FECHA DE PAGO</td>
					<td class=" columna" colspan="2">NO. DE OPERACION INTERNA</td>
					<td class="columna">REFERENCIA BANCARIA</td>
				</tr>
				<tr class="{{ $info->solicitudes[0]->info->{'tipoTramite'} !== 'declaracionEn0' ? 'datos-enajenante' : '' }} last">
					<td class="columna value">{{ $control->operaciones->{'fecha_pago'} ?? "-" }}</td>
					<td class="columna value" colspan="2">{{ $control->operaciones->{'operacion_interna'} ?? "-" }}</td>
					<td class="columna value">{{  $control->operaciones->{'referencia'}  ?? "-"  }}</td>
				</tr>
			@if ($info->solicitudes[0]->info->{'tipoTramite'} === 'declaracionEn0')
				<tr>
					<td class="titulo1 text-center" colspan="2"> <strong> MOTIVO POR EL QUE DECLARA EN CEROS </strong></td>
					<td class="titulo1 text-center" colspan="2"> <strong> FUNDAMENTO LEGAL</strong></td>
				</tr>
					<tr class="datos-enajenante first last">
						<td class="motivo-fundamento" colspan="2" valign="middle" align="center">{{ $info->solicitudes[0]->info->campos->{'Listado de enajenantes'}->motivo ?? '' }}</td>
						<td class="motivo-fundamento" colspan="2" valign="middle" align="center">...</td>
					</tr>
			@endif
		</tbody>
	</table>
	<div class="footer">
		<table style="width: 100%; margin-top:0px; text-align:center;" >
			<tr class="">
				<td class=""></td>
				<td class=""></td>
				<td class=""></td>
			</tr>
			<tr class="">
				<td class="border_bottom">{{ 'NOTARIO_TITULAR' }}</td>
				<td class="border_bottom">SELLO DEL NOTARIO PUBLICO</td>
				<td class="border_bottom">FIRMA DEL CONTRIBUYENTE</td>
			</tr>
			<tr class="datos-enajenante">
				<td  colspan="3"><div style="height:26px;"></div></td>
			</tr>
		</table>
		<div style="position:fixed;left:0px;bottom:0px;"> 
			<p >BAJO PROTESTA DE DECIR VERDAD MANIFIESTO QUE TODOS Y CADA UNO DE LOS DATOS PROPORCIONADOS EN ESTA DECLARACION SON CIERTOS.<p>
		</div>
 	 </div>
</body>
</html>