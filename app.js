
var apiBase = 'http://localhost:8888/api/index.php/'; 
var app = angular.module('main', []);

app.controller('fbUser', ['$scope','srvAuth', function($scope, srvAuth){
    this.user = {};
    var _self = this;
    $scope.$on('fbUserAvailable', function(){
        console.log(srvAuth.user);
        _self.user = srvAuth.user;
    });
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
    
    this.checkRegistration = function(){
        var _self = this;
        console.log("Checking registration.....");
        $http.get(apiBase+'students/'+this.user.id).success(function(data){
            console.log(data);
            _self.broadcastUser();
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
        var _self = this;
        FB.logout(function(res){
            $rootScope.$apply(function(){
                $rootScope.user = _self.user = {};
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
