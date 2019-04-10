<?php
class MtipoCalificacion extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='TIPCAL_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
                
            /*  if (!empty($numero)){
                  $datos->econdicion .=" AND TIPCAL_SECUENCIAL=$numero";              
				  }*/
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
											"TIPCAL_SECUENCIAL",
											"TIPCAL_SEC_MATRICULA",
											"TIPCAL_FECHAINGRESO",
											"TIPCAL_FECHAINICIO",
											"TIPCAL_FECHAFIN",
											"TIPCAL_PORCENTAJE",
											"TIPCAL_RESPONSABLE",
											"TIPCAL_ESTADO");
			  $datos->campos = array( "ROWNUM",
										"TIPCAL_SECUENCIAL",
										"TIPCAL_SEC_MATRICULA",
										"TIPCAL_FECHAINGRESO",
										"TIPCAL_FECHAINICIO",
										"TIPCAL_FECHAFIN",
										"TIPCAL_PORCENTAJE",
										"TIPCAL_RESPONSABLE",
										"TIPCAL_ESTADO");
			  $datos->tabla="TIPOCALIFICACION";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataTipoCalificacion($numero){
	   $sql="select
	   				TIPCAL_SECUENCIAL,
					TIPCAL_SEC_MATRICULA,
				    TIPCAL_FECHAINGRESO,
					TIPCAL_FECHAINICIO,
					TIPCAL_FECHAFIN,
					TIPCAL_PORCENTAJE,
					TIPCAL_RESPONSABLE,
					TIPCAL_ESTADO
          FROM TIPOCALIFICACION WHERE TIPCAL_SECUENCIAL=$numero";
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select
				TIPCAL_SECUENCIAL,
				TIPCAL_SEC_MATRICULA,
				TIPCAL_FECHAINGRESO,
				TIPCAL_FECHAINICIO,
				TIPCAL_FECHAFIN,
				TIPCAL_PORCENTAJE,
				TIPCAL_RESPONSABLE,
				TIPCAL_ESTADO
                          FROM TIPOCALIFICACION WHERE TIPCAL_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
						
          return $sol;
		}

	//funcion para crear un nuevo reporte o cabecera
    function agrTipoCalificacion(){
			$sql="select to_char(SYSDATE,'DD/MM/YYYY HH24:MI:SS') FECHA from dual";		
			$conn = $this->db->conn_id;
			$stmt = oci_parse($conn,$sql);
			oci_execute($stmt);
			$nsol=oci_fetch_row($stmt);
			oci_free_statement($stmt);            
				
			$TIPCAL_FECHAINGRESO="TO_DATE('".$nsol[0]."','DD/MM/YYYY HH24:MI:SS')";
			$TIPCAL_RESPONSABLE=$this->session->userdata('US_CODIGO');
			
			//VARIABLES DE INGRESO
			$TIPCAL_SEC_MATRICULA=$this->input->post('TIPCAL_SEC_MATRICULA');						
			$FECHAINICIO =prepCampoAlmacenar($this->input->post('TIPCAL_FECHAINICIO'));	
			$TIPCAL_FECHAINICIO="TO_DATE('".$FECHAINICIO."','DD/MM/YYYY HH24:MI:SS')";				
			$FECHAFIN=prepCampoAlmacenar($this->input->post('TIPCAL_FECHAFIN'));
			$TIPCAL_FECHAFIN="TO_DATE('".$FECHAFIN."','DD/MM/YYYY HH24:MI:SS')";				
			$TIPCAL_PORCENTAJE=prepCampoAlmacenar($this->input->post('TIPCAL_PORCENTAJE'));	
			
			if(!empty($FECHAINICIO) and !empty($FECHAFIN)){
				$TIPCAL_FECHAINICIO="TO_DATE('$FECHAINICIO 00:00:00','dd/mm/yy HH24:MI:SS')";
				$TIPCAL_FECHAFIN="TO_DATE('$FECHAFIN 23:59:59','dd/mm/yy HH24:MI:SS')";
			}else{ 							
				$TIPCAL_FECHAINICIO =null;
				$TIPCAL_FECHAFIN = null;
			}

				$sql="INSERT INTO TIPOCALIFICACION (
							TIPCAL_SEC_MATRICULA,
							TIPCAL_FECHAINGRESO,
							TIPCAL_FECHAINICIO,
							TIPCAL_FECHAFIN,
							TIPCAL_PORCENTAJE,
							TIPCAL_RESPONSABLE,
							TIPCAL_ESTADO) VALUES(
							$TIPCAL_SEC_MATRICULA,
							$TIPCAL_FECHAINGRESO,
							$TIPCAL_FECHAINICIO,
							$TIPCAL_FECHAFIN,
							$TIPCAL_PORCENTAJE,
							'$TIPCAL_RESPONSABLE',
							0)";
            $this->db->query($sql);
            //print_r($sql);
			$TIPCAL_SECUENCIAL=$this->db->query("select max(TIPCAL_SECUENCIAL) SECUENCIAL from TIPOCALIFICACION")->row()->SECUENCIAL;
			echo json_encode(array("cod"=>$TIPCAL_SECUENCIAL,"numero"=>$TIPCAL_SECUENCIAL,"mensaje"=>"TipoCalificacion: ".$TIPCAL_SECUENCIAL.", creado con éxito"));    
	}	
    
	//funcion para editar un registro selccionado
    function editTipoCalificacion(){
			$TIPCAL_SECUENCIAL=$this->input->post('TIPCAL_SECUENCIAL');

			//VARIABLES DE INGRESO
			$TIPCAL_SEC_MATRICULA=$this->input->post('TIPCAL_SEC_MATRICULA');		
			$TIPCAL_FECHAINICIO =prepCampoAlmacenar($this->input->post('TIPCAL_FECHAINICIO'));			
			$TIPCAL_FECHAFIN=prepCampoAlmacenar($this->input->post('TIPCAL_FECHAFIN'));			
			$TIPCAL_PORCENTAJE=prepCampoAlmacenar($this->input->post('TIPCAL_PORCENTAJE'));			
			
			$FECHA_INICIO=$this->input->post('TIPCAL_FECHAINICIO');
			$FECHA_FIN=$this->input->post('TIPCAL_FECHAFIN');	

			if (!empty($FECHA_INICIO) and !empty($FECHA_FIN)){
				$TIPCAL_FECHAINICIO ="TO_DATE('$FECHA_INICIO 00:00:00', 'dd/mm/yy HH24:MI:SS')";
				$TIPCAL_FECHAFIN ="TO_DATE('$FECHA_FIN 23:59:59', 'dd/mm/yy HH24:MI:SS')";              
			}else{
				$TIPCAL_FECHAINICIO =null;
				$TIPCAL_FECHAFIN = null;
			}

				$sql="UPDATE TIPOCALIFICACION SET
							TIPCAL_SEC_MATRICULA=$TIPCAL_SEC_MATRICULA,
							TIPCAL_FECHAINICIO=$TIPCAL_FECHAINICIO,
							TIPCAL_FECHAFIN=$TIPCAL_FECHAFIN,
							TIPCAL_PORCENTAJE='$TIPCAL_PORCENTAJE'
                 WHERE TIPCAL_SECUENCIAL=$TIPCAL_SECUENCIAL";
		 $this->db->query($sql);

		 //print_r($sql);
         echo json_encode(array("cod"=>1,"numero"=>$TIPCAL_SECUENCIAL,"mensaje"=>"TipoCalificacion: ".$TIPCAL_SECUENCIAL.", editado con éxito"));            
    }
}
?>