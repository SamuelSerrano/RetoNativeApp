<template>
    <div class="row justify-content-center">
        <frm-estudiante-component @new="addEstudiante"></frm-estudiante-component>
        <estudiante-component v-for="(estudiante, index) in estudiantes" 
        :key="estudiante.id"
        :estudiante="estudiante"
        @update="updateEstudiante(index, ...arguments)"
        @delete="deleteEstudiante(index)">
        </estudiante-component>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                estudiantes: []
            }
        },
        mounted() {
            axios.get('/estudiantes').then((response)=>
            {
                this.estudiantes = response.data;
            });
        },
        methods:{
            addEstudiante(estudiante)
            {
                this.estudiantes.push(estudiante);
            },
            updateEstudiante(index, objEstudiante)
            {
                this.estudiantes[index] = objEstudiante;
            },
            deleteEstudiante(index)
            {
                this.estudiantes.splice(index,1);
            }
        }
    }
</script>
