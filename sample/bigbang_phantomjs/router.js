module.exports = function (requestUrl) {
    var a = document.createElement('a');
    a.href = requestUrl;
    var spiderName = a.hostname;
    var spiderFile = "./spider/" + spiderName + ".js";
    
    var fs = require("fs");
    if(fs.isFile(spiderFile)) {
	return require(spiderFile);
    } else {
	return  undefined;
    }
};
