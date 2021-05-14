<div class="content tabla-horizontal table-responsive iecontent" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container view-usuarios">
            <span class="col-sm-12 col-md-3 mb-4 ">
                perfil -> Usuarios
            </span> 
            <div class="row users-component pt-7">
                <card-porfile-component  :user="{{ json_encode($user) }}" ></card-porfile-component>
                <usuarios-component notary="{{$notary}}"></usuarios-component>
            </div>
        </div>        
    </div>
</div> 