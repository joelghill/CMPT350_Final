 app.controller('classes', ['$http', '$scope', function($http, $scope){
    $scope.classes = [];
    $scope.studentClasses = [];
    $http.get(apiBase + '/classes').success(function(r){
        $scope.classes = r;
    });
    $scope.$watch('user', function(newV, old){
        if(newV["student"] != null){
            //console.log("User updated: "+newV["student"]["studentID"]);
            $http.get(apiBase + 'students/' + newV["student"]["studentID"] + '/classes').then(function(r){
                $scope.studentClasses = r.data;
            });
        }
    });    

}]);

