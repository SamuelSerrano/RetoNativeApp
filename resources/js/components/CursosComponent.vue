<template>
    <div class="row justify-content-center">
        <frm-curso-component @new="addCurso"></frm-curso-component>
        <curso-component v-for="(curso, index) in cursos" 
        :key="curso.id"
        :curso="curso"
        @update="updateCurso(index, ...arguments)"
        @delete="deleteCurso(index)">
        </curso-component>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                cursos: []
            }
        },
        mounted() {
            axios.get('/cursos').then((response)=>
            {
                this.cursos = response.data;
            });
        },
        methods:{
            addCurso(curso)
            {
                this.cursos.push(curso);
            },
            updateCurso(index, objCurso)
            {
                this.cursos[index] = objCurso;
            },
            deleteCurso(index)
            {
                this.cursos.splice(index,1);
            }
        }
    }
</script>
