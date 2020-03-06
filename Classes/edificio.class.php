<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of edificio
 *
 * @author pberna
 */
class edificio {
    
    // Variáveis de instância
    private $id_ed;
    private $name_ed;
    private $description_ed;
    private $modelPath_ed;

    // Constructer class edificio
    public function __construct() {       
    }
    
    // Getters
    public function getId_ed() {
        return $this->id_ed;
    }

    public function getName_ed() {
        return $this->name_ed;
    }

    public function getDescription_ed() {
        return $this->description_ed;
    }
    
    public function getModelPath_ed() {
        return $this->modelPath_ed;
    }

    // Setters
    public function setModelPath_ed($modelPath_ed) {
        $this->modelPath_ed = $modelPath_ed;
    }

    public function setId_ed($id_ed) {
        $this->id_ed = $id_ed;
    }

    public function setName_ed($name_ed) {
        $this->name_ed = $name_ed;
    }

    public function setDescription_ed($description_ed) {
        $this->description_ed = $description_ed;
    }
    
    // Database access    

    public function getAllInfo_ed( $id_edificio ){
        
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysql_query( 'SELECT * FROM edificio WHERE id='.$id_edificio );

        $row = mysql_fetch_array($result);
        
        //id of the object
        $this->setId_ed($row['id']);

        //name of the object
        $this->setName_ed($row['nome']);         
        
        // description of the object
        $this->setDescription_ed($row['descricao']);
    
        // path to the 3D model of the object
        $this->setModelPath_ed($row['caminhoModelo']);
        
        mysql_close();
    }
    
    public function getAllAlcados_ed(){

        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
        
        // select data
        $result = mysql_query( 'SELECT * FROM alcados_edificio WHERE id_edificio='.$this->getId_ed() );
        
        // set alcados array
        $idAlcados[] = NULL;
        $i = 0;
        while ( $row = mysql_fetch_array( $result ) ) {
            $idAlcados[$i] = $row['id_alcado'];
            $i++;
        }

        // close DB connection
        mysql_close( $con );        
     
        // return alcados array
        return $idAlcados;        
    }
    
    public function getSelectedAlcados_ed( $selection_id ){
        
    }
    
    public function getAllPlanos_ed(){

        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
        
        // select data
        $result = mysql_query( 'SELECT * FROM planos_edificio WHERE id_edificio='.$this->getId_ed() );
        
        // set planos array
        $idPlanos[] = NULL;
        $i = 0;
        while ( $row = mysql_fetch_array( $result ) ) {
            $idPlanos[$i] = $row['id_plano'];
            $i++;
        }

        // close DB connection
        mysql_close( $con );        
     
        // return planos array
        return $idPlanos;        
    }
    
    public function getSelectedPlanos_ed( $selection_id ){
        
    }
    
    public function getAllVaos_ed(){
         // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
        
        // select data
        $result = mysql_query( 'SELECT * FROM vaos_edificio WHERE id_edificio='.$this->getId_ed() );
        
        // set planos array
        $idVaos[] = NULL;
        $i = 0;
        while ( $row = mysql_fetch_array( $result ) ) {
            $idVaos[$i] = $row['id_vao'];
            $i++;
        }

        // close DB connection
        mysql_close( $con );        
     
        // return planos array
        return $idVaos;               
    }
    
    public function getSelectedVaos_ed ( $selection_id ){
        
    }
        
    public function jsonSerialize(){
        return [
            'edificio' => [
                'id_ed' => $this->id_ed,
                'name_ed' => $this->name_ed,
                'description_ed' => $this->description_ed,
                'modelPath_ed' => $this->modelPath_ed
            ]
        ];
    }    

    // static functions
    
    public static  function isEmpty_ed(){
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysql_query( 'SELECT COUNT(*) as NumEdi FROM edificio');
        
        // count the number of objects in the table
        $row = mysql_fetch_assoc($result);      
        
        // close the DB
        mysql_close();
        
        //return the number of objects in the table
        return $row['NumEdi'];
        
    }// end of isEmpty_ed()    
    
}
