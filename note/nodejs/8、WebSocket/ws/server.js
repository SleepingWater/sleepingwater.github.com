var express = require('express')
	,wsio = require('websocket.io')

//create express app
var app = express();

//attach websocket server
var ws = wsio.attach(app);

//server your code
app.use(express.static('public'));

//listenin on connections
ws.on('connection',function(socket){
	socket.on('message',function(msg){
		console.log('got '+msg);
		socket.send('pong');
	});
});

app.listen(3000);