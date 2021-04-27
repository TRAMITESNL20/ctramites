<div class="content ">
    <div class="d-flex flex-column-fluid">
        <div class="container dashboard pt-0">
            <div class="row">
                    <!--[if IE]>
                    <div class="error"><span>ESTAS EN INTERNET EXPLORER</span></div>
                    ![endif]-->

                <div class="col-lg-6 cards-espace" > 
                    <div class="card" >
                          <div class="card-body">
                                <h5 class="card-title">
                                    <div style="padding-left: 1%">
                                        <span class="titulo">Borradores <span class="badge badge-secondary">{{ $totals['draft'] }}</span></span>
                                    </div>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach ($draft as $item)
                                                @php
                                                    $infoDraft = $item->info;
                                                @endphp
                                                <tr>
                                                    <td class="mobile-id" style="vertical-align: middle">
                                                        <div class="text-center">
                                                            <span class="identificador">
                                                                {{$item->id}}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td colspan="3">
                                                        <span class="nombre-tramite">
                                                            {{$item->titulo}}
                                                        </span>
                                                        <div class="nombre">
                                                            <strong>{{ $item->clave }}</strong>
                                                            @php
                                                                if(isset($infoDraft->solicitante)){
                                                                    if($infoDraft->solicitante->tipoPersona == 'pf'){
                                                                        $apMat = isset($infoDraft->solicitante->apMat) ? $infoDraft->solicitante->apMat : '';
                                                                        echo "- {$infoDraft->solicitante->nombreSolicitante} {$infoDraft->solicitante->apPat} {$apMat}";
                                                                    }else{
                                                                        echo "- {$infoDraft->solicitante->razonSocial}";
                                                                    }
                                                                }
                                                            @endphp
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if (count($draft) === 0)
                                                <tr>
                                                    <td class="nombre" align="center">NO HAY REGISTROS <strong>EN BORRADOR</strong> PARA MOSTRAR</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @if ($totals['draft'] > 0)
                                    <div class="text-center">
                                        <a href="{{ url()->route("tramites", [ "borradores", "80" ]) }}" type="button" class="btn btn-vermas">VER MÁS</a>
                                    </div>
                                @endif
                          </div>
                    </div>
                </div>
                <div class="col-lg-6 cards-espace" > 
                    <div class="card" >
                          <div class="card-body">
                                <h5 class="card-title">
                                    <div style="padding-left: 1%">
                                        <span class="titulo">Pendientes de Pago <span class="badge badge-sm badge-secondary">{{ $totals['pendingPayment'] }}</span></span>
                                    </div>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach ($pendingPayment as $item)
                                                @php
                                                    $infoPP = $item->info;
                                                @endphp
                                                <tr>
                                                    <td class="mobile-id" style="vertical-align: middle">
                                                        <div class="text-center">
                                                            <span class="identificador">
                                                                {{$item->id}}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td colspan="3">
                                                        <span class="nombre-tramite">
                                                            {{$item->titulo}}
                                                        </span>
                                                        <div class="nombre">
                                                            <strong>{{ $item->clave }}</strong>
                                                            @php
                                                                if(isset($infoPP->solicitante)){
                                                                    if($infoPP->solicitante->tipoPersona == 'pf'){
                                                                        $apMat = isset($infoPP->solicitante->apMat) ? $infoPP->solicitante->apMat : '';
                                                                        echo "- {$infoPP->solicitante->nombreSolicitante} {$infoPP->solicitante->apPat} {$apMat}";
                                                                    }else{
                                                                        echo "- {$infoPP->solicitante->razonSocial}";
                                                                    }
                                                                }
                                                            @endphp
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if (count($pendingPayment) === 0)
                                                <tr>
                                                    <td class="nombre" align="center">NO HAY REGISTROS <strong>PENDIENTES DE PAGO</strong> PARA MOSTRAR</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @if ($totals['pendingPayment'] > 0)
                                    <div class="text-center">
                                        <a href="{{ url()->route("tramites", [ "pendientes-de-pago", "99" ]) }}" type="button" class="btn btn-vermas">VER MÁS</a>
                                    </div>
                                @endif
                          </div>
                    </div>
                </div>
                <div class="col-lg-6 cards-espace" > 
                    <div class="card" >
                          <div class="card-body">
                                <h5 class="card-title">
                                    <div style="padding-left: 1%">
                                        <span class="titulo">En Espera de Atención <span class="badge badge-sm badge-secondary">{{ $totals['waiting'] }}</span></span>
                                    </div>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach ($waiting as $item)
                                                @php
                                                    $infoWait = $item->info;
                                                @endphp
                                                <tr>
                                                    <td class="mobile-id" style="vertical-align: middle">
                                                        <div class="text-center">
                                                            <span class="identificador">
                                                                {{$item->id}}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td colspan="3">
                                                        <span class="nombre-tramite">
                                                            {{$item->titulo}}
                                                        </span>
                                                        <div class="nombre">
                                                            <strong>{{ $item->clave }}</strong>
                                                            @php
                                                                if(isset($infoWait->solicitante)){
                                                                    if($infoWait->solicitante->tipoPersona == 'pf'){
                                                                        $apMat = isset($infoWait->solicitante->apMat) ? $infoWait->solicitante->apMat : '';
                                                                        echo "- {$infoWait->solicitante->nombreSolicitante} {$infoWait->solicitante->apPat} {$apMat}";
                                                                    }else{
                                                                        echo "- {$infoWait->solicitante->razonSocial}";
                                                                    }
                                                                }
                                                            @endphp
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if (count($waiting) === 0)
                                                <tr>
                                                    <td class="nombre" align="center">NO HAY REGISTROS <strong>EN ESPERA DE ATENCIÓN</strong> PARA MOSTRAR</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @if ($totals['waiting'] > 0)
                                    <div class="text-center">
                                        <a href="{{ url()->route("tramites", [ "en-espera-de-atención", "3" ]) }}" type="button" class="btn btn-vermas">VER MÁS</a>
                                    </div>
                                @endif
                          </div>
                    </div>
                </div>
                <div class="col-lg-6 cards-espace" > 
                    <div class="card" >
                          <div class="card-body">
                                <h5 class="card-title">
                                    <div style="padding-left: 1%">
                                        <span class="titulo">En Curso <span class="badge badge-sm badge-secondary">{{ $totals['progress'] }}</span></span>
                                    </div>
                                </h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach ($progress as $item)
                                                @php
                                                    $infoProgress = $item->info;
                                                @endphp
                                                <tr>
                                                    <td class="mobile-id" style="vertical-align: middle">
                                                        <div class="text-center">
                                                            <span class="identificador">
                                                                {{$item->id}}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td colspan="3">
                                                        <span class="nombre-tramite">
                                                            {{$item->titulo}}
                                                        </span>
                                                        <div class="nombre">
                                                            <strong>{{ $item->clave }}</strong>
                                                            @php
                                                                if(isset($infoProgress->solicitante)){
                                                                    if($infoProgress->solicitante->tipoPersona == 'pf'){
                                                                        $apMat = isset($infoProgress->solicitante->apMat) ? $infoProgress->solicitante->apMat : '';
                                                                        echo "- {$infoProgress->solicitante->nombreSolicitante} {$infoProgress->solicitante->apPat} {$apMat}";
                                                                    }else{
                                                                        echo "- {$infoProgress->solicitante->razonSocial}";
                                                                    }
                                                                }
                                                            @endphp
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if (count($progress) === 0)
                                                <tr>
                                                    <td class="nombre" align="center">NO HAY REGISTROS <strong>EN CURSO</strong> PARA MOSTRAR</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @if ($totals['progress'] > 0)
                                    <div class="text-center">
                                        <a href="{{ url()->route("tramites", [ "en-curso", "1" ]) }}" type="button" class="btn btn-vermas">VER MÁS</a>
                                    </div>
                                @endif
                          </div>
                    </div>
                </div>
            </div>

        </div>        
    </div>
</div> 