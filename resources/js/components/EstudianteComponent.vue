<template>
    <div class="col-sm-9">        
        <div class="card">
        <div class="card-header">
            <div class="row">
               <div class="col-sm-7">
                   {{estudiante.nombre}} {{estudiante.apellido}}
               </div> 
               <div class="col-sm-4">
                   <button class="btn btn-light" v-on:click="onClickHideCursos()" v-if="showCursos">Esconder Cursos</button>
                    <button class="btn btn-info" v-on:click="onClickShowCursos()" v-else>Ver Cursos</button>            
               </div> 
            </div>            
        </div>               
        <div class="card card-body" v-if="showCursos">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center" v-for="elemento in cursosestudiante" v-bind:key="elemento.id">{{ elemento.nombre }}<span class="badge badge-primary badge-pill">{{elemento.horario}}</span></li>                                                                
            </ul>
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
                    <input type="text" class="form-control" v-model="estudiante.nombre">                   
                </div>
                <div class="col-sm-5">
                    <label >Apellido:</label>
                    <input type="text" class="form-control" v-model="estudiante.apellido"> 
                </div>
            </div> 
            <div class="row" >
                <div class="col-sm-7">
                    <label >Correo:</label>
                    <input type="text" class="form-control" v-model="estudiante.correo"> 
                </div>
                <div class="col-sm-3">
                    <label>Edad:</label>
                    <input type="text" class="form-control" v-model="estudiante.edad"> 
                </div>
            </div> 
        </div>
        <div class="card-body" v-else-if="asigarcursoMode">             
            <div class="row" >
                <div class="col-sm-4">
                    <label >Selecciona el curso a asignar:</label>                  
                </div>
                <div class="col-sm-5">
                   <select class="form-control" ref="idcurso">
                        <option v-for="elemento in cursos" v-bind:key="elemento.id" v-bind:value="elemento.id">{{ elemento.text }}</option>                                                                
                    </select>
                </div>
            </div>            
        </div>
        <div class="card-body" v-else>
            <div class="row" >
                <div class="col-sm-6">
                    <label >Correo:</label>
                   {{estudiante.correo}}
                </div>
                <div class="col-sm-3">
                    <label>Edad:</label>
                    {{estudiante.edad}}
                </div>
            </div>            
        </div>
        

        <div class="card-footer">
            <button class="btn btn-primary" v-on:click="onClickSave()" v-if="editMode">Guardar Cambios</button>
            <button class="btn btn-primary" v-on:click="onClickEdit()" v-else>Editar</button>            
            <button class="btn btn-secondary" v-on:click="onAsignarCurso()" v-if="asigarcursoMode">Guardar Cambio</button>
            <button class="btn btn-secondary" v-on:click="onAbrirAsignarCurso()" v-else>Asignar Curso</button>
            <button class="btn btn-danger" v-on:click="onClickDelete()">Eliminar</button>
        </div>
        </div>
    </div>    
</template>

<script>

// Note: Vue automatically prefixes the directive name with 'v-'
    export default {
        props:['estudiante'],
        data()
        {
            return{
               errorEditmode:[],
               cursos:[],
               cursosestudiante:[],
               editMode:false,
               asigarcursoMode:false,
               showCursos:false
            }
        },
        mounted() {
            console.log('Component mounted.');                        
        },
        methods:{
            onClickDelete()
            {
                axios.delete('/estudiantes/'+this.estudiante.id).then(()=>{                   
                   this.$emit("delete");
                    });
                
            },
            onClickEdit()
            {
                this.editMode=true;
                this.asigarcursoMode=false;
            },
            onClickSave()
            {
                if(this.checkForm())
                {
                     const params =
                    {
                        nombre:this.estudiante.nombre,
                        apellido:this.estudiante.apellido,
                        correo:this.estudiante.correo,
                        edad:this.estudiante.edad
                    };
                    axios.put('/estudiantes/'+this.estudiante.id, params).then((response)=>{
                    this.editMode=false;
                    const estudiante = response.data;
                    this.$emit('update',estudiante);
                    });
                   
                }
                
            },
            onAbrirAsignarCurso(){
                this.editMode=false;
                this.asigarcursoMode=true;
                axios.get('/cursos').then((response)=>
                {
                    //SDSE se combierte el objeto en un array.
                    this.cursos = response.data.map(function(obj) {
                    return {
                        id: obj.id,
                        text: obj.nombre
                         }
                    });                          
                });
            },
            onAsignarCurso()
            {
                // SDSE - Se verifica que el curso no se encuentre asociado previamente
                const params =
                    {
                        idEstudiante:this.estudiante.id,
                        idCurso:this.$refs.idcurso.value
                    };
                    axios.post('/asignarcurso', params).then((response)=>{
                        console.log(response.data);
                        if(response.data==1) alert('Se ha inscrito el curso');
                        else alert('Ya se encuentra inscrito en el curso seleccionado');                        
                    });                     
                    this.asigarcursoMode=false;               
            },
            onClickShowCursos(){
                const params =
                    {
                        idEstudiante:this.estudiante.id
                    };
                    axios.post('/listarcursos', params).then((response)=>
                    {
                        console.log(response.data);
                        this.cursosestudiante = response.data.map(function(obj) 
                        {
                        return {
                                nombre: obj.nombre,
                                horario: obj.horario
                                }
                        });                        
                    });
                this.showCursos=true;
            },
            onClickHideCursos(){
                this.showCursos=false;
            },
            checkForm()
            {
                this.errorEditmode = [];

                if (!this.estudiante.nombre) {
                    this.errorEditmode.push("El nombre es obligatorio.");
                }
                if (!this.estudiante.apellido) {
                    this.errorEditmode.push("El apellido es obligatorio.");
                }
                if (!this.estudiante.edad) {
                    this.errorEditmode.push('La edad es obligatoria.');
                } else if (!this.validNumber(this.estudiante.edad)) {
                    this.errorEditmode.push('La edad debe ser numérica');
                }
                if (!this.estudiante.correo) {
                    this.errorEditmode.push('El correo electrónico es obligatorio.');
                } else if (!this.validEmail(this.estudiante.correo)) {
                    this.errorEditmode.push('El correo electrónico debe ser válido.');
                }

                if (!this.errorEditmode.length) {                    
                    return true;
                }

                return false;
            },
            validEmail: function (email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
            },
            validNumber: function (num) {
            var re = /^[0-9]+$/;
            return re.test(num);
            }
        }
    }
</script>
