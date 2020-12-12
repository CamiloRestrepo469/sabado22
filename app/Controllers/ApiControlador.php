<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ApiControlador extends ResourceController
{
    protected $modelName = 'App\Models\ModeloAnimales';
    protected $format    = 'json';

    public function index(){
        return $this->respond($this->model->findAll());
    }

  public function insertar(){
    
    //1. recibir los datos desde el cliente
    $nombre=$this->request->getPost("nombre");
    $edad=$this->request->getPost("edad");
    $tipo=$this->request->getPost("tipo");
    $descripcion=$this->request->getPost("descripcion");
    $comida=$this->request->getPost("comida");
    $raza=$this->request->getPost("raza");
    $foto=$this->request->getPost("foto");

    //2. organizar los datos que llegen de la vista
    //en un arreglo asociativo 
	  //las claves deben ser iguales a los campos o atributos de la tabla en bd

	   $datosEnvio=array(
        "nombre"=>$nombre,
        "edad"=>$edad,
        "tipo"=>$tipo,
        "descripcion"=>$descripcion,
        "comida"=>$comida,
        "raza"=>$raza,
        "foto"=>$foto
     );
                    
    //3. utilizar el atributo this->validate dek controlador para validar datos
      
    if($this->validate('animalesPOST')){
      try{
          $id=$this->model->insert($datosEnvio);
      return $this->respond($this->model->find($id));

      }catch(\Exception $error ){
        echo ($error->getMessage()); 
      }
                   
     }else{
                        
          $validation = \Config\Services::validation(); 
          return $this->respond($validation->getErrors());             
         }


  }

  public function eliminar($id){

    $consultar=$this->model->where('id',$id)->delete();
    $filasAfectadas=$consultar->connID->affected_rows;

    if($filasAfectadas==1){
      try{
        $mensaje=array("mensaje"=>"Registro eliminado con éxito"); 
       return $this->respond(json_encode($mensaje),200); 

      }catch(\Exception $error ){
        echo ($error->getMessage()); 
      }
      
      }
      else{
         $mensaje=array("mensaje"=>"Error en el id a NO SE PUEDE ELIMINAR"); 
         return $this->respond(json_encode($mensaje),400);
         }

  }

  public function editar($id){
    //1Recibir los datos desde el cliente 
    $datosEditar=$this->request->getRawInput();

    //2 depurar arreglo de paso 1 para segmentar 
    $nombre=$datosEditar["nombre"];
    $edad=$datosEditar["edad"];

    //3 organizar los datos para envio hacia BD
    $datosEnvio=array(
      "nombre"=>$nombre,
      "edad"=>$edad
   );
   //4
   if($this->validate('animalesPUT')){
     
    try{
     $this->model->update($id,$datosEnvio);
    return $this->respond($this->model->find($id));

    }catch(\Exception $error ){
      echo ($error->getMessage()); 
    }
                      
    }else{

      $mensaje=array("mensaje"=>"No existe este registro");  
     
      return $this->respond(json_encode($mensaje),400);                    
        $validation = \Config\Services::validation(); 
        return $this->respond($validation->getErrors(),400);
                                       
        }
  }
  public function buscar($id){
    try{
     return $this->respond($this->model->find($id));
     }catch(\Exception $error ){
       echo ($error->getMessage()); 
     }

  }

}