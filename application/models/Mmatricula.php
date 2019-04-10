<?php
class Mmatricula extends CI_Model {
   
   //Funcion en la cual muestra cada seleccion que ingresemos
   function getdatosItems(){
        $datos = new stdClass();
        $consulta=$_POST['_search'];
        $numero=  $this->input->post('numero');
        $datos->econdicion ='MATR_ESTADO<>1';
		$user=$this->session->userdata('US_CODIGO');
        
              $datos->campoId = "ROWNUM";
			   $datos->camposelect = array("ROWNUM",
											"MATR_SECUENCIAL",
                                            "MATR_SEC_MATERIA",
                                            "MATR_SEC_ASPIRANTE",
                                            "MATR_SEC_JORNADA",
											"MATR_SEC_PERSONA",
											"MATR_FECHAINGRESO",
											"MATR_RESPONSABLE",
											"MATR_ESTADO");
			  $datos->campos = array( "ROWNUM",
                                        "MATR_SECUENCIAL",
                                        "MATR_SEC_MATERIA",
                                        "MATR_SEC_ASPIRANTE",
                                        "MATR_SEC_JORNADA",
                                        "MATR_SEC_PERSONA",
                                        "MATR_FECHAINGRESO",
                                        "MATR_RESPONSABLE",
                                        "MATR_ESTADO");
			  $datos->tabla="MATRICULA";
              $datos->debug = false;	
           return $this->jqtabla->finalizarTabla($this->jqtabla->getTabla($datos), $datos);
   }
   
   //Datos que seran enviados para la edicion o visualizacion de cada registro seleccionado
   function dataMatricula($numero){
	   $sql="select
            MATR_SECUENCIAL,
            MATR_SEC_MATERIA,
            MATR_SEC_ASPIRANTE,
            MATR_SEC_JORNADA,
            MATR_SEC_PERSONA,
            MATR_FECHAINGRESO,
            MATR_RESPONSABLE,
            MATR_ESTADO
          FROM MATRICULA WHERE MATR_SECUENCIAL=$numero";
         $sol=$this->db->query($sql)->row();
         if ( count($sol)==0){
                $sql="select
                    MATR_SECUENCIAL,
                    MATR_SEC_MATERIA,
                    MATR_SEC_ASPIRANTE,
                    MATR_SEC_JORNADA,
                    MATR_SEC_PERSONA,
                    MATR_FECHAINGRESO,
                    MATR_RESPONSABLE,
                    MATR_ESTADO
                    FROM MATRICULA WHERE MATR_SECUENCIAL=$numero";
                         $sol=$this->db->query($sql)->row();
						}
						
          return $sol;
		}

	//funcion para crear un nuevo reporte o cabecera
    function agrMatricula(){
			$sql="select to_char(SYSDATE,'DD/MM/YYYY HH24:MI:SS') FECHA from dual";		
			$conn = $this->db->conn_id;
			$stmt = oci_parse($conn,$sql);
			oci_execute($stmt);
			$nsol=oci_fetch_row($stmt);
			oci_free_statement($stmt);            
           
            $MATR_RESPONSABLE=$this->session->userdata('US_CODIGO');
			
			//VARIABLES DE INGRESO
            $MATR_SEC_MATERIA=$this->input->post('MATR_SEC_MATERIA');
            $MATR_SEC_ASPIRANTE=$this->input->post('MATR_SEC_ASPIRANTE');
            $MATR_SEC_JORNADA=$this->input->post('MATR_SEC_JORNADA');
            $MATR_SEC_PERSONA=$this->input->post('MATR_SEC_PERSONA');						
			$FECHAINGRESO =prepCampoAlmacenar($this->input->post('MATR_FECHAINGRESO'));	
            $MATR_FECHAINGRESO="TO_DATE('".$FECHAINGRESO."','DD/MM/YYYY HH24:MI:SS')";
            
			/*if (!empty($FECHAINGRESO)){
				$MATR_FECHAINGRESO ="TO_DATE('$FECHAINGRESO 00:00:00', 'dd/mm/yy HH24:MI:SS')";
				}else{
				$MATR_FECHAINGRESO =null;
				}*/

				$sql="INSERT INTO MATRICULA (
					  MATR_SEC_MATERIA,
                      MATR_SEC_ASPIRANTE,
                      MATR_SEC_JORNADA,
                      MATR_SEC_PERSONA,
                      MATR_FECHAINGRESO,
                      MATR_RESPONSABLE,
                      MATR_ESTADO) VALUES(
                            $MATR_SEC_MATERIA,
                            $MATR_SEC_ASPIRANTE,
                            $MATR_SEC_JORNADA,
                            $MATR_SEC_PERSONA,
                            $MATR_FECHAINGRESO,
                            '$MATR_RESPONSABLE',
						    0)";
      $this->db->query($sql);
            //print_r($sql);
			$MATR_SECUENCIAL=$this->db->query("select max(MATR_SECUENCIAL) SECUENCIAL from MATRICULA")->row()->SECUENCIAL;
			echo json_encode(array("cod"=>$MATR_SECUENCIAL,"numero"=>$MATR_SECUENCIAL,"mensaje"=>"Matricula: ".$MATR_SECUENCIAL.", creado con éxito"));    
	}	
    
	//funcion para editar un registro selccionado
    function editMatricula(){
			$MATR_SECUENCIAL=$this->input->post('MATR_SECUENCIAL');
			
			//VARIABLES DE INGRESO
            $MATR_SEC_MATERIA=$this->input->post('MATR_SEC_MATERIA');
            $MATR_SEC_ASPIRANTE=$this->input->post('MATR_SEC_ASPIRANTE');
            $MATR_SEC_JORNADA=$this->input->post('MATR_SEC_JORNADA');
            $MATR_SEC_PERSONA=$this->input->post('MATR_SEC_PERSONA');
			$FECHAINGRESO =prepCampoAlmacenar($this->input->post('MATR_FECHAINGRESO'));			
            $MATR_FECHAINGRESO="TO_DATE('".$FECHAINGRESO."','DD/MM/YYYY HH24:MI:SS')";
        
				$sql="UPDATE MATRICULA SET
							MATR_SEC_MATERIA=$MATR_SEC_MATERIA,
                            MATR_SEC_ASPIRANTE=$MATR_SEC_ASPIRANTE,
                            MATR_SEC_JORNADA=$MATR_SEC_JORNADA,
                            MATR_SEC_PERSONA=$MATR_SEC_PERSONA,
							MATR_FECHAINGRESO=$MATR_FECHAINGRESO,
							WHERE MATR_SECUENCIAL=$MATR_SECUENCIAL";
		 $this->db->query($sql);

		 //print_r($sql);
         echo json_encode(array("cod"=>1,"numero"=>$MATR_SECUENCIAL,"mensaje"=>": ".$MATR_SECUENCIAL.", editado con éxito"));            
    }

}
?>