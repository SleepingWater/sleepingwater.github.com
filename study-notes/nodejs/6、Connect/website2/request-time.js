/**
* 请求时间中间件
**/
module.exports = function (opt) {
	var time = opt.time || 100;
	return function(req,res,next){
		var timer = setTimeout(function(){
			console.log('too long',req.method,req.url);
		},time);

		var end = res.end;
		res.end = function(chunk,encoding){
			res.end = end;
			res.end(chunk,encoding);
			clearTimeout(timer);
		};
		next();
	};
};