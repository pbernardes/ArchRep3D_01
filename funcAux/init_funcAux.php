<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Set the 'option' of 'setScene(option)' if it is supposed to use spots. The trackball is set by default
 * 
 * @param string $mesh - total meshes (instances and spots) to be loaded
 * @param string $instance - total instances
 * @param string $spot - total spots 
 * 
 * @author Paulo Bernardes
 * 
 * @return string $option
 */
function set3DHOPOptions_WithSpots ( $mesh, $instance, $spot){
    
    return '{
                        meshes: {'.$mesh.'},
                        modelInstances : {'.$instance.'},
                        spots : {'.$spot.'},
                        trackball: {
                                type : SphereTrackball,
                                trackOptions : {
                                        startPhi: 35.0,
                                        startTheta: 15.0,
                                        startDistance: 2.5,
                                        minMaxPhi: [-360, 360],
                                        minMaxTheta: [-360.0, 360.0],
                                        minMaxDist: [0, 10.0]
                                }
                        }
                }';
    
} // end of set3DHOPOptions_WithSpots ( $mesh, $instance, $spot)

/**
 * Set the 'option' of 'setScene(option)' if it is NOT supposed to use spots. The trackball is set by default
 * 
 * @param string $mesh - total meshes (instances and spots) to be loaded
 * @param string $instance - total instances
 * 
 * @author Paulo Bernardes
 * 
 * @return string $option
 */
function set3DHOPOptions_WithoutSpots ( $mesh, $instance ){
    
    return '{
                        meshes: {'.$mesh.'},
                        modelInstances : {'.$instance.'},                        
                        trackball: {
                                type : SphereTrackball,
                                trackOptions : {
                                        startPhi: 35.0,
                                        startTheta: 15.0,
                                        startDistance: 2.5,
                                        minMaxPhi: [-360, 360],
                                        minMaxTheta: [-360.0, 360.0],
                                        minMaxDist: [0, 10.0]
                                }
                        }
                }';
} // end of set3DHOPOptions_WithoutSpots ( $mesh, $instance )

function setup_OptionOf3DHOPsetScene ( $id_edificio, &$ed, &$partesEdificio, &$numPartesEdificio, &$pormenoresEdificio){
    
    //local variables
    $meshes = '';    
    $modelInstances = '';
    $spots = '';
    $transColor = 'transform : { matrix: SglMat4.mul(SglMat4.translation([0, 0, 0]), SglMat4.scaling([ 1.0, 1.0, 1.0])) },
                color : [0.0, 0.25, 1.0]';
    
    $option = '';
    
    // load all data of the 'edificio'
    if( !edificio::isEmpty_ed() ){                

        $ed = new edificio();   
        $ed->getAllInfo_ed( $id_edificio );                                             

        // evaluates if 'edificio' has 'partes'
        if( !parteEdificio::isEmptyByIdEdificio_parteEdificio( $id_edificio ) ){

            // determine the number of 'partes' from 'edificio'
            $numPartesEdificio = parteEdificio::totalNumberByIdEdificio_parteEdificio( $id_edificio );                    

            // set the array of 'partes' from 'edificio'
            for( $i = 0; $i < $numPartesEdificio; $i++ ){
                $partesEdificio[$i] = new parteEdificio();
                $partesEdificio[$i]->getAllInfo_parteEdificio( $i + 1 );                      

                // set meshes from 'partes' from 'edificio'
                $meshes = $meshes.'"Par'.$partesEdificio[$i]->getId_parteEdificio().'" : { url: "'.$partesEdificio[$i]->getModelPath_parteEdificio().'" } , ';

                // set model instances
                if ( $i < ($numPartesEdificio - 1) ){
                    $modelInstances = $modelInstances.'"'.$partesEdificio[$i]->getId_parteEdificio().'" : { mesh : "Par'.$partesEdificio[$i]->getId_parteEdificio().'" } , ';
                }else{
                    $modelInstances = $modelInstances.'"'.$partesEdificio[$i]->getId_parteEdificio().'" : { mesh : "Par'.$partesEdificio[$i]->getId_parteEdificio().'" } ';   
                }
            }                                        

            if ( !pormenor::isEmpty_por() ){

                // set the number of buiding part details ('pormenor')
                $numPormenorPartesEdificio = pormenor::totalNumber_por();

                for( $i = 0; $i < $numPormenorPartesEdificio; $i++){
                    $pormenoresEdificio[$i] = new pormenor();
                    $pormenoresEdificio[$i]->getAllInfo_por($i+1);                                       

                    // set meshes from buiding parts details
                    if( $i < ($numPormenorPartesEdificio - 1) ){
                        $meshes = $meshes.'"Por'.$pormenoresEdificio[$i]->getId_por().'" : { url: "'.$pormenoresEdificio[$i]->getModelPath_por().'" } , '; 
                        $spots = $spots.'"'.$pormenoresEdificio[$i]->getId_por().'" : { mesh : "Por'.$pormenoresEdificio[$i]->getId_por().'",'.$transColor.'},';
                    }else{
                        $meshes = $meshes.'"Por'.$pormenoresEdificio[$i]->getId_por().'" : { url: "'.$pormenoresEdificio[$i]->getModelPath_por().'" } ';
                        $spots = $spots.'"'.$pormenoresEdificio[$i]->getId_por().'" : { mesh : "Por'.$pormenoresEdificio[$i]->getId_por().'",'.$transColor.'} ';
                    }                                                                                                  
                } // end FOR                

                $option = set3DHOPOptions_WithSpots( $meshes, $modelInstances, $spots );

            } // end IF                    
        }else{

            $meshes = $meshes.'"Par'.$ed->getId_ed().'" : { url: "'.$ed->getModelPath_ed().'" }  ';

            $modelInstances = $modelInstances.'"'.$ed->getId_ed().'" : { mesh : "Par'.$ed->getId_ed().'" } ';                       

            $option = set3DHOPOptions_WithoutSpots($meshes, $modelInstances);

        } // end ELSE
    }     
    
    return $option;
}