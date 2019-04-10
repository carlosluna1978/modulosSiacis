<div id="accordion">
  <h3>Ingrese Una Matricula</h3>
  
        <form id="fmatricula">
            <div id="cabecera">
            <table width="99%" id="tmatricula" class="formDialog">    
					<tr>
					    <th>
							Materia
						</th>
                        <td>
					
						<input type="number" min="0" max="100" style="width:100px;" name="MATR_SEC_MATERIA" id="MATR_SEC_MATERIA" value="<?php echo !empty($sol->MATR_SEC_MATERIA) ? prepCampoMostrar($sol->MATR_SEC_MATERIA) : null ; ?>"  />				
						</td>
						<th>
							Aspirante
						</th>
						<td> 
						<input type="number" min="0" max="100" style="width:100px;" name="MATR_SEC_ASPIRANTE" id="MATR_SEC_ASPIRANTE" value="<?php echo !empty($sol->MATR_SEC_ASPIRANTE) ? prepCampoMostrar($sol->MATR_SEC_ASPIRANTE) : null ; ?>"  />
						</td>
						<th>
							Jornada
						</th>
						<td> 
						<input type="number" min="0" max="100" style="width:100px;" name="MATR_SEC_JORNADA" id="MATR_SEC_JORNADA" value="<?php echo !empty($sol->MATR_SEC_JORNADA) ? prepCampoMostrar($sol->MATR_SEC_JORNADA) : null ; ?>"  />
						</td>
					</tr>
					<tr>
						<th>
							Persona
						</th>
                        <td>
					    	<input type="number" min="0" max="100" style="width:100px;" name="MATR_SEC_PERSONA" id="MATR_SEC_PERSONA" value="<?php echo !empty($sol->MATR_SEC_PERSONA) ? prepCampoMostrar($sol->MATR_SEC_PERSONA) : null ; ?>"  />				
						</td>
						<th>
							Fecha Ingreso(*)
						</th>
                        <td>
						<input type="text" style="width:100px;" name="MATR_FECHAINGRESO" id="MATR_FECHAINGRESO" value="<?php echo !empty($sol->MATR_FECHAINGRESO) ? prepCampoMostrar($sol->MATR_FECHAINGRESO) : null ;?>" /> 		
						</td>
					</tr>
						<?php if($accion=='n'|$accion=='e') : ?>                    
                            
                             <td align="center" colspan="6" class="noclass">
                                <button title="Verifique la información antes de guardar." id="co_grabar" type="submit" ><img src="./imagenes/guardar.png" width="17" height="17"/>Grabar Matricula</button>
                             </td>
                    
						<?php endif; ?>
						
                </table>
            </div>
            <input type="hidden"  name="MATR_SECUENCIAL" id="MATR_SECUENCIAL" value="<?php echo !empty($sol->MATR_SECUENCIAL) ? prepCampoMostrar($sol->MATR_SECUENCIAL) : 0 ; ?>"  />
        </form>
</div>
