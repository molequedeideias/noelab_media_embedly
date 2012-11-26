$(function() {


$("div.elgg-body").embedly({key:'xxxxxxxxxxxxxxxxxxxxxx'}, function(oembed, dict){
    if ( oembed == null)
      return;
    var output = "<li class='embedly'><div class='elgg-grid clearfix'><div class='elgg-col elgg-col-1of5'><div class='elgg-inner pvm prl'><a href='#'><img class='medias-thumb-oembed' src='"+oembed.thumbnail_url+"' /></a></div></div><div class='elgg-col elgg-col-4of5'><div class='elgg-inner pvm'><h4>"+oembed.title+"</h4><span>Type: "+oembed.type+" | Provider: "+oembed.provider_name+" | Posted by: "+oembed.author_name+"</span><p>"+oembed.description+"</p></div></div></div></li>";
    output += oembed['code'];
		$(dict["node"]).parent().html( output );
  })
  $('.embedly').live("click", function(e){
    e.preventDefault();
    $(this).parents('li').find('.embed').toggle();
  });                                                 


//end function
});