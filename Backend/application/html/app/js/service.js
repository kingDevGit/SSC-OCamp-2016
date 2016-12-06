angular.module('starter.services', [])

.service('UserService',['$cookies','$log' ,function($cookies,$log) {


  // For the purpose of this example I will store user data on ionic local storage but you should save it on a database
  var setUser = function(token) {
    var date = new Date(Date.now() + 1000*86400*30);
  	$cookies.put('token',JSON.stringify(token),{ expires: date });

  	$log.debug('[Set User] OK  Token:'+token);

  };

  var getUser = function(){

  	if(!$cookies.get('token')){

  		return console.log("No cookie stored");
  	}else{
  		var temp= JSON.parse($cookies.get('token'))||null;
  		$log.debug('[Get User] OK Token:'+$cookies.get('token'));
  		return temp;
  	}
  };

  var logoutUser = function(){
  	console.log('[Logout User] OK');
  	$cookies.remove('token');

  };




  return {
  	getUser: getUser,
  	setUser: setUser,
  	logoutUser: logoutUser,

  };
}]);



