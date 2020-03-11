<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$meshes='';
$modelInstances='';
$spots='';
$transColor = 'transform : { matrix: SglMat4.mul(SglMat4.translation([0, 0, 0]), SglMat4.scaling([ 1.0, 1.0, 1.0])) },
                color : [0.0, 0.25, 1.0]';

function set3DHOPOptions ( $mesh, $instance, $spot){
    
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
    
}