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
            // to access variables
            include 'funcAux/init_varArchRep3D.php';
            
            // to access DB
            include 'funcAux/funcAux_BD.php';
            include 'funcAux/init_funcAux.php';
                        
            
            // to access Classes
            include 'Classes/edificio.class.php';  
            include 'Classes/pormenor.class.php';
            include 'Classes/parteEdificio.class.php';
            
            if( null !== filter_input( INPUT_POST, 'edificio')){
                $ed_id = filter_input( INPUT_POST, 'edificio');
            }else{
                $ed_id=1;
            }            
            
            // load all data of the 'edificio'
            $option = setup_OptionOf3DHOPsetScene($ed_id, $ed, $partesEdificio, $numPartesEdificio, $pormenoresEdificio);
                      
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
                        <h3 id="tit_parteEdificio"></h3>
                        <p id="des_parteEdificio"></p>
                    </table>
                    <table>
                        <h4 id="tit_pormenor"></h4>
                        <p id="des_pormenor"></p>
                    </table>
                </div>
                <div class="col-2" style="background-color: lightgray">
                    <form role="form" name="form_ed" method="post" action="index.php">
                        <div class="form-group">
                            <?php
                                // determine the number of "edificios" in the DB
                                $numEd = edificio::totalNumber_ed();
                            ?>
                            <label for="edi">Select 3D representation:</label>
                            <select multiple class="form-control" id="edi" name="edificio">
                            <?php
                                for ( $i=0; $i<$numEd; $i++){
                            ?>
                                <option><?php echo ($i+1) ?></option>
                            <?php
                                }
                            ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Select</button>
                    </form>                    
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
                
        var edificio = <?php echo json_encode( $ed ); ?>; 
        var numPartesEdificio = <?php echo json_encode($numPartesEdificio)?>;                
        var partesEdificio = <?php echo json_encode( $partesEdificio ); ?>;                
        var pormenor = <?php echo json_encode( $pormenoresEdificio ); ?>;

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
                                    
        
        function onEnterInstance( id ) {
            
            if ( numPartesEdificio > 0 ){
                document.getElementById('tit_parteEdificio').innerHTML = partesEdificio[id-1].parteEdificio.nome_parteEdificio; 
                document.getElementById('des_parteEdificio').innerHTML = partesEdificio[id-1].parteEdificio.desc_parteEdificio;
            }
            else{
                document.getElementById('tit_parteEdificio').innerHTML = edificio.edificio.name_ed; 
                document.getElementById('des_parteEdificio').innerHTML = edificio.edificio.description_ed;
            }
            
        }
        
        function onLeaveInstance(){
            document.getElementById('tit_parteEdificio').innerHTML = " "; 
            document.getElementById('des_parteEdificio').innerHTML = " ";           
        } 
        
        function onPickedSpot(id) {
            document.getElementById('tit_pormenor').innerHTML = pormenor[id-1].pormenor.name_por; 
            document.getElementById('des_pormenor').innerHTML = pormenor[id-1].pormenor.description_por;          
        }
        
        function onLeaveSpot(){
            document.getElementById('tit_pormenor').innerHTML = " "; 
            document.getElementById('des_pormenor').innerHTML = " ";
        }   

        $(document).ready(function(){
                init3dhop();

                setup3dhop();    
                
                resizeCanvas(1100, 750);
        });
    </script>        
    </body>
</html>
