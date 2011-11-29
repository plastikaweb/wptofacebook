<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
<title><?php echo $data->title;?></title>
<script
	src="http://connect.facebook.net/en_US/all.js#appId=<?php echo $data->fb_app_id;?>&amp;xfbml=1"></script>
<link rel="stylesheet" type="text/css" media="all"
	href="<?php echo WP_PLUGIN_URL . '/wp-to-fb/tpls/reset.css'; ?>" />
<link rel="stylesheet" type="text/css" media="all"
	href="<?php echo WP_PLUGIN_URL . '/wp-to-fb/tpls/wptofb.css'; ?>" />
<!-- general style same for all templates -->
<?php if ( $facebook_data[ 'like' ] || ( !$facebook_data[ 'like' ] && !$data->hide_for_nofans ) ){ ?>
<!-- especific style  -->
<link rel="stylesheet" type="text/css" media="all"
	href="<?php echo WP_PLUGIN_URL . '/wp-to-fb/tpls/'.$data->tpl.'/style.css'; ?>" />

<?php };?>

</head>
<body>
	<div id="fb-root"></div>
	
	<script>
		  FB.init({
		    appId  : '<?php echo $data->fb_app_id;?>',
		    status : true, // check login status
		    cookie : true, // enable cookies to allow the server to access the session
		    xfbml  : true  // parse XFBML
		  });
		  window.fbAsyncInit = function() {
			  FB.Canvas.setSize();
		  }
		  //Do things that will sometimes call sizeChangeCallback()
		  function sizeChangeCallback() {
		  	FB.Canvas.setSize();
		  }

		  setTimeout(function(){ FB.Canvas.setSize() },1000);
		  
		</script>
	<!-- main container -->