module.exports = function (url, cb) {
    var page = require('webpage').create(); 
    page.settings.userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36';

    page.onResourceRequested = function(request, network) {
	if (request.url.indexOf('.css')     !== -1
	    || request.url.indexOf('.png')  !== -1
	    || request.url.indexOf('.jpg')  !== -1
	    || request.url.indexOf('.jpeg') !== -1
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
    		json_data.price = document.querySelector("div#price table.a-lineitem tr span#priceblock_ourprice")
		    ? document.querySelector("div#price table.a-lineitem tr span#priceblock_ourprice").textContent
		    : document.querySelector("div#price table.a-lineitem:first-child .a-span12").textContent.replace(/(^\s*)|(\s*$)/g, "");
		console.log(json_data.price);
    		json_data.name  = document.querySelector("h1#title").textContent.replace(/(^\s*)|(\s*$)/g, "");
    		var s_img_url_arr = document.querySelectorAll("div#altImages ul li img");
		var m_img_url_arr = document.querySelectorAll("div#main-image-container ul li img");
    		json_data.s_imgs = [];
		json_data.m_imgs = [];
    		for(var i in s_img_url_arr){
    		    if(s_img_url_arr[i].nodeType && (s_img_url_arr[i].nodeType == 1)){
    			json_data.s_imgs.push(s_img_url_arr[i].src); 
    		    }
    		}
    		for(var i in m_img_url_arr){
    		    if(m_img_url_arr[i].nodeType && (m_img_url_arr[i].nodeType == 1)){
    			json_data.m_imgs.push(m_img_url_arr[i].src); 
    		    }
    		}
    		return json_data;
    	    });
    	    console.log(JSON.stringify(data));
    	}
	cb();
    });
}
