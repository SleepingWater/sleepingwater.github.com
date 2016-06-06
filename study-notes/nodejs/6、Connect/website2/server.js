var connect = require('connect')
/**
*	创建服务器
**/
var server = connect.createServer();

/**
*	处理静态文件
**/
server.user(connect.static(__dirname + '/website'));

/**
*	监听
**/
server.listen(3000);