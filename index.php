<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta content="charset=UTF-8"/>
        <title>Archaeological 3D Representation</title>
        <!--STYLESHEET-->
        <link type="text/css" rel="stylesheet" href="bootstrap-4.4.1-dist/css/bootstrap.css"/>
        <link type="text/css" rel="stylesheet" href="stylesheet/3dhop.css"/>  
        <!--SPIDERGL-->
        <script type="text/javascript" src="js/3DHOP/spidergl.js"></script>
        <!--JQUERY-->
        <script type="text/javascript" src="js/3DHOP/jquery.js"></script>
        <!--PRESENTER-->
        <script type="text/javascript" src="js/3DHOP/presenter.js"></script>
        <!--3D MODELS LOADING AND RENDERING-->
        <script type="text/javascript" src="js/3DHOP/nexus.js"></script>
        <script type="text/javascript" src="js/3DHOP/ply.js"></script>
        <!--TRACKBALLS-->
        <script type="text/javascript" src="js/3DHOP/trackball_sphere.js"></script>
        <script type="text/javascript" src="js/3DHOP/trackball_turntable.js"></script>
        <script type="text/javascript" src="js/3DHOP/trackball_turntable_pan.js"></script>
        <script type="text/javascript" src="js/3DHOP/trackball_pantilt.js"></script>
        <!--UTILITY-->
        <script type="text/javascript" src="js/3DHOP/init.js"></script>       
    </head>
    <body>        
        <?php
            // to access DB
            include 'funcAux/funcAux_BD.php';
            
            // to access variables
            include 'funcAux/init_varArchRep3D.php';
            
            // to access Classes
            include 'Classes/edificio.class.php';  
            include 'Classes/pormenor.class.php';
            include 'Classes/parteEdificio.class.php';
                        
                   
            // load all data of the 'edificio'
            if( edificio::isEmpty_ed() > 0 ){

                $ed = new edificio();   
                $ed->getAllInfo_ed(1);                
                
                // initialize the array of building parts
                $partesEdificio[] = NULL;
                
                // initialize the array of buiding parts detaisl
                $pormenoresEdificio[] = NULL;

                // evaluates if building has parts
                if( parteEdificio::isEmptyByIdEdificio_parteEdificio(1) > 0 ){
                    
                    // set the number of bulding parts
                    $numPartesEdificio = parteEdificio::isEmptyByIdEdificio_parteEdificio(1);                    
                    
                    // set the array of buiding parts
                    for( $i =0; $i<$numPartesEdificio; $i++){
                        $partesEdificio[$i] = new parteEdificio();
                        $partesEdificio[$i]->getAllInfo_parteEdificio(($i+1));                      
                       
                        // set meshes from buiding parts
                        $meshes = $meshes.'"Par'.$partesEdificio[$i]->getId_parteEdificio().'" : { url: "'.$partesEdificio[$i]->getModelPath_parteEdificio().'" } , ';
                       
                        // set model instances
                        if ( $i < ($numPartesEdificio-1) ){
                            $modelInstances = $modelInstances.'"'.$partesEdificio[$i]->getId_parteEdificio().'" : { mesh : "Par'.$partesEdificio[$i]->getId_parteEdificio().'" } , ';
                        }else{
                            $modelInstances = $modelInstances.'"'.$partesEdificio[$i]->getId_parteEdificio().'" : { mesh : "Par'.$partesEdificio[$i]->getId_parteEdificio().'" } ';   
                        }
                    }
                    
                }else{
                    // what happens if the buiding has no parts...
                }
            }
            else{
                echo 'Lista de edificios vazia...';
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
                        $spots = $spots.'"Por'.$pormenoresEdificio[$i]->getId_por().'" : { mesh : "Por'.$pormenoresEdificio[$i]->getId_por().'",'.$transColor.'},';
                    }else{
                        $meshes = $meshes.'"Por'.$pormenoresEdificio[$i]->getId_por().'" : { url: "'.$pormenoresEdificio[$i]->getModelPath_por().'" } ';
                        $spots = $spots.'"Por'.$pormenoresEdificio[$i]->getId_por().'" : { mesh : "Por'.$pormenoresEdificio[$i]->getId_por().'",'.$transColor.'} ';
                    }                                           
                     
                } // end FOR                
            } // end IF
            else {
                //echo 'Lista de pormenores vazia...';
            }            
           
            $option = '{
                        meshes: {'.$meshes.'},
                        modelInstances : {'.$modelInstances.'},
                        spots : {'.$spots.'},
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
                       
        ?>
        <div class="jumbotron text-center" style="padding: 5px 5px 5px 5px">
            <h1>3D Representation of Archaeology of Architecture</h1>  
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-7">
                    <div id="3dhop" class="tdhop" onmousedown="if (event.preventDefault) event.preventDefault()">
                        <div id="toolbar">
                            <img id="home"       title="Home"                  src="skins/dark/home.png"            /><br/>
                            <img id="zoomin"     title="Zoom In"               src="skins/dark/zoomin.png"          /><br/>
                            <img id="zoomout"    title="Zoom Out"              src="skins/dark/zoomout.png"         /><br/>
                            <!--img id="light_on"   title="Disable Light Control" src="skins/dark/lightcontrol_on.png" style="position:absolute; visibility:hidden;"/-->
                            <!--img id="light"      title="Enable Light Control"  src="skins/dark/lightcontrol.png"    /><br/-->
                            <img id="hotspot_on" title="Hide Hotspots"         src="skins/dark/pin_on.png"          style="position:absolute; visibility:hidden;"/>
                            <img id="hotspot"    title="Show Hotspots"         src="skins/dark/pin.png"             /><br/>
                            <!--img id="full_on"    title="Exit Full Screen"      src="skins/dark/full_on.png"         style="position:absolute; visibility:hidden;"/>
                            <img id="full"       title="Full Screen"           src="skins/dark/full.png"            /-->
                        </div>
                            <canvas id="draw-canvas" style="background-color: whitesmoke"/>
                    </div> 
                    <div id="tdhlg"></div>
                </div>
                <div class="col-3" style="background-color: gray">                   
                    <table>
                        <h3 id="tit"></h3>
                        <p id="des"></p>
                    </table>
                </div>
                <div class="col-2" style="background-color: lightgray">
                    <table>
                        <h3 id="titi"></h3>
                        <p id="desi"></p>
                    </table>
                </div>
            </div>
            <div class="row">
                <center>
                    <fieldset>
                        <legend>Compose The Scene</legend>
                        <?php
                            for( $i=0; $i<$numPartesEdificio; $i++ ){
                                ?>
                        <input type="checkbox" checked="checked" style="cursor:hand;margin-left:40px;" onclick="presenter.toggleInstanceVisibilityByName('<?php echo $partesEdificio[$i]->getId_parteEdificio();?>', true);"> <?php echo $partesEdificio[$i]->getNome_parteEdificio(); ?></input>
                                <?php
                            }
                        ?>                            
                            <!--input type="radio" name="model" checked="checked" style="cursor:hand;margin-left:40px;" onclick="presenter.setInstanceVisibility('Group', false, false); presenter.setInstanceVisibilityByName('GargoSingle', true, true);"> Gargoyle Fixed Resolution </input-->
                            <!--input type="radio" name="model" style="cursor:hand;margin-left:40px;" onclick="presenter.setInstanceVisibility('Group', false, false); presenter.setInstanceVisibilityByName('GargoMulti', true, true);"> Gargoyle Multi Resolution </input-->
                            <!--input type="radio" name="model" style="cursor:hand;margin-left:40px;" onclick="presenter.setInstanceVisibility('Group', false, false); presenter.setInstanceVisibilityByName('Lady', true, true);"> Statue </input-->
                    </fieldset>
                </center>
            </div>
        </div>                         
    </body>

    <script type="text/javascript">
        var presenter = null;
                
        var edificio = <?php echo json_encode($ed->jsonSerialize()); ?>; 
        
        var lengh_partesEdificio = <?php echo json_encode(sizeof($partesEdificio)) ?>;
        var partesEdificio = [];
        
        partesEdificio.push(<?php echo json_encode(array_shift($partesEdificio)->jsonSerialize() ); ?>);
        partesEdificio.push(<?php echo json_encode(array_shift($partesEdificio)->jsonSerialize() ); ?>);
        partesEdificio.push(<?php echo json_encode(array_shift($partesEdificio)->jsonSerialize() ); ?>);
        partesEdificio.push(<?php echo json_encode(array_shift($partesEdificio)->jsonSerialize() ); ?>);
        partesEdificio.push(<?php echo json_encode(array_shift($partesEdificio)->jsonSerialize() ); ?>);
        partesEdificio.push(<?php echo json_encode(array_shift($partesEdificio)->jsonSerialize() ); ?>);                
        
        var lengh = <?php echo json_encode (sizeof($pormenoresEdificio)) ?>;        
        var pormenor = [];
        pormenor.push(<?php echo json_encode(array_shift($pormenoresEdificio)->jsonSerialize() ); ?>);
        pormenor.push(<?php echo json_encode(array_shift($pormenoresEdificio)->jsonSerialize() ); ?>);

        var option = <?php echo $option?>;
        
        function setup3dhop() { 
                presenter = new Presenter("draw-canvas");

                presenter.setScene( option );

                // Hide Spots
                presenter.setSpotVisibility(HOP_ALL, false, true);                                             
                
                // highlight spots
                presenter._onPickedSpot = onPickedSpot;  
                presenter._onLeaveSpot = onLeaveSpot;
                
                // activate 3D instances
                presenter._onEnterInstance = onEnterInstance;
                presenter._onLeaveInstance = onLeaveInstance;
        }

        function actionsToolbar(action) {
                if(action=='home') presenter.resetTrackball(); 
                else if(action=='zoomin') presenter.zoomIn();
                else if(action=='zoomout') presenter.zoomOut(); 
                else if(action=='light' || action=='light_on') { presenter.enableLightTrackball(!presenter.isLightTrackballEnabled()); lightSwitch(); } 
                else if(action=='hotspot'|| action=='hotspot_on') { presenter.toggleSpotVisibility(HOP_ALL, true); presenter.enableOnHover(!presenter.isOnHoverEnabled()); hotspotSwitch(); }
                else if(action=='full'  || action=='full_on') fullscreenSwitch(); 
        }
        
        function onLeaveSpot(){
            document.getElementById('tit').innerHTML = " "; document.getElementById('des').innerHTML = " ";
        }
        
        function onLeaveInstance(){
            document.getElementById('titi').innerHTML = " "; document.getElementById('desi').innerHTML = " ";           
        }                
        
        function onEnterInstance(id) {
            switch(id) {
               case '1' : document.getElementById('titi').innerHTML = partesEdificio[0].parteEdificio.nome_parteEdificio; document.getElementById('desi').innerHTML = partesEdificio[0].parteEdificio.desc_parteEdificio; break;
               case '2' : document.getElementById('titi').innerHTML = partesEdificio[1].parteEdificio.nome_parteEdificio; document.getElementById('desi').innerHTML = partesEdificio[1].parteEdificio.desc_parteEdificio; break;
               case '3' : document.getElementById('titi').innerHTML = partesEdificio[2].parteEdificio.nome_parteEdificio; document.getElementById('desi').innerHTML = partesEdificio[2].parteEdificio.desc_parteEdificio; break;
               case '4' : document.getElementById('titi').innerHTML = partesEdificio[3].parteEdificio.nome_parteEdificio; document.getElementById('desi').innerHTML = partesEdificio[3].parteEdificio.desc_parteEdificio; break;
               case '5' : document.getElementById('titi').innerHTML = partesEdificio[4].parteEdificio.nome_parteEdificio; document.getElementById('desi').innerHTML = partesEdificio[4].parteEdificio.desc_parteEdificio; break;
               case '6' : document.getElementById('titi').innerHTML = partesEdificio[5].parteEdificio.nome_parteEdificio; document.getElementById('desi').innerHTML = partesEdificio[5].parteEdificio.desc_parteEdificio; break;                   
            }
        }
        
        function onPickedSpot(id) {
          switch(id) {
             case 'Por1'   : document.getElementById('tit').innerHTML = pormenor[0].pormenor.name_por; document.getElementById('des').innerHTML = pormenor[0].pormenor.description_por;break;
             case 'Por2' : document.getElementById('tit').innerHTML = pormenor[1].pormenor.name_por; document.getElementById('des').innerHTML = pormenor[1].pormenor.description_por;break;
          }
        }

        function onPickedInstance(id) {
            switch(id) {
               case '1' : document.getElementById('titi').innerHTML = "Alçado 01"; document.getElementById('desi').innerHTML = " ";; break;
               case '2' : document.getElementById('titi').innerHTML = "Alçado 02"; document.getElementById('desi').innerHTML = " ";; break;
               case '3' : document.getElementById('titi').innerHTML = "Alçado 03"; document.getElementById('desi').innerHTML = " ";; break;
               case '4' : document.getElementById('titi').innerHTML = "Alçado 04"; document.getElementById('desi').innerHTML = " ";; break;
               case '5' : document.getElementById('titi').innerHTML = "Plano 01"; document.getElementById('desi').innerHTML = " ";; break;
               case '6' : document.getElementById('titi').innerHTML = "Plano 02"; document.getElementById('desi').innerHTML = " ";; break;                   
            }
        }

        $(document).ready(function(){
                init3dhop();

                setup3dhop();                
                resizeCanvas(1100, 750);
        });
    </script>        
    </body>
</html>
