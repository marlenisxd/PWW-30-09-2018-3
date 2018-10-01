<?PHP
function isAjax() {
  return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

if(isAjax())
{
    include"lib/clsSocialLikeLocker.php";

    $url = urldecode($_GET['url']);
    $lock_again = (int)($_GET['lock']);

    $oclsSocialLikeLocker = new clsSocialLikeLocker();
    
    $array = array();
    
    if($lock_again == 0)
    {
        if($url != '')
        {
            $oclsSocialLikeLocker->markAsLiked($url);   

            $array['code'] = 1;
            $array['url'] = $url;
        }
        else
        {
            $array['code'] = 0;
            $array['url'] = $url;
        }
    }
    else
    {
        $oclsSocialLikeLocker->markAsLocked($url); 
        $array['code'] = 1;
        $array['url'] = $url;
    }
    
    echo json_encode($array);
}
?>