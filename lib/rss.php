<?php
// Clase para gestionar RSS.
/*
 * Librerias de uso público que nos permiten gestionar RSS:
 * SimplePie
 * Last Rss
 * PHP Universal Feed Parser
 * 
 */
class rss{
    private static $_rutaCarpetaRSS;
    private $_ficheroCache;
    private $_titulo;
    private $_url;
    private $_tiempoCache;
    

    public function __construct($titulo,$url,$tiempo=120)
    {
       // ...../lib/../rss/
       self::$_rutaCarpetaRSS=dirname(__FILE__).DIRECTORY_SEPARATOR.'..' .DIRECTORY_SEPARATOR.'rss'.DIRECTORY_SEPARATOR;
       $this->_titulo=$titulo;
       $this->_url=$url;
       $this->_tiempoCache=$tiempo;
       $this->_ficheroCache=self::$_rutaCarpetaRSS.md5($url);
    }
    

    public function getRSS()
    {
        // Nos conectamos a la URL usando cURL.
        $curl=curl_init();
        
        // Propiedades de la conexión.
        curl_setopt($curl,CURLOPT_URL,$this->_url);
        curl_setopt($curl,CURLOPT_TIMEOUT,4);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_HEADER,false);
        curl_setopt($curl,CURL_HTTP_VERSION_1_1,true);
        curl_setopt($curl,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:18.0) Gecko/20100101 Firefox/18.0");
        
        // Ejecutamos la conexión y almacenamos el resultado.
        $textoXML=curl_exec($curl);
        
        // TODO LO ANTERIOR SE PUEDE REDUCIR A:
        // $textoXML=file_get_contents($this->_url);
        
        // Comprobamos si tenemos contenido XML.
        if ($textoXML && !empty($textoXML))
        {
            // Creamos un objeto XML a partir del string XML.
            $objetoXML=@simplexml_load_string($textoXML);
            
            $noticias="<h2>$this->_titulo</h2><h5>Actualizado: ".date('h:i:s A')."</h5><ul>";
            
            // Recorremos el objeto XML.
            foreach($objetoXML->channel->item as $item)
            {
                $noticias.="<li style='padding:4px 0;'><a href='$item->link' target='_blank'>$item->title</a></li>";
            }
            
            $noticias.="</ul>";
        }
        else
            $noticias="Error accediendo al feed RSS. Inténtelo de nuevo pasados ".($this->_tiempoCache / 60)." minutos.";
        
        
        return $noticias;
    }
    

    
    
    
    
}
?>
