var system = require("system");

function getStdin() {
    var line;
    line = system.stdin.readLine();
    var spiderModule = require('./router');
    var spider = spiderModule(line);
    if(spider === undefined) {
	console.log("not valid url");
	getStdin();
	return ;
    }
    spider(line, getStdin);
}

getStdin();
