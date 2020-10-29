<template>
    <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Cursos
                </div>

                <div class="card-body">
                    <form action="" v-on:submit.prevent="newCurso()">
                        <p v-if="errors.length">
                            <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                            <ul>
                                <li v-for="error in errors" :key="error">{{ error }}</li>
                            </ul>
                         </p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" v-model="mod_nombre">
                                </div>
                                <div class="col-sm-6">
                                    <label for="horario">Horario</label>
                                    <select class="form-control" id="horario" v-model="mod_horario">
                                        <option>Diurno</option>
                                        <option>Nocturno</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <span class="block text-gray-600 text-sm text-left font-bold mb-2"
                                    >Fecha del Curso</span>
                                <v-date-picker
                                    v-model="range"
                                    mode="date"
                                    :masks="masks"
                                    is-range
                                >
                                </v-date-picker>
                            </div>                           
                        </div>
                        <button type="submit" class="btn btn-primary">Crear Curso</button>
                        
                    </form>

                </div>
                <div class="card-footer pull-right">
                     <button class="btn btn-light" v-on:click="onClickHideTop()" v-if="showTop">Esconder Top 3</button>
                    <button class="btn btn-info" v-on:click="onClickShowTop()" v-else>Ver Top 3</button>            
                </div>
            </div>
             <div class="card card-body" v-if="showTop">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center" v-for="elemento in topcursos" v-bind:key="elemento.id">{{ elemento.nombre }}<span class="badge badge-primary badge-pill">{{elemento.cantidad}}</span></li>                                                                
            </ul>
        </div>
        </div>
</template>

<script>
   import VCalendar from 'v-calendar';
    export default {
        data(){
            return{
                errors: [],
                topcursos:[],
                mod_nombre: '',
                mod_horario:'',
                mod_finicial:'',
                mod_ffinal:'',
                showTop:false,
                range: {
                        start: new Date(2020, 10, 2),
                        end: new Date(2020, 10, 6),
                       },
                masks: {
                    input: 'YYYY-MM-DD',
                },
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            newCurso()
            {   
                if(this.checkForm())
                {
                    
                    const params =
                    {
                        nombre:this.mod_nombre,
                        horario:this.mod_horario,
                        fechainicio:this.padLeft((this.range.start.getMonth())+1)+'/'+this.padLeft(this.range.start.getDate())+'/'+this.range.start.getFullYear(),
                        fechafin:this.padLeft((this.range.end.getMonth())+1)+'/'+this.padLeft(this.range.end.getDate())+'/'+this.range.end.getFullYear()
                    };  

                    axios.post('/cursos', params)
                    .then((response)=>{
                        const curso = response.data;
                        this.$emit('new',curso);

                        this.mod_nombre='';
                        this.mod_horario='';
                        
                        },(error) => {
                            console.log(error.message);
                        } 
                    );
                }             
                
            },
            checkForm()
            {
                this.errors = [];

                if (!this.mod_nombre) {
                    this.errors.push("El nombre es obligatorio.");
                }
                if (!this.mod_horario) {
                    this.errors.push("El horario es obligatorio.");
                }

                if (!this.errors.length) {                    
                    return true;
                }

                return false;
            },
            padLeft(n){
            return ("00" + n).slice(-2);
            },
            onClickHideTop(){
                this.showTop=false;
            },
            onClickShowTop(){
                axios.get('/topcurso').then((response)=>
                {
                    console.log(response.data);
                    //SDSE se combierte el objeto en un array.
                    this.topcursos = response.data.map(function(obj) {
                    return {
                        nombre: obj.nombre,
                        cantidad: obj.cantidad
                         }
                    });                          
                });
                this.showTop=true;
            }           
        }
        
    }
</script>
