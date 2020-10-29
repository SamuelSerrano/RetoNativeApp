<template>
    <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Estudiantes
                </div>

                <div class="card-body">
                    <form action="" v-on:submit.prevent="newEstudiante()">
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
                                    <label for="apellido">Apellido</label>
                                    <input type="text" class="form-control" name="apellido" v-model="mod_apellido">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">                                                 
                                    <label for="correo">Correo</label>
                                    <input type="text" class="form-control" name="correo" v-model="mod_correo">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="edad">Edad</label>
                                    <input type="text" class="form-control" name="edad" v-model="mod_edad">
                                </div>                                   
                            </div>                            
                        </div>
                        <button type="submit" class="btn btn-primary">Crear Estudiante</button>
                    </form>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        data(){
            return{
                errors: [],
                mod_nombre: '',
                mod_apellido:'',
                mod_edad:'',
                mod_correo:''
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            newEstudiante()
            {   
                if(this.checkForm())
                {
                    const params =
                    {
                        nombre:this.mod_nombre,
                        apellido:this.mod_apellido,
                        correo:this.mod_correo,
                        edad:this.mod_edad
                    };  
                    axios.post('/estudiantes', params)
                    .then((response)=>{
                        const estudiante = response.data;
                        this.$emit('new',estudiante);

                        this.mod_nombre='';
                        this.mod_apellido='';
                        this.mod_correo='';
                        this.mod_edad='';
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
                if (!this.mod_apellido) {
                    this.errors.push("El apellido es obligatorio.");
                }
                if (!this.mod_edad) {
                    this.errors.push('La edad es obligatoria.');
                } else if (!this.validNumber(this.mod_edad)) {
                    this.errors.push('La edad debe ser numérica');
                }
                if (!this.mod_correo) {
                    this.errors.push('El correo electrónico es obligatorio.');
                } else if (!this.validEmail(this.mod_correo)) {
                    this.errors.push('El correo electrónico debe ser válido.');
                }

                if (!this.errors.length) {                    
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
