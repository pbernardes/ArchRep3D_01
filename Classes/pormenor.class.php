<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pormenor
 *
 * @author pberna
 */
class pormenor implements JsonSerializable {

    // Variáveis de instância
    private $id_por;
    private $name_por;
    private $description_por;
    private $modelPath_por;

    public function __construct() {
        
    }

    public function getId_por() {
        return $this->id_por;
    } // end of getId_por()

    public function getName_por() {
        return $this->name_por;
    } // end of getName_por()

    public function getDescription_por() {
        return $this->description_por;
    } // end of getDescription_por()

    public function getModelPath_por() {
        return $this->modelPath_por;
    } // end of getModelPath_por()

    public function setId_por( $id_por ) {
        $this->id_por = $id_por;
    } // end of setId_por( $id_por )

    public function setName_por( $name_por ) {
        $this->name_por = $name_por;
    } // end of setName_por( $name_por )

    public function setDescription_por( $description_por ) {
        $this->description_por = $description_por;
    } // end of setDescription_por( $description_por )

    public function setModelPath_por( $modelPath_por ) {
        $this->modelPath_por = $modelPath_por;
    } // end of setModelPath_por( $modelPath_por )

    // Database access    
    
    /**
     * 
     * @param int $id_pormenor
     * 
     * @author Paulo Bernardes
     */
    public function getAllInfo_por( $id_pormenor ){
        
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysqli_query( $con, 'SELECT * FROM pormenor WHERE id_pormenor='.$id_pormenor );

        $row = mysqli_fetch_array($result);
        
        //id of the object
        $this->setId_por($row['id_pormenor']);

        //name of the object
        $this->setName_por($row['nome_pormenor']);         
        
        // description of the object
        $this->setDescription_por($row['desc_pormenor']);
    
        // path to the 3D model of the object
        $this->setModelPath_por($row['caminhoModelo_pormenor']);
        
        mysqli_close( $con );
    } // end of getAllInfo_por( $id_pormenor )
    
    /**
     * 
     * @return string array
     * 
     * @author Paulo Bernardes
     * 
     */
    public function jsonSerialize(){
        return [
            'pormenor' => [
                'id_por' => $this->id_por,
                'name_por' => $this->name_por,
                'description_por' => $this->description_por,
                'modelPath_por' => $this->modelPath_por    
            ]
        ];
    } // end of jsonSerialize()
    
    // static functions
    
    /**
     * 
     * @return boolean
     * 
     * @author Paulo Bernardes
     */
    public static function isEmpty_por(){
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysqli_query( $con, 'SELECT COUNT(*) as NumPor FROM pormenor');
        
        // count the number of objects in the table
        $row = mysqli_fetch_assoc( $result );      
        
        // close the DB
        mysqli_close( $con );
        
        //return the number of objects in the table
        return ( $row['NumPor'] == 0 );        
    } // end of isEmpty_ed()
      
    /**
     * 
     * @return int - number of elements in the pomenor table
     * 
     * @author Paulo Bernardes
     * 
     */
    public static function totalNumber_por(){
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysqli_query( $con, 'SELECT COUNT(*) as NumPor FROM pormenor');
        
        // count the number of objects in the table
        $row = mysqli_fetch_assoc( $result );      
        
        // close the DB
        mysqli_close( $con );
        
        //return the number of objects in the table
        return $row['NumPor'];        
    } // end of totalNumber_por()
}
