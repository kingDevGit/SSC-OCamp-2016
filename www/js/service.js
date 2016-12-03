angular.module('starter.services', [])

.service('UserService',['$cookies','$log' ,function($cookies,$log) {


  // For the purpose of this example I will store user data on ionic local storage but you should save it on a database
  var setUser = function(token) {

  	window.localStorage.setItem('token',JSON.stringify(token));

  	$log.debug('[Set User] OK  Token:'+token);

  };

  var getUser = function(){

  	if(!window.localStorage.getItem('token')){

  		return console.log("No cookie stored");
  	}else{
  		var temp= JSON.parse(window.localStorage.getItem('token'))||null;
  		$log.debug('[Get User] OK Token:'+$cookies.get('token'));
  		return temp;
  	}
  };

  var logoutUser = function(){
  	console.log('[Logout User] OK');
  	window.localStorage.clear();
  };




  return {
  	getUser: getUser,
  	setUser: setUser,
  	logoutUser: logoutUser,

  };
}]);



