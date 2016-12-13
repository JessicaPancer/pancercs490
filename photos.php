<?php session_start(); ?>
<html>
<body background="background.jpg">
<center>
  <?php
  $place = $_GET["place"];
    echo '<h1 id="title"> Study abroad photos of '. $place .': </h1>';
  ?>
<input action="action" type="button" value="Back" onclick="history.go(-1);" />
<br>
<div id="flickr-images"></div>
</center>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript">
	var place = "<?php echo $place;?>";
	function grabImages() {
	 var URL = 'https://api.flickr.com/services/rest/?method=flickr.places.find&api_key=b91bca9fe607c9115da17dafab2e6a08&query=' + place + '&format=rest&format=json&jsoncallback=?'; 
	 $.getJSON(URL, function(data){
	    $.each(data.places.place, function(i, item){
	      var URL = 'https://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=b91bca9fe607c9115da17dafab2e6a08&tags=studyabroad&format=rest&format=json&jsoncallback=?&place_id=' + item.place_id;
	     $.getJSON(URL, function(data){
		$.each(data.photos.photo, function(i, item){
		  // Creating the image URL. Info: http://www.flickr.com/services/api/misc.urls.html
		  var img_src = "https://farm" + item.farm + ".static.flickr.com/" + item.server + "/" + item.id + "_" + item.secret + "_m.jpg";
		  var img_thumb = $("<img/>").attr("src", img_src).css("margin", "8px")
		  $(img_thumb).appendTo("#flickr-images");
		});
	      });
	    });
	  });
	}
	grabImages();
</script>
</html>
