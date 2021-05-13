<div class="w-100 fixed " style="top:0;position:fixed;height:70px">
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color:#3ba3fb">
        <img src="images/logo.svg" width="120" height="65" class="d-inline-block align-top" alt="" loading="lazy">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active" style="padding-top:1%">
                <a class="nav-link" href="#">VENTANILLA UNICA DIGITAL <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item pl-12" style="max-width: 130px;">
                <img src="images/iconos-06.png" alt="" style="width:20px;">
                <a class="nav-link x-small-font" href="http://egobierno.nl.gob.mx/egob/Reimpresion.php">Reimprecion de declaracion</a>
            </li>
            <li class="nav-item" style="max-width: 120px;">
                <img src="images/iconos-07.png" alt="" style="width:20px;">
                <a class="nav-link x-small-font" href="http://egobierno.nl.gob.mx/egob/ConsultaConstancia.php">Obten tu constancia de retencion</a>
            </li>
            <li class="nav-item">
                <img src="images/iconos-08.png" alt="" style="width:20px;">
                <a class="nav-link x-small-font" href="http://egobierno.nl.gob.mx/egob/CancelRF.php">Cancelar Folio</a>
            </li>
            <li class="nav-item">
                <img src="images/iconos-09.png" alt="" style="width:20px;">
                <a class="nav-link x-small-font" href="http://cfdi.nl.gob.mx/">Obten tu CFDI</a>
            </li>
            <li class="nav-item">
                <img src="images/homeacceso-11.png" alt="" style="width:20px;">
                <a class="nav-link x-small-font" href="https://www.banxico.org.mx/cep/">Consulta el estado SPEI</a>
            </li>
            <li class="nav-item">
                <img src="images/iconos-10.png" alt="" style="width:20px;">
                <a class="nav-link x-small-font" href="#">Consulta a un asesor en linea</a>
            </li>
            </ul>
            <span class="navbar-text pr-4">
            Captura tu folio de seguimiento
            </span>
            <form class="form-inline mr-4">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="">
                <i class="fas fa-search text-black"></i>
            </form>
            <a class="btn btn-outline-success my-2  my-sm-0" href="/login">Iniciar Sesion</a>
        </div>
    </nav>
</div>

<div class="w-100 " style="margin-top:30%;    background-color: aliceblue;">
        <div class="pb-20" style="margin-top: -20%;margin-left:10% ">
            <h2 style="color:#3ba3fb !important;font-weight: 600;">Accede a trámites y servicios</h2>
            <h2 style="color:#3ba3fb !important;font-weight: 600;">desde cualquier lugar</h2>

            <div class="btn-group" style="width:35%">
                <input type="search" class="ds-input form-control w-45"  
                placeholder="¿cual trámite deseas realizar?" 
                aria-label="¿cual trámite deseas realizar?" 
                autocomplete="off" 
                spellcheck="false" 
                role="combobox" 
                aria-autocomplete="list" 
                aria-expanded="false" 
                aria-owns="algolia-autocomplete-listbox-0" 
                dir="auto" 
                style="position: relative; vertical-align: top;width:100% !important;border-color:#2699fb;color:#2699fb;height: unset;border-radius:unset"
                >
                <button type="button" id="btnWizard" data-wizard-type="action-next" 
                        class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4" >
                <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    <div class="d-flex flex-column-fluid" style="background-color:#f1f8fe;min-height: 450px;">
        <div class="container dashboard pt-0">
        <div class="row row-cols-1 row-cols-md-3">
            
            @foreach( $tramitesAgrupados as $categoria )
                
                <div class="col mb-4">
                    <div class="card" style="border: 0;background-color: aliceblue;">
                        <div class="card-body">
                            <h4 style="color:#3ba3fb">{{$categoria['name']}}</h4>
                                <div class="table-responsive">
                                    <ul style="font-size:10px">
                                        @for($i = 0 ; $i < count($categoria['tramites']) ; $i++ )
                                        @php 
                                        $url =  env("APP_URL") . "/detalle-tramite/";
                                        @endphp
                                        <li class="p-2"><a href="{{$url.$categoria['tramites'][$i]->id_tramite}}" >{{ $categoria['tramites'][$i]->tramite }} </a> </li>
                                        @endfor
                                    </ul>
                                </div>
                        </div>
                    </div>
                </div>

            @endforeach

            <!-- <div class="table-responsive">
                                    <ul style="font-size:10px">
                                      
                                        <li class="p-2"><a href="detalle-tramite/399" style="color:#3ba3fb !important">Pago de 5% de ISR regimen intermedio</a></li>
                                      
                                    </ul>
                                </div>
            </div> -->
        </div>        
    </div>
</div> 
<footer class="align-items-center px-3" style="position: fixed; bottom: 0px; width: 100%;background-color: #cbe7fe">
        
</footer>
<div class="card-footer bg-transparent border-success" style="background-color: #cbe7fe; border: 0">
    <div class="col text-center" style="color:#56affb">
        <strong class="mr-2" style="color:#56affb">2021</strong> <strong class="mr-2" style="color:#56affb">Aviso de Privacidad</strong> <strong style="color:#56affb">Contacto</strong>
    </div>
</div>


<script type="text/javascript">
        $(document).ready(function(){
            $("#app").removeClass("align-items-center"); 

            console.log("se quito la clase?")
        });
</script>