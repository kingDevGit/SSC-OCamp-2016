var io = require('socket.io');
var Adapter = require('socket.io-redis');
var throttle = require('lodash.throttle');

var server = io(80);
var adapter = Adapter('redis:6379');
var php = adapter.pubClient;

var invokeGc = throttle(function() { global.gc() }, 60000, { 'trailing': false });
setInterval(invokeGc, 300000);

server.adapter(adapter);
server.on('connection', function(socket) {
	var sid = socket.id;
	console.log('[connection]', sid);

	socket.on('disconnect', function() {
		console.log('[disconnect]', sid);
		invokeGc();
	});
});

php.subscribe('php#action', function(err) {});
php.on('message', function(channel, message) {
	var splited = message.split(',');
	console.log('[php#action]', splited);

	var action = splited[0];
	var sid = splited[1];
	var room;

	if (server.sockets.sockets.hasOwnProperty(sid)) {
		if (action === 'join') {
			room = splited[2];
			server.sockets.sockets[sid].join(room);

		} else if (action === 'leaveAll') {
			server.sockets.sockets[sid].leaveAll();
		}
	}
});
