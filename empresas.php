<!DOCTYPE html>
<html>
<head>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.2/dist/sweetalert2.min.css">  
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
  <style>
  #app{
      background-color:#CFD8DC;      
  }
  </style>
</head>
<body>
  <div id="app">
    <v-app>
      <v-main>   
       <!--<h2 class="text-center">CRUD usando APIREST con Node JS</h2>-->
       <!-- Botón CREAR -->                
        <v-card class="mx-auto mt-5" color="transparent" max-width="1280" elevation="0">            
        <v-btn class="mx-2"  @click="formNuevo()"><v-icon dark>mdi-plus</v-icon></v-btn>           
        <!-- Tabla y formulario -->
        <v-simple-table class="mt-5">
            <template v-slot:default>
                <thead>
                    <tr class="black darken-2">
                        <th class="white--text">Id</th>
                        <th class="white--text">Nombre</th>
                        <th class="white--text">Correo</th>
                        <th class="white--text">Logotipo</th>
                        <th class="white--text text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="empresa in empresas" :key="empresa.id">
                    <td>{{ empresa.id }}</td>
                    <td>{{ empresa.nombre }}</td>
                    <td>{{ empresa.correo }}</td>
                    <td>{{ empresa.logotipo }}</td>
                    <td>
                        <v-btn class="pink" dark small fab  @click="formEditar( empresa.id, empresa.nombre, empresa.correo , empresa.logotipo )"><v-icon>mdi-pencil</v-icon></v-btn>
                        <v-btn class="error" fab dark small @click="borrar(empresa.id)"><v-icon>mdi-delete</v-icon></v-btn>
                    </td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>
        </v-card>        
      <!-- Componente de Diálogo para CREAR y EDITAR -->
      <v-dialog v-model="dialog" max-width="500">        
        <v-card>
          <v-card-title class="purple darken-4 white--text">empresa</v-card-title>    
          <v-card-text>            
            <v-form  v-model="formValid" ref="myForm">             
              <v-container>
                <v-row>
                  <input v-model="empresa.id" hidden></input>
                  <v-col  md="12">
                    <v-text-field v-model="empresa.nombre" label="nombre"  >{{empresa.nombre}}</v-text-field>
                  </v-col>
                  <v-col  md="12">
                    <v-text-field v-model="empresa.correo" label="correo" type="email"></v-text-field>
                  </v-col>
                  <v-col  md="12">
                    <v-file-input max-height="100" max-width="100" v-model="empresa.logotipo" label="logotipo" ></v-file-input>
                  </v-col>
                </v-row>
              </v-container>            
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="dialog=false"dark>Cancelar</v-btn>
            <v-btn @click="guardar()" type="submit" color="accent-3" dark>Guardar</v-btn>
          </v-card-actions>
          </v-form>
        </v-card>
      </v-dialog>
      </v-main>
    </v-app>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.js" integrity="sha512-nqIFZC8560+CqHgXKez61MI0f9XSTKLkm0zFVm/99Wt0jSTZ7yeeYwbzyl0SGn/s8Mulbdw+ScCG41hmO2+FKw==" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.2/dist/sweetalert2.all.min.js"></script>

  <script>
    let url = 'http://127.0.0.1:8000/api/empresas/';
    new Vue({
      el: '#app',
      vuetify: new Vuetify(),
       data() {
        return {  
         
            empresas: [],
            dialog: false,
            operacion: '',            
            empresa:{
                id: null,
                nombre:'',
                correo:'',
                logotipo:''
            }          
        }
       },
      
       created(){               
            this.mostrar()
       },  
       methods:{          
            //MÉTODOS PARA EL CRUD
            mostrar:function(){
              axios.get(url)
              .then(response =>{
                this.empresas = response.data;                   
              })
            },
            crear:function(){
                let parametros = { nombre:this.empresa.nombre, correo:this.empresa.correo, logotipo:this.empresa.logotipo };                
                axios.post(url, parametros)
                .then(response =>{
                  this.mostrar();
                });     
                this.empresa.nombre="";
                this.empresa.correo="";
                this.empresa.logotipo="";
            },                        
            editar: function(){
            let parametros = { id:this.empresa.id, nombre:this.empresa.nombre, correo:this.empresa.correo, logotipo:this.empresa.logotipo};                            
            //console.log(parametros);                   
                 axios.put(url+this.empresa.id, parametros)                            
                  .then(response => {                                
                     this.mostrar();
                  })                
                  .catch(error => {
                      console.log(error);            
                  });
            },
            borrar:function(id){
             Swal.fire({
                title: '¿Confirma eliminar el registro?',   
                confirmButtonText: `Confirmar`,                  
                showCancelButton: true,                          
              }).then((result) => {                
                if (result.isConfirmed) {      
                      //procedimiento borrar
                      axios.delete(url+id)
                      .then(response =>{           
                          this.mostrar();
                       });      
                      Swal.fire('¡Eliminado!', '', 'success')
                } else if (result.isDenied) {                  
                }
              });              
            },

            //Botones y formularios
            guardar:function(){
              if(this.operacion == 'crear'){
                this.crear();                
              }
              if(this.operacion == 'editar'){ 
                this.editar();                           
              }
              this.dialog=false;                        
            }, 
            formNuevo:function () {
              this.dialog=true;
              this.operacion='crear';
              this.empresa.nombre='';                           
              this.empresa.correo='';
              this.empresa.logotipo='';
            },
            formEditar:function( id, nombre, correo, logotipo ){              

              this.empresa.id = id; 
              this.empresa.nombre = nombre;                            
              this.empresa.correo = correo;    
              this.empresa.telefono = logotipo;                    
              this.dialog=true;                            
              this.operacion='editar';
            }
       },
         
    });
  </script>
</body>
</html> 