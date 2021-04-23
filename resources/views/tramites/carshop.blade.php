<?php
    $user = session()->get("user");
    $id = $user->id;
?>
<div class="content d-flex flex-column flex-column-fluid" id="app">
    <div class="d-flex flex-column-fluid">
        <div class="container">                
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent">
                    <li class="breadcrumb-item"><a href="{{ url()->route("home") }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url()->route("tramite.nuevo") }}">Trámites</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="tramite-name">CARRITO</li>
                </ol>
            </nav>
            <div style="min-height: 600px;" class="content d-flex flex-column flex-column-fluid pt-0">
                <div>
                    @php
                        $usuario = json_encode($user);
                      @endphp
                    <car-shop-component :type="{{ $id }}" id-usuario="{{ $id }}" :usuario="{{$usuario}}"></car-shop-component>
                </div>
            </div>
        </div>
        
    </div>
</div> 
