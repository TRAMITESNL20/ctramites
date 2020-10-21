<div class="content" id="kt_content">
    <div class="d-flex flex-column-fluid">

        @if($user->role_id === 2 || $user->role_id === 5 || $user->role_id === 6)
        <div class="container">

            <div class="tramites">
                <div class="cuadros-cont">
                    <div>
                        <span > inicio->Tramites pendientes</span>
                    </div>
                    <div class="cuadro-listado tramite-lista" style="padding-top: 10px">
                        <div class="titulo-cuadro">
                            <div style="padding-top: 20px;">
                                <span class="tituloTabla">
                                    Trámites pendientes
                                </span>
                                <a class="btn btn-icon btn-primary btn-exportar-registro" ><i class="la la-plus"></i> Exportar registro</a>
                                <a class="btn btn-icon btn-primary btn-exportar "> <i style="color: #0c4d8b" class="la la-download"></i>Exportar</a>
                            </div>
                            <div class="dropdown-divider"></div>
                            <table id="listado" class="table .table-bordered table-responsive tabla-horizontal " >
                                <thead>
                                    <tr >
                                        <th > 
                                            <div >
                                                <span>id tramites</span>
                                            </div>
                                        </th>
                                        <th > 
                                            <div >
                                                <span>Nombre del Tramite</span>
                                            </div>
                                        </th>
                                        <th > 
                                            <div>
                                                <span>Nombre del solicitante</span>
                                            </div>
                                        </th>
                                        <th > 
                                            <div>
                                                <span>Fecha de creacion</span>
                                            </div>
                                        </th>
                                        <th > 
                                            <div>
                                                <span>Fecha de ultima modificacion</span>
                                            </div>
                                        </th>
                                        <th > 
                                            <div>
                                                <span>Acciones</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                            </table>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="container">
            <p class="text-center pt-5">
                No Tienes Permisos para esta Accion
            </p>
        </div>
        @endif
    </div>
</div>    
<link href="{{ asset('css/tramites.css') }}" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(() => createTable());

function createTable( ){
    let url = "{{ url()->route('getTramites') }}";
    $('#listado').DataTable({
        initComplete:initTableComplete,
        "ajax": {
            "url":url,"dataSrc":""
        },
        pagingType: "simple_numbers"  ,
        ordering: false,
        dom:" <'grid' <f> <l>   <p>   <i>  >t  <'grid' <f> <l> <p>  <i> >",
        "language": {
            "lengthMenu" : "<span>Mostrar registros</span>  <div class='registros'> _MENU_</div>   ",
            "zeroRecords" : "No se encontraron resultados",
            "info" : "Mostrando _START_ de _END_ entradas",
            "infoEmpty" : "Mostrando registros del 0 al 0 de un tatal de 0 registros",
            "infoFiltered" : "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst" : "Primero",
                "sLAst" : "Ultimo",
                "sNext" : ">",
                "sPrevious" : "<"
            },
            "sProcessing" : "Procesando...",
        },
        "columns": [
            { "data": "id_tramite", class:"alineacionVertical",render: renderTd }, 
            { "data": "nombre", render: renderTitulo },
            { "data": "user", render: renderNombre },
            { "data": "created_at", render: renderDate },
            { "data": "updated_at", render: renderDate },
            { "data": "id", "render": getTemplateAcciones}
        ]
    });
}

function initTableComplete(){
    $("#listado").removeClass('dataTable');
    $(".dataTables_filter").hide();    
}

function getTemplateAcciones( data, type, row, meta  ){
    let botonEditar = '<a type="button" ><i class="fas fa-edit"></i></a>';
    let botonEliminar = '<a type="button" ><i class="far fa-trash-alt"></i></a>';
    return botonEditar + botonEliminar; 
}

function renderTd( data ){
   return '<div class="text-center"><span class="identificador">' + data + '</span></div>';                              
}

function renderTitulo( data, type, row, meta ){
    return data ? '<span class="nombre-tramite">' + data + '</span>' : "";
}

function renderNombre( data){
    return data ? '<span class="nombre">' + data + '</span>' : "";
}

function renderDate( data ){
    if( data){
        let f = new Date("2020-09-10T11:51:23.000000Z");
        let mes =  f.getMonth() > 9 ? + f.getMonth() :  "0" + f.getMonth();
        let fecha = f.getDate() + "-"+ mes + "-" +f.getFullYear();

        return '<span class="nombre">' + fecha + '</span>';
    } else {
        return "";
    }
}

$("#busqueda").on("keyup", (s) => {
    $('#listado').DataTable().search( $("#busqueda").val() ).draw();
})


</script>