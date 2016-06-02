//模块依赖
var express = require('express')
	,search = require('./search');
//创建app
var app = express.createServer()
//配置
app.set('view engine','ejs');
app.set('views',__dirname+ '/views');
app.set('view options',{layout: false});
//路由
app.get('/',function(req,res){
	res.render('index');
});
app.get('/search',function(req,res,next){
	search(req.query.q,function(err,tweets){
		if(err) return next(err);
		res.render('search',{results: tweets,search: req.query.q});
	});
});
//监听
app.listen(3000);