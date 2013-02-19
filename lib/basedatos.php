<?php
// Gestión de la base de datos MySQL.
// Ejemplo de dirname:
// La constante predefinida __FILE__ de php contiene la ruta fisica real del fichero, por ejemplo para este fichero podría ser: /var/www/amadeus/basedatos.php
// dirname ("/var/www/amadeus/basedatos.php") --> devuelve /var/www/amadeus

require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/funciones.php';

class Basedatos{
 
    /**
     *
     * @var Basedatos Contiene la instancia de Basedatos. 
     */
    private static $_instancia;
    
    /**
     *
     * @var boolean|mysqli Contiene el objeto mysqli después de que se haya 
     * establecido la conexión.
     */
    private static $_mysqli = false;
    
    
    private function __construct(){
    
    }
    
    /**
     * Crea la conexión al servidor o devuelve error parando la ejecución.
     * 
     * @return Basedatos Devuelve la referencia al objeto Basedatos.
     */
    public static function getInstancia(){
        if (! self::$_instancia instanceof self)
        {
            // Creamos una nueva instancia de basedatos.
            self::$_instancia = new self;
            
            // Creamos el objeto mysqli y lo asignamos a $_mysqli
            self::$_mysqli=@new mysqli(Config::$dbServidor, Config::$dbUsuario, Config::$dbPassword, Config::$dbDatabase);
            if (self::$_mysqli->connnect_error){
                echo "Error conectando Base Datos". self::$_mysqli->connect_error;
                self::$_mysqli=false;
                die();
            }
       }
       
       // Si la instancia ya estaba creada, la devolvemos.
       return self::$_instancia;
        
    }
    
    /**
     * Cierra una conexión activa con el servidor
     * 
     * @access public
     * @return boolean Siempre devolverá true.
     */
    public function close(){
        if (self::$_mysqli)
        {
            self::$_mysqli->close();
            self::$_mysqli=false;
        }
        return true;
    }
    
    
    
    public function insertarUsuario($nick, $password,$nombre,$apellidos,$dni,$email,$telefono){
        // Preparamos la instrucción SQL.
        $stmt=self::$_mysqli->prepare("insert into amadeus_usuarios(nick,password,nombre,apellidos,dni,email,telefono) values(?,?,?,?,?,?,?)") or die(self::$_mysqli->error);
        
       // Enlazamos los parámetros.
        $stmt->bind_param('sssssss',$nick,encriptar($password,10),$nombre,$apellidos,$dni,$email,$telefono);
        
        // Ejecutamos la instrucción
        $stmt->execute() or die(self::$_mysqli->error);
        
        return "OK";
    }
    
    
    
    
    
}
?>