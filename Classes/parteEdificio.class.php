<?php

/**
 * Description of parteEdificio
 *
 * @author pberna
 */
class parteEdificio {
    
    //variáveis de instância
    private $id_parteEdificio;
    private $nome_parteEdificio;
    private $desc_parteEdificio;
    private $tipo_parteEdifício;
    private $modelPath_parteEdificio;
    
    public function __construct() {
        
    }
    
    public function getId_parteEdificio() {
        return $this->id_parteEdificio;
    }

    public function getNome_parteEdificio() {
        return $this->nome_parteEdificio;
    }

    public function getDesc_parteEdificio() {
        return $this->desc_parteEdificio;
    }

    public function getTipo_parteEdifício() {
        return $this->tipo_parteEdifício;
    }

    public function getModelPath_parteEdificio() {
        return $this->modelPath_parteEdificio;
    }

    public function setId_parteEdificio($id_parteEdificio) {
        $this->id_parteEdificio = $id_parteEdificio;
    }

    public function setNome_parteEdificio($nome_parteEdificio) {
        $this->nome_parteEdificio = $nome_parteEdificio;
    }

    public function setDesc_parteEdificio($desc_parteEdificio) {
        $this->desc_parteEdificio = $desc_parteEdificio;
    }

    public function setTipo_parteEdifício($tipo_parteEdifício) {
        $this->tipo_parteEdifício = $tipo_parteEdifício;
    }

    public function setModelPath_parteEdificio($modelPath_parteEdificio) {
        $this->modelPath_parteEdificio = $modelPath_parteEdificio;
    }

    // Database access
    
    /** 
     * @param type $id_pE
     * @author Paulo Bernardes
     */
    public function getAllInfo_parteEdificio ( $id_pE) {
        
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysql_query( 'SELECT * FROM parte_edificio WHERE id_parte='.$id_pE );

        $row = mysql_fetch_array( $result );
        
        // id of the object
        $this->setId_parteEdificio( $row['id_parte'] );
        
        // name of the object 
        $this->setNome_parteEdificio( $row['nome_parte'] );
        
        // desciption of the object
        $this->setDesc_parteEdificio( $row['desc_parte'] );
        
        // type of the object
        $this->setTipo_parteEdifício( $row['tipo_parte'] );
        
        // path to the 3D model of the object
        $this->setModelPath_parteEdificio( $row['modelPath_parte'] );
        
        // close the DB
        mysql_close();
        
    }// end of getAllInfo_parteEdificio()
    
    public function printAllInfo_parteEdificio(){
        echo '<p> Id: '.$this->getId_parteEdificio().'</p><br>';
        echo '<p> Nome: '.$this->getNome_parteEdificio().'</p><br>';
        echo '<p> Descrição: '.$this->getDesc_parteEdificio().'</p><br>';
        echo '<p> Tipo: '.$this->getTipo_parteEdifício().'</p><br>';
        echo '<p> Model path: '.$this->getModelPath_parteEdificio().'</p><br>';
    }

    /**
     * @author Paulo Bernardes
     */
    public function getAllPormenores_parteEdificio(){
        
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );        
        
        // select data
        $result = mysql_query( 'SELECT * FROM pormenores_parte WHERE id_parte='.$this->getId_parteEdificio() );
        
        // set pormenores array
        $idPormenores[] = NULL;
        $i = 0;
        while ( $row = mysql_fetch_array( $result ) ) {
            $idPormenores[$i] = $row['id_pormenor'];
            $i++;
        } // end WHILE
        
        // close DB connection
        mysql_close( $con );        
     
        // return pormenores array
        return $idPormenores;                
        
    }// end of getAllPormenores_parteEdificio()
    
    
    public function jsonSerialize(){
        return [
            'parteEdificio' => [
                'id_parteEdificio' => $this->id_parteEdificio,
                'nome_parteEdificio' => $this->nome_parteEdificio,
                'desc_parteEdificio' => $this->desc_parteEdificio,
                'modelPath_parteEdificio' => $this->modelPath_parteEdificio,
                'tipo_parteEdificio' => $this->tipo_parteEdifício
            ]
        ];
    }
    
    // Static functions
    
    /**
     * 
     * @return int number of total objects
     * 
     * @author Paulo Bernardes
     */
    public static function isEmpty_parteEdificio(){
        
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysql_query( 'SELECT COUNT(*) AS NumPartes FROM parte_edificio');
        
        $row = mysql_fetch_assoc($result);
        
        mysql_close();
        
        return $row['NumPartes'];                        
    }// end of isEmpty_parteEdificio()
    
    /**
     * 
     * @param int $id_edificio
     * @return int number of objects
     * 
     * @author Paulo Bernardes
     */
    public static function isEmptyByIdEdificio_parteEdificio( $id_edificio ){
        
        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );
 
        // select data
        $result = mysql_query( 'SELECT COUNT(*) AS NumPartes FROM partes_edificio WHERE id_edificio='.$id_edificio);
        
        // set number of 'partes'
        $row = mysql_fetch_assoc($result);
        
        // close database
        mysql_close();
        
        // return number of 'partes'
        return $row['NumPartes'];                        
    }// end of isEmptyByIdEdificio_parteEdificio( $id_edificio )   
    
    /**
     * 
     * @return string array $nomeEdificio - names of parte_edificio
     * 
     * @author Paulo Bernardes
     */
    public static function getNomesEdificios_parteEdificio(){

        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );        
        
        // select data
        $result = mysql_query( 'SELECT * FROM parte_edificio ORDER BY nome_parte');
        
        // set pormenores array
        $nomeEdificio[] = NULL;
        $i = 0;
        while ( $row = mysql_fetch_array( $result ) ) {
            $nomeEdificio[$i] = $row['nome_parte'];
            $i++;
        } // end WHILE
        
        // close DB connection
        mysql_close( $con );        
     
        // return pormenores array
        return $nomeEdificio;                        
    }
    
    public static function getIdEdificios_parteEdificio(){

        // create DB connection
        $con = connectDatabase();

        // select DB
        selectDatabase( $con );        
        
        // select data
        $result = mysql_query( 'SELECT * FROM parte_edificio ORDER BY nome_parte');
        
        // set pormenores array
        $idEdificio[] = NULL;
        $i = 0;
        while ( $row = mysql_fetch_array( $result ) ) {
            $idEdificio[$i] = $row['id_parte'];
            $i++;
        } // end WHILE
        
        // close DB connection
        mysql_close( $con );        
     
        // return pormenores array
        return $idEdificio;                                
    }
}
