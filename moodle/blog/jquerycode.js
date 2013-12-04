//var script = document.createElement('script');script.src = "https://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js";document.getElementsByTagName('head')[0].appendChild(script);


var name = $("div.logininfo a").html();
var commenters = $("span.user").find("a");
var size = $("span.user").find("a").size();
var requiredposts = $("div.no-overflow p");
var reqpostsize = $("div.no-overflow p").contents().size();
var numpostsmade = 0;
var t;

for (var i=0;i<size;i++)
{
	if(name == commenters[i].text)
	{
		numpostsmade++;
	}
}

alert("You have made " + numpostsmade + " comments");

for	(var i=0;i<reqpostsize;i++)
{
	var b = requiredposts[i];
	var c = $(b);
	var d = c.html();

	if(numpostsmade < d)
	{
		t = $("div.comment-area")[i];
		var x = $(t);
		x.hide();
	}
}
