var express = require("express");
var session = require("express-session");
var logfmt = require("logfmt");
var app = express();
var bodyParser = require("body-parser");
var cookieParser = require("cookie-parser");
var http = require("http");
var request = require("request");
var mongoose = require("mongoose");
var faye = require('faye');
var server = http.createServer(app);

global.setImmediate = global.setImmediate || process.nextTick.bind(process);

var bayeux = new faye.NodeAdapter({
	mount: '/faye', timeout: 45
});
bayeux.attach(server);
bayeux.on('handshake', function(clientId){
	console.log('Client connected', clientId);
});
bayeux.on('subscribe', function(clientId, channel){
	console.log('Client subscribed to ' + channel + ': ' + clientId);
});
var bayeuxClient = bayeux.getClient();

var mUser = '';	//Mongoose login username
var mPwd = '';	//Mongoose login password
var mServer = ''; //Mongoose server/app URL
mongoose.connect('mongodb://'+mUser+':'+mPwd+'@' + mServer);
var db = mongoose.connection;
db.on('error', console.error.bind(console, 'connection error'));
db.once('open', function(){
});

var shows = require('./models/shows');
var questions = require('./models/questions');
var answers = require('./models/answers');
var showQuestions = require('./models/showQuestions');
var showQuestionAnswers = require('./models/showQuestionAnswers');
var showUsers = require('./models/showUsers');
var surveys = require('./models/surveys');

app.use(logfmt.requestLogger());
app.use(bodyParser());
app.use(cookieParser());
app.use(session({
	secret: 'noexit', resave: true, saveUninitialized: true,
	cookie: { path: '/', httpOnly: true, secure: false, maxAge: 5000000 }
}));
app.use(function(req, res, next){
	res.header('Access-Control-Allow-Origin', '*');
	res.header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
	res.header('Access-Control-Allow-Headers', 'Content-Type');
	next();
});

var router = express.Router();

router.use(function(req, res, next){
	var proceed = true;
	if(proceed)
		next();
	else{
		res.status(401).send('Unauthorized request');
	}
});

//ROUTES
require('./routes/shows')(router, shows, bayeux, questions, mongoose);
require('./routes/questions')(router, questions, bayeux);
require('./routes/answers')(router, answers, bayeux);
require('./routes/showQuestions')(router, showQuestions, bayeuxClient);
require('./routes/showQuestionAnswers')(router, showQuestionAnswers, bayeux);
require('./routes/special')(router, showQuestions, bayeux);

//REST base
app.use('/api', router);

var port = Number(process.env.PORT || 5000);
server.listen(port, function() {
  console.log("Listening on " + port);
});