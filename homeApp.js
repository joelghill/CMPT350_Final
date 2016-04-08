
 app.controller('home', ['$http', '$scope','$uibModal', '$log', 
 function($http, $scope, $uibModal, $log){
    
    $scope.photoSrc = "";
    $scope.points=0;
    $scope.$watch('user', function(newV, old){
        if(newV["student"] != null){
            $scope.points = newV["points"][0]["total"];
            $scope.getProfilePhoto(newV["student"]["facebookID"]);
        }
    });

    $scope.getProfilePhoto = function(id){
        console.log("Getting profile photo for "+id);
        FB.api("/"+id+"/picture?type=large",
            function (response) {
            if (response && !response.error) {
                $scope.photoSrc = response["data"]["url"];
            }else{
                console.log("FB photo error: "+response.error);
            }
        });
    };

 }]);


 app.controller('leaderboard', ['$http', '$scope','$uibModal', '$log', 
 function($http, $scope, $uibModal, $log){
    $scope.students = [];
    $scope.points=0;

    $scope.$watch('user', function(newV, old){
        if(newV["student"] != null){
        }
    });
    
    $http.get(apiBase + "/students").success(function(r){
        $scope.students = r.data;
    }).error(function(){
        console.log("Error getting students");    
    });

    $scope.getProfilePhoto = function(id){
        console.log("Getting profile photo for "+id);
        FB.api("/"+id+"/picture?type=large",
            function (response) {
            if (response && !response.error) {
                $scope.photoSrc = response["data"]["url"];
            }else{
                console.log("FB photo error: "+response.error);
            }
        });
    };

 }]);
