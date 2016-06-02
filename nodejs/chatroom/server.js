var express = require('express');
var app  = express();
var http  = require('http').Server(app);//没懂
var io = require('socket.io')(http);

app.get('/',function(req,res){
	res.sendFile(__dirname + '/index.html');
});

var onlineUserCount = 0,//连接数
	onlineUsers = {};//连接者

io.on('connection',function(socket){
	socket.emit('open');//通知client已经开启socket
	//构造客户端对线
	var client = {
			socket:socket,
			name:false
	}

	//监听client chat message事件
	socket.on('chat message',function(msg){
		console.log('chat message:'+msg);
		var obj = {time:getTime()};//构建客户端返回的对象

		//如果不存在用户，则为新用户，msg为用户名；如果存在用户，msg为聊天内容
		if(!client.name){
			onlineUserCount++;
			client.name = msg;
			obj['text'] = client.name;
			obj['author'] = 'Sys';
			obj['type'] = 'welcome';
			obj['onlineUserCount'] = onlineUserCount;
			socket.name = client.name;//用户登录后设置socket.name， 当退出时用该标识删除该在线用户
			if(!onlineUsers.hasOwnProperty(client.name)){
				onlineUsers[client.name] = client.name;
			}
			obj['onlineUsers'] = onlineUsers;
			console.log(client.name+' login,当前在线人数:'+onlineUserCount);

			//返回欢迎语
			socket.emit('system',obj);
			socket.broadcast.emit('system',obj);
		}else{
			obj['text'] = msg;
			obj['author'] = client.name;//错误：如果写成client，会导致死循环 socket触发事件中又含有socket RangeError: Maximum call stack size exceeded
			obj['type'] = 'message';
			console.log(client.name+' say:'+msg);

			//io.to(socket).emit('chat message',obj);
			socket.emit('chat message',obj);
			socket.broadcast.emit('chat message',obj);
		}
	});

	//退出
	socket.on('disconnect',function(){
		if(!socket.name) return;
		onlineUserCount--;

		if(onlineUsers.hasOwnProperty(socket.name)){
			delete onlineUsers[client.name];
		}

		var obj = {
			time: getTime(),
			author: 'Sys',
			text: client.name,
			type: 'disconnect',
			onlineUsers: onlineUsers,
			onlineUserCount: onlineUserCount
		};

		socket.broadcast.emit('system',obj);
		console.log(client.name+' disconnect,当前在线人数:'+onlineUserCount);
	});
});

http.listen(3000,function(){
	console.log('start to listen 3000.....');
});

var getTime = function(){
	var date = new Date();
	return date.getHours()+":"+date.getMinutes()+":"+date.getSeconds();
}