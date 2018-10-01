<?PHP
class clsSocialLikeLocker
{
    /*For Cookie or Session Storage*/
    private $storage_key = 'social_like_locker_data';
    
    private static $cookie_lifetime = 5000;
    private static $cookie_path = '/';
    private static $cookie_domain = '';
    private static $cookie_secure = false;
    private static $cookie_httponly = false;
    private static $hash_cookie = false;

    private static $session_lifetime = 5000;
    private static $session_path = '/';
    private static $session_domain = '';
    private static $session_secure = false;
    private static $session_httponly = false;
    private static $hash_storage_key = false;

    /**
     * @param array $session_vars An array of values that set how the class functions.
        * 	-'cookie_path' _string_: The path where the cookie is to be stored
        * 	-'cookie_domain' _string_: The domain the that the cookie resides on
        * 	-'cookie_secure' _boolean_: Access the cookie only over an secure connection
        * 	-'cookie_httponly' _boolean_: Write to the cookie only over an http(s) connection
        * 	-'cookie_lifetime' _int_: The amount of time the cookie is active for
        * 	-'hash_cookie' _boolean_ :Hash the cookie to its value is not easily readable
        * 	-'hash_session' _boolean: Has a season so its value is not easily readable
        * 	-'session_name' _string_ : Name of the current session
        * 	-'session_lifetime' _int_: The life time of the session, in seconds
        * 	-'session_path' _string_: The path of the session.
        * 	-'session_domain' _string_: The domain of the session. Default is current.
        * 	-'session_secure'_boolean_: Access the session only over a secure connection
        * 	-'session_httponly' _boolean: Writes to the session only over an http connection
        * 	-'hash_storage_key' _boolean: Session/Cookie Storage key will be hashed.
        * 	-'storage_key' _string: Session/Cookie Key to Store Like Data
     */
    public function __construct($vars = array())
    {
        $defaults = array(
                'cookie_path' => '/', 
                'cookie_domain' => $_SERVER['HTTP_HOST'], 
                'cookie_secure' => false, 
                'cookie_httponly' => false, 
                'cookie_lifetime' => time() + 30 * 24 * 60, //one month - default
                'hash_storage_key' => true, 
                'session_lifetime' => 2000, 
                'session_path' => '/', 
                'session_domain' => $_SERVER['HTTP_HOST'], 
                'session_secure' => false, 
                'session_httponly' => false, 
                'session_start' => true,
                'storage_key' => 'social_like_locker_data'
        );

        $vars += $defaults;
        
        self::$cookie_path = $vars['cookie_path'];
        self::$cookie_domain = $vars['cookie_domain'];
        self::$cookie_secure = $vars['cookie_secure'];
        self::$cookie_httponly = $vars['cookie_httponly'];
        self::$cookie_lifetime = $vars['cookie_lifetime'];

        self::$session_path = $vars['session_path'];
        self::$session_domain = $vars['session_domain'];
        self::$session_secure = $vars['session_secure'];
        self::$session_httponly = $vars['session_httponly'];
        self::$session_lifetime = $vars['session_lifetime'];

        self::$hash_storage_key = $vars['hash_storage_key'];
        
        $this->storage_key = $vars['storage_key'];
        
        $this->_initSession();
    }
    
    private function _initSession()
    {           
        session_set_cookie_params(self::$session_lifetime, self::$session_path, self::$session_domain, self::$session_secure, self::$session_httponly);
    
         if( !session_id())
            session_start();
    }
    
       
    protected function writeSessionData(array $data)
    {
        $_SESSION[$this->getStorageKey()] = $data;
        
        return $this;
    }
    
    protected function writeCookieData(array $data)
    {
        $key = $this->getStorageKey();
        
        $_data =  json_encode($data);
        
        $_COOKIE[$key] = $_data;
        return setcookie($key, $_data, self::$cookie_lifetime, self::$cookie_path, self::$cookie_domain, self::$cookie_secure, self::$cookie_httponly);
        
    }
    
    protected function readSession()
    {
        if(isset($_SESSION[$this->getStorageKey()]))
        {
            return $_SESSION[$this->getStorageKey()];
        }
        else if(isset($_COOKIE[$this->getStorageKey()]))
        {
            return json_decode($_COOKIE[$this->getStorageKey()]);
        }
        
        return array();
    }
    
    private $_key = ''; //local code key caching
    private function getStorageKey()
    {
        if($this->_key == '')
        {
            $this->_key = $this->storage_key;
            if(self::$hash_storage_key)
            {
                $this->_key = md5($this->storage_key);
            }
        }
        
        return $this->_key;
    }
    
    protected function getLikeData()
    {
        $like_data = array();
        
        $like_data = $this->readSession();

        return $like_data;
    }
    
    public function markAsLiked($locked_url)
    {
        if($locked_url == '')
            return false;
        
        $like_data = $this->getLikeData();

        $like_data[] = md5($locked_url);
        $like_data = array_unique($like_data);
         
        $this->writeSessionData($like_data)->writeCookieData($like_data);
    }
    
    public function markAsLocked($locked_url)
    {
        if($locked_url == '')
            return false;
        
        $like_data = (array)$this->getLikeData();
        
        $hash_url = md5($locked_url);
        foreach($like_data as $k => $url)
        {
            if($hash_url == $url)
            {
                unset($like_data[$k]);
            }
        }
        
        $like_data = array_unique($like_data);
         
        $this->writeSessionData($like_data)->writeCookieData($like_data);
    }
    
    public function isAnyUrlLiked()
    {
        $like_data = $this->getLikeData();
        
        if(count($like_data) > 0)
            return true;
        return false;
    }
    
    public function isLiked($locked_url = '')
    {
        if($locked_url == '')
        {
            $locked_url = self::getCurrentURL();
        }
        
        $like_data = $this->getLikeData();
        
        $locked_url = md5($locked_url);
        
        if(is_array($like_data) == false)
            return false;
        
        if(in_array($locked_url, $like_data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function destroyLikeSession()
    {
        if(isset($_SESSION[$this->getStorageKey()]))
               unset($_SESSION[$this->getStorageKey()]);
        
        if(isset($_COOKIE[$this->getStorageKey()]))
        {
               unset($_COOKIE[$this->getStorageKey()]);
               $data = array(); // NULL DATA
               return setcookie($this->getStorageKey(), json_encode($data), time() - 4800, self::$cookie_path, self::$cookie_domain, self::$cookie_secure, self::$cookie_httponly);
        }
    }
    
    public static function getCurrentURL()
    {
         $current_url = 'http';
         if (!empty($_SERVER['HTTPS'])) {$pageURL .= "s";}  

         $current_url .= "://";
         if ($_SERVER["SERVER_PORT"] != "80") 
         {
                $current_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
         } 
         else 
         {
                 $current_url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
         }
         
         return $current_url;
    }
}
?>