var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var users = [];//all users
var games = {};//all games
var O_X = {};// all users x or o

io.on('connection', function(socket){
	users.push(socket.id);
	console.log(users);

  	socket.on('my other event', function (data) {

    		io.sockets.to(games[socket.id]).emit('xo', data);//send to Competitor  steps of moves
    		console.log(data);
    		io.sockets.to(socket.id).emit('change', { move : false , xo : O_X[socket.id]});
    		io.sockets.to(games[socket.id]).emit('change', { move : true, xo : O_X[games[socket.id]]});
    		
    		
  	});

  	socket.on('user', function (data) {
    		console.log(data + " connected!");//if user connected show this message
  	});
  	
  	
  
for( i = 0 ;i<users.length-1;i++){
	games[users[i]] = users[i+1];// set user Competitor
	O_X[users[i]] = "O";// set user o just can move
	O_X[users[i+1]] = "X";// set user X just can move 
	games[users[i+1]] = users[i];// set user Competitor
	//console.log(games);
	i++;

}
});

http.listen(3000, function(){
  console.log('listening on *:3000');
});