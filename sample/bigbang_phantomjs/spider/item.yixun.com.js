module.exports = function (url, cb) {
    var page = require('webpage').create(); 
    page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36';

    page.onResourceRequested = function(request, network) {
	if (request.url.indexOf('.css')     !== -1
	    || request.url.indexOf('.png')  !== -1
	    // || request.url.indexOf('.jpg')  !== -1
	    // || request.url.indexOf('.jpeg') !== -1
            || request.url.indexOf('.otf')  !== -1
	    || request.url.indexOf('.swf')  !== -1
	   ) {
	    // console.log("abort " + request.url);
	    network.abort();
	}
    };

    page.onResourceReceived = function(response) {};
    
    page.open(url, function(status) {
    	if (status !== 'success') {
    	    console.log('Error: Unable to access network!');
    	} else {
    	    var data = page.evaluate(function() {
    		var json_data = {};
    		json_data.price = document.querySelector('span.mod_price.xprice_val').textContent;
    		json_data.name  = document.querySelector('h1.xname').textContent;
    		var img_url_arr = document.querySelectorAll('div#list_smallpic ul li img');
    		json_data.imgs = [];
    		for(var i in img_url_arr){
    		    if(img_url_arr[i].nodeType && (img_url_arr[i].nodeType == 1)){
    			json_data.imgs.push(img_url_arr[i].src); 
    		    }
    		}
    		return json_data;
    	    });
    	    console.log(JSON.stringify(data));
    	}
	cb();
    });
}
