<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>test</title>
    <meta property="fb:app_id" content="1682131928749951" />

  </head>
  <body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <div id="fb-root"></div>
    <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '1682131928749951',
        xfbml      : true,
        version    : 'v2.9'
      });
      FB.AppEvents.logPageView();
    };
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/sk_SK/sdk.js#xfbml=1&version=v2.9";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>

    <div class="fb-like" data-layout="button" data-action="recommend" data-size="large" data-show-faces="true" data-adapt-container-width="true" data-share="true"></div>
      <div class="fb-comments" mobile="auto-detect" data-numposts="5" width='100%'></div>
  <script type="text/javascript">
        var url = 'http://<?php echo $_SERVER['SERVER_NAME'] ?>/test3.php'
        $(".fb-like").attr('data-href', url);
        $(".fb-comments").attr('data-href', url);


    </script>

  </body>
</html>
