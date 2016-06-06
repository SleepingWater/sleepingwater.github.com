/**
*	模块依赖
**/
var http = require('http')
	,fs = require('fs')

/**
*	创建服务器
**/
var server = http.createServer(function (req,res) {
	if('GET' == req.method && '/images' == req.url.substr(0,7) && '.jpg' == req.url.substr(-4)){
		//检查文件是否存在
		fs.stat(__dirname + req.url,function (err,stat){
			if(err || !stat.isFile()){
				res.writeHead(400);
				res.end('Not Found');
				return;
			}
			serve(__dirname + req.url,'application/jpg');
		});
	}else if('GET' == req.method && '/' == req.url){
		serve(__dirname + '/index.html','text/html');
	}else{
		res.writeHead(400);
		res.end('Not Found');
	}

	function serve(path,type){
		res.writeHead(200,{'Content-Type':type});
		fs.createReadStream(path).pipe(res);
	}
});

/**
*	监听
**/
server.listen(3000);

/*
fs.createReadStream(path).pipe(res)
等价于
fs.createReadStream(path)
	.on('data',function(data){
		res.write(data);
	})
	.on('end',function(){
		res.end();
	})
*/