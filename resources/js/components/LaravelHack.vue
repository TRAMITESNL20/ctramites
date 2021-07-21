<template>
    <div>
        <!-- En esta rama estan los cambios que se veian en la rama feat/evitar-descarga-componentes, se pensaba que la rama estaba corrompida asi que me traje todos los cambios para aca, mas un fix importante, 
en un entorno local el componente vue-pdf-component estaba tronando unicamente si corrias el proyecto desde 0 , al guardar (sin efectuar ningun cambio) ya funcionaba correctamente,  el error era Failed to resolve async component ....vue-pdf-component ya que este a su ves importaba un componente llamado vue-pdf que incluye sus propios estilos y en el app.js los estilos no estaban cargados al correr el proyecto desde 0, una ves recargaras no habia error
al pasar al review app esto presento un problema 
se resolvio de la siguiente manera
Se crea un componente auxiliar llamado LaravelHack.vue que importa (sin usar) el mismo componente vue-pdf  su funcion es cargar antes de que cargue nuestro componente con problemas para que ya tenga listos sus estilos
nota ** este error tambien puede ocurrir si se dejan los tags <style> </style> dentro del vue component
src** https://github.com/JeffreyWay/laravel-mix/issues/2064 ** -->
    </div>
</template>

<script>
import pdf from "vue-pdf";
export default {
    name: 'LaravelHack',
     components: {
        pdf,
    },
}
</script>

<style scoped lang="scss">
div#nonExistingSCSSIdHack {
    display: none;
}
</style>