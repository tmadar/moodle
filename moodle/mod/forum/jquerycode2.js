var script = document.createElement('script');script.src = "https://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js";document.getElementsByTagName('head')[0].appendChild(script);

var x = parseInt($(".posting p")[0].innerHTML);
var y = parseInt($( "input[name='countr']" ).val());

alert("You have a total of " + y + " comments");

if(y<x)
{
	$(".commands").hide();
}
else
{
	$(".commands").show();
}