<template>
      <div class="nav col-lg-4 nav" style="margin-bottom: 30px;"> 
                    <div class="card" >
                          <div class="card-body">
                            <div class="d-flex align-items-center" style="border-bottom:  1px solid #ECF0F3 !important;padding-bottom: 30px">
                                <div class="symbol symbol-100 mr-5">
                                    <span class="symbol-label font-size-h2 font-weight-bold bg-dark-o-30"> {{  user.name.substr(0,1) }} {{ user.fathers_surname.substr(0,1 )  }}</span>
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="/perfil" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary"> {{ user.name }} {{ user.fathers_surname}}</a>
                                    <div class="text-muted mt-1">{{ user.role.description }}</div>
                                    <div class="navi mt-2">
                                        <a href="mailto:user.email" class="navi-item">
                                            <span class="navi-link p-0 pb-2">
                                                <span class="navi-icon mr-1">
                                                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                                                        <!--begin::Svg Icon | path:media/svg/icons/Communication/Mail-notification.svg-->
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24" />
                                                                <path d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z" fill="#000000" />
                                                            </g>
                                                        </svg>
                                                        <!--end::Svg Icon-->
                                                    </span>
                                                </span>
                                                <span class="navi-text text-muted text-hover-primary text-truncate col-8 pl-0">{{ user.email}}</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                                <div class="">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td style="vertical-align: middle">
                                                    <div>
                                                        <span>
                                                            Email   
                                                        </span>
                                                    </div>
                                                </td>
                                                <td colspan="3">
                                                    <span class="text-muted text-truncate col-8 pl-0">
                                                        {{ user.email  }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr v-if="user.phone">
                                                <td style="vertical-align: middle">
                                                    <div>
                                                        <span>
                                                            Teléfono   
                                                        </span>
                                                    </div>
                                                </td>
                                                <td colspan="3">
                                                    <span class="text-muted" style="padding-right: 10%">
                                                        {{ user.phone  }}
                                                    </span>
                                                </td>
                                            </tr>

                                            
                                        </tbody>
                                    </table>
                                    <a href="/perfil" type="button" class="mt-4 btn btn-text-primary text-left btn-hover-light-primary font-weight-bold btn-lg btn-block text-dark text-hover-primary"> <i class="far fa-user"></i>Información Personal</a>
                                    <a href="/informacion-cuenta" type="button" class="btn btn-text-primary text-left btn-hover-light-primary font-weight-bold btn-lg btn-block text-dark text-hover-primary"> <i class="fas fa-clipboard-list"></i>Información de la Cuenta</a>
                                    <a href="/cambiar-contraseña"  type="checkbox" class="btn btn-text-primary text-left btn-hover-light-primary font-weight-bold btn-lg btn-block text-dark text-hover-primary"> <i class="fas fa-exchange-alt"></i>Cambiar Contraseña</a>
                                    <a href="/usuarios" type="button" class="btn btn-text-primary btn-hover-light-primary text-left font-weight-bold btn-lg btn-block text-dark text-hover-primary"> <i class="fas fa-people-arrows"></i>Usuarios</a>
                                </div>
                          </div>
                    </div>
                </div>
</template>

<script>
export default {
    props: ['user'],
    data(){
        return {
            ruta: '',
        }
    }
}
</script>