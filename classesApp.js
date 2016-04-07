 app.controller('classes', ['$http', '$scope', function($http, $scope){
    $scope.classes = [];
    //$rootscope.classes = [];
    $scope.studentClasses = [];
    $scope.studentList = [];
    $scope.studentAdminClasses = [];
    $scope.classSearchResult = []; 
    $scope.classSearch = "";
    
    $scope.student = {};

    $http.get(apiBase + '/classes').success(function(r){
        $scope.classes = r;
        //$rootscope.classes = r;
    });
    $scope.$watch('user', function(newV, old){
        if(newV["student"] != null){
            $scope.student = newV["student"];
            $scope.getStudentClasses();
        }
    });
    
    $scope.getStudentClasses = function() {
        console.log("get student classes called for:" + $scope.student["studentID"]);
        $http.get(apiBase + 'students/' + $scope.student["studentID"] + '/classes').then(function(r){
            $scope.studentClasses = r.data;
            console.log($scope.studentClasses);
            $scope.studentAdminClasses = $scope.studentClasses.filter(function(c){
                return (true);
            });
        });
    };

    $scope.getStudents = function(classID){
        $http.get(apiBase + 'classes/'+classID+'/students').then(function(r){
            $scope.studentList = r.data;    
        });
    };

    $scope.$watch('classSearch', function(newS, oldS){
        $scope.classSearchResult = $scope.classes.filter(function(c){
            console.log("Main controller watching ClassSearch");
            return (c.name.indexOf($scope.classSearch) > -1 && $scope.classSearch != "");    
        });
    });

}]);

app.controller('classSearchController', ['$http', '$scope', '$uibModal',
    '$log', function($http, $scope, $uibModal, $log){

    $scope.open = function (size) {
    var modalInstance = $uibModal.open({
        animation: $scope.animationsEnabled,
        templateUrl: './findClassTest.html',
        controller: 'classSearchInstanceCtrl',
        size: size,
        resolve: {
            classes: function () {
                return $scope.classes;
            },
            student: function() {
                return $scope.student;    
            }
        }
        });

        modalInstance.result.then(function () {
            console.log("Success???");
        }, 
        function () {
            $log.info('Modal dismissed at: ' + new Date());
        });
    };

    $scope.toggleAnimation = function () {
        $scope.animationsEnabled = !$scope.animationsEnabled;
    };

}]);

// Please note that $uibModalInstance represents a modal window (instance) dependency.
// It is not the same as the $uibModal service used above.
app.controller('classSearchInstanceCtrl',['$scope','$uibModalInstance' , 'classes', 'student',
    function ($scope, $uibModalInstance, classes, student) {
    
    $scope.classes = classes;
    $scope.student = student;
    $scope.classSearch="";
    $scope.selected = [];
    
    $scope.check = function(index){
        console.log("Index #" + index + " clicked");    
    };
    
    $scope.ok = function () {
        $uibModalInstance.close();
    };
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };

    $scope.emptyQuery = function(){
        return $scope.classSearch == "";
    };

    $scope.$watch('classSearch', function(newS, oldS){
        $scope.classSearchResult = $scope.classes.filter(function(c){
            $scope.selected = [];
            return (c.name.indexOf($scope.classSearch) > -1 && $scope.classSearch != "");    
        });
    });

    $scope.addClasses = function(){
        console.log($scope.selected);
        for(var i = 0; i < $scope.selected.length; i++){
            if($scope.selected[i]){
                console.log($scope.classes[i]);
            }
        }
        console.log($scope.student);
    };

}]);

app.controller('classAddController', ['$http', '$scope', '$uibModal',
    '$log', function($http, $scope, $uibModal, $log){

    $scope.open = function (size) {
    var modalInstance = $uibModal.open({
        animation: $scope.animationsEnabled,
        templateUrl: './createNewChannel.html',
        controller: 'classAddInstanceCtrl',
        size: size,
        resolve: {
            classes: function () {
                return $scope.classes;
            },
            student: function() {
                return $scope.student;    
            }
        }
        });

        modalInstance.result.then(function () {
            console.log("Success???");
            $scope.getStudentClasses();
        }, 
        function () {
            $log.info('Modal dismissed at: ' + new Date());
        });
    };

    $scope.toggleAnimation = function () {
        $scope.animationsEnabled = !$scope.animationsEnabled;
    };

}]);

// Please note that $uibModalInstance represents a modal window (instance) dependency.
// It is not the same as the $uibModal service used above.
app.controller('classAddInstanceCtrl',['$scope','$uibModalInstance', '$http', 'classes', 'student',
    function ($scope, $uibModalInstance, $http, classes, student) {
    
    $scope.classes = classes;
    $scope.student = student;
    
    $scope.cName = "";
    $scope.cShort = "";
    $scope.cLong = "";

    $scope.formOK = function(){
        return ($scope.cName != "" 
            && $scope.cShort != "" 
            && $scope.cLong != "");
    }; 
    
    $scope.ok = function () {
        console.log("OK pressed");
        $scope.addClass();
        //$uibModalInstance.close();
    };

    $scope.addClass = function(){
        var params = JSON.stringify({"name":$scope.cName, 
            "short":$scope.cShort,
            "long":$scope.cLong});

        $http.post('http://localhost:8888/api/index.php/classes', params).
        success(function(data){
            console.log(data["data"][0]);
            console.log("About to add student to new class");
            $scope.addStudentToClass(data["data"][0]["classID"]);
        }).error(function(data){
            console.log("THERE WAS AN ERROR");
        });
    };

    $scope.addStudentToClass = function(classID){
        console.log("Adding student "+$scope.student["studentID"] +" to class...");
        var params = JSON.stringify({classID:classID, admin:"TRUE"});
        console.log("params are:  "+params);
        $http.post('http://localhost:8888/api/index.php/students/' + $scope.student["studentID"] + '/classes'
        , params).success(function(data){
            console.log("Add student to class result: "+data["message"]);
        }).error(function(data){
            console.log(data);
            alert(data["message"]);
        });
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };


}]);
