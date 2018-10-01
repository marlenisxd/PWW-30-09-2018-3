function doItemUnlock(like_req_url)
{
    if($("#is_liked").val() == 1)
    {
        return true;
    }
    else
    {
        jQuery.ajax({
            type: "GET",
            url: like_req_url,
            data: "",
            dataType:  'json',
            success: function(responseHTML)
            {
                if(responseHTML.code == 1)
                {
                    window.location = responseHTML.url;
                }
            }
        });
    }
}

function doItemlock(like_req_url)
{
    jQuery.ajax({
        type: "GET",
        url: like_req_url + '&lock=1',
        data: "",
        dataType:  'json',
        success: function(responseHTML)
        {
            if(responseHTML.code == 1)
            {
                window.location = responseHTML.url;
            }
        }
    });
}

function gplus_callback(response) {
    if (response.state == 'on') {
        doItemUnlock($("#url_request").val());
    }
    else
    {
         doItemlock($("#url_request").val());
    }
}

try
{
    if(FB != undefined){
        FB.Event.subscribe('edge.create', function(href, widget) {
              doItemUnlock($("#url_request").val());
        });
        
         FB.Event.subscribe('edge.remove', function(href, widget) {
            doItemlock($("#url_request").val());
        });
    }
}catch(err){}


try
{
    if(twttr != undefined)
    {
        twttr.events.bind('tweet', function(event) {
             doItemUnlock($("#url_request").val());
        });
    }
}catch(err){}
