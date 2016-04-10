
var apiBase = 'http://52.37.80.104/api/'; 
var app = angular.module('main', ['ui.bootstrap']);

app.controller('fbUser', ['$scope','srvAuth', function($scope, srvAuth){
    this.user = {};
    this.data = {};
    $scope.user = {};
    this.loggedIn = false;
    var _self = this;
    $scope.$on('fbUserAvailable', function(){
        console.log(srvAuth.data);
        _self.data = srvAuth.data;
        _self.loggedIn = true;
        $scope.user = srvAuth.data;
    });

    this.logOut = function(){
        srvAuth.logout();
    };

}]);

app.factory('srvAuth', ['$rootScope', '$http', function ($rootScope, $http){
    this.user = {};
    this.watchAuthenticationStatusChange = function(){
        var _self = this;
        _self.user = {};
        FB.Event.subscribe('auth.authResponseChange', function(res){
            if(res.status === 'connected'){
                _self.getUserInfo();
            }else{
                //user is not logged in                
            }
        });
    };
    
    this.registerStudent = function(){
        var _self = this;
	console.log("Registering :"+ this.user.first_name);
        $http.post(apiBase+'students', 
        {first: this.user.first_name, 
        last: this.user.last_name, 
        email: this.user.email, 
        facebook: this.user.id}).success(function(data){
	    console.log("REGISTERED STUDENT");
	    console.log(data);
            if(data['result']){
		console.log("Has result:  "+data["result"]);
                $http.get(apiBase+'students/'+_self.user.id).success(function(data){
                    _self.data = data;
                    _self.broadcastUser();
                });    
            }

        }).error(function(data){
		console.log(data);
	});
    };

    this.checkRegistration = function(){
        var _self = this;
        console.log("Checking registration.....");
        $http.get(apiBase+'students/'+this.user.id).success(function(data){
            if(data.length == 0){
                console.log("REgistration required...");
                _self.registerStudent();
            }else{
                _self.data = data;
                _self.broadcastUser();
            }
        });        
    };

    this.broadcastUser = function(){
        $rootScope.$broadcast('fbUserAvailable');
    };

    this.getUserInfo = function() {
        var _self = this;
        FB.api('/me','get', {fields: 'first_name,last_name,email'}, function(res){
            return $rootScope.$apply(function(){
                $rootScope.user = _self.user = res;
                _self.checkRegistration();
                //_self.broadcastUser();
            });
        });
    };

    this.logout = function(){
        console.log("Logged out");
        var _self = this;
        _self.loggedIn = false;
        FB.logout(function(res){
            $rootScope.$apply(function(){
                $rootScope.user = _self.user = {};
                _self.data = {};
            });
        });
    };

    return this;
}]);

app.run(['$rootScope','$window', 'srvAuth',
    function($rootScope, $window, srvAuth){
        $rootScope.user = {};
        $window.fbAsyncInit = function(){
            FB.init({
                appId: '1768012943429428',
                //channelUrl: 'app/channel.html',
                status: true,
                cookie: true,
                xfbml: true,
                version: 'v2.5'
                });
            srvAuth.watchAuthenticationStatusChange();
            };
        (function(d){
            var js,
            id = 'facebook-jssdk',
            ref = d.getElementsByTagName('script')[0];

            if(d.getElementById(id)) {return;}

            js = d.createElement('script');
            js.id = id;
            js.async = true;
            js.src = "//connect.facebook.net/en_US/sdk.js";

            ref.parentNode.insertBefore(js, ref);

            }(document));
        }]);
