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
    } // end of getId_ed()

    public function getName_ed() {
        return $this->name_ed;
    } // end of getName_ed()

    public function getDescription_ed() {
        return $this->description_ed;
    } // end of getDescription_ed()
    
    public function getModelPath_ed() {
        return $this->modelPath_ed;
    } // end of getModelPath_ed()

    // Setters
    public function setModelPath_ed( $modelPath_ed ) {
        $this->modelPath_ed = $modelPath_ed;
    } // end of setModelPath_ed( $modelPath_ed )

    public function setId_ed( $id_ed ) {
        $this->id_ed = $id_ed;
    } // end of setId_ed( $id_ed )

    public function setName_ed( $name_ed ) {
        $this->name_ed = $name_ed;
    } // end of setName_ed( $name_ed )

    public function setDescription_ed( $description_ed ) {
        $this->description_ed = $description_ed;
    } // end of setDescription_ed( $description_ed )
    
    // Database access    

    /**
     * 
     * @param int $id_edificio
     * 
     * @author Paulo Bernardes
     */
    public function getAllInfo_ed( $id_edificio ){
        
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysqli_query( $con, 'SELECT * FROM edificio WHERE id='.$id_edificio );

        $row = mysqli_fetch_array( $result );
        
        //id of the object
        $this->setId_ed($row['id']);

        //name of the object
        $this->setName_ed($row['nome']);         
        
        // description of the object
        $this->setDescription_ed($row['descricao']);
    
        // path to the 3D model of the object
        $this->setModelPath_ed($row['caminhoModelo']);
        
        mysqli_close( $con );
    } // end of getAllInfo_ed( $id_edificio )
    
    /**
     * 
     * @return string array
     * 
     * @author Paulo Bernardes
     */
    public function jsonSerialize(){
        return [
            'edificio' => [
                'id_ed' => $this->id_ed,
                'name_ed' => $this->name_ed,
                'description_ed' => $this->description_ed,
                'modelPath_ed' => $this->modelPath_ed
            ]
        ];
    } // end of jsonSerialize()   

    // static functions
    
    /**
     * 
     * @return int
     * 
     * @author Paulo Bernardes
     */
    public static  function isEmpty_ed(){
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysqli_query( $con, 'SELECT COUNT(*) as NumEdi FROM edificio');
        
        // count the number of objects in the table
        $row = mysqli_fetch_assoc( $result );      
        
        // close the DB
        mysqli_close( $con );
        
        //return the number of objects in the table
        return $row['NumEdi'];        
    }// end of isEmpty_ed()    
    
}
