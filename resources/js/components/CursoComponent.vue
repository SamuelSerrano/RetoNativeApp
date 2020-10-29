<template>
    <div class="col-sm-9">
        <div class="card">
        <div class="card-header">
            {{curso.nombre}}
        </div>

        <div class="card-body" v-if="editMode">
            <p v-if="errorEditmode.length">
                <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                <ul>
                    <li v-for="error in errorEditmode" :key="error">{{ error }}</li>
                </ul>
            </p>
            <div class="row" >
                <div class="col-sm-5">
                    <label >Nombre:</label>
                    <input type="text" class="form-control" v-model="curso.nombre">                   
                </div>
                <div class="col-sm-5">
                    <label for="horario">Horario</label>
                        <select class="form-control" id="horario" v-model="curso.horario">
                            <option>Diurno</option>
                            <option>Nocturno</option>                                        
                        </select> 
                </div>
            </div> 
            <div class="row" >
                <div class="mb-4">
                    <span class="block text-gray-600 text-sm text-left font-bold mb-2"
                        >Fecha del Curso</span>
                    <v-date-picker
                        v-model="rangeEdit"
                        mode="date"
                        :masks="masksEdit"
                        is-range
                    >
                    </v-date-picker>
                </div>   
            </div> 
        </div>

        <div class="card-body" v-else>
            <div class="row" >
                <div class="col-sm-4">
                    <label >Horario:</label>
                    
                   {{curso.horario}}
                </div>
                <div class="col-sm-6">
                    <label>Fecha:</label>
                    {{curso.fechainicio}} - {{curso.fechafin}}
                </div>
            </div>            
        </div>
        

        <div class="card-footer">
            <button class="btn btn-primary" v-on:click="onClickSave()" v-if="editMode">Guardar Cambios</button>
            <button class="btn btn-primary" v-on:click="onClickEdit()" v-else>Editar</button>
            <button class="btn btn-danger" v-on:click="onClickDelete()">Eliminar</button>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['curso'],
        data()
        {
            return{
               errorEditmode:[],
               editMode:false,
               rangeEdit: {
                        start:  new Date(this.curso.fechainicio),
                        end: new Date(this.curso.fechafin)
                       },
                masksEdit: {
                    input: 'YYYY-MM-DD',
                },
            }
        },
        mounted() {
            console.log('Component mounted.');
        },
        methods:{
            onClickDelete()
            {
                axios.delete('/cursos/'+this.curso.id).then(()=>{                   
                   this.$emit("delete");
                    });
                
            },
            onClickEdit()
            {
                this.editMode=true;
            },
            onClickSave()
            {
                if(this.checkForm())
                {
                     const params =
                    {
                        nombre:this.curso.nombre,
                        horario:this.curso.horario,
                        fechainicio:this.padLeft((this.rangeEdit.start.getMonth())+1)+'/'+this.padLeft(this.rangeEdit.start.getDate())+'/'+this.rangeEdit.start.getFullYear(),
                        fechafin:this.padLeft((this.rangeEdit.end.getMonth())+1)+'/'+this.padLeft(this.rangeEdit.end.getDate())+'/'+this.rangeEdit.end.getFullYear()
                    };
                    axios.put('/cursos/'+this.curso.id, params).then((response)=>{
                    this.editMode=false;
                    const curso = response.data;
                    this.curso.fechainicio = curso.fechainicio;
                    this.curso.fechafin = curso.fechafin;
                    this.$emit('update',curso);
                    });
                   
                }
                
            },
            checkForm()
            {
                this.errorEditmode = [];

                if (!this.curso.nombre) {
                    this.errorEditmode.push("El nombre es obligatorio.");
                }
                if (!this.curso.horario) {
                    this.errorEditmode.push("El horario es obligatorio.");
                }                

                if (!this.errorEditmode.length) {                    
                    return true;
                }

                return false;
            },            
            padLeft(n){
            return ("00" + n).slice(-2);
            }
        }
    }
</script>
