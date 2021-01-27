<?php
if(session_status() !== 2)
{
    session_start();
}

/**
 * Class UnsetAll : Used to unset all cookie variables except for a few predefined ones
 */
class UnsetAll
{
    function unsetEverything($currentPage)
    {
        if(isset($_COOKIE["currentPageNav"]))
        {
            if($currentPage !== $_COOKIE["currentPageNav"])
            {
                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                    foreach ($cookies as $cookie) {
                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        if($name !== "currentPageNav")
                        {
                            if($name !== "PHPSESSID")
                            {
                                setcookie($name, '', time() - 1000);
                                setcookie($name, '', time() - 1000, '/');
                            }
                        }
                    }
                }
                foreach ($_SESSION as $key => $val) {
                    $loggedIn = strcmp($key ,'loggedIn');
                    if ($loggedIn ==! 0) {
                        $privilege = strcmp($key, 'privilege');
                        if ($privilege !== 0){
                            unset($_SESSION[$key]);
                        }
                    }
                }
            }
        }
    }
}
