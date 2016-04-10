 app.controller('classes', ['$http', '$scope','$uibModal', '$log', 
 function($http, $scope, $uibModal, $log){
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
    });
    $scope.$watch('user', function(newV, old){
        if(newV["student"] != null){
            $scope.student = newV["student"];
            $scope.getStudentClasses();
        }
    });
    
    $scope.getStudentClasses = function() {
    	$http.get(apiBase + '/classes').success(function(r){
        	$scope.classes = r;
    	});
        console.log("get student classes called for:" + $scope.student["studentID"]);
        $http.get(apiBase + 'students/' + $scope.student["studentID"] + '/classes').then(function(r){
            $scope.studentClasses = r.data.filter(function(c){
                return (c.admin == 0);
            });
            $scope.studentAdminClasses = r.data.filter(function(c){
                return (c.admin==1);
            });
            console.log($scope.classes);
        });
    };

    $scope.getStudents = function(classID){
        $http.get(apiBase + 'classes/'+classID+'/students').then(function(r){
            $scope.studentList = r.data;    
        });
    };

    $scope.deleteClass = function(classID){
        $http.delete(apiBase + 'classes/'+classID).success(function(data){
            console.log(data);
            $scope.getStudentClasses();    
        }).error(function(){
            $scope.getStudentClasses();
        });    
    };

    $scope.removeStudent = function(cID, stuID){
        $http.delete(apiBase + 'students/'+stuID+'/classes/'+cID).success(function(data){
            console.log(data);
            $scope.getStudentClasses();
        }).error(function(){
            
        });
    };

    $scope.$watch('classSearch', function(newS, oldS){
        $scope.classSearchResult = $scope.classes.filter(function(c){
            console.log("Main controller watching ClassSearch");
            return (c.name.indexOf($scope.classSearch) > -1 && $scope.classSearch != "");    
        });
    });

    $scope.openAddClass = function (current_class) {
    var modalInstance = $uibModal.open({
        animation: $scope.animationsEnabled,
        templateUrl: './createNewChannel.html',
        controller: 'classAddInstanceCtrl',
        resolve: {
            classes: function () {
                return $scope.classes;
            },
            student: function() {
                return $scope.student;    
            },
            currentClass: function(){
                return current_class;    
            }
        }
        });

        modalInstance.result.then(function () {
            console.log("Success???");
            $scope.getStudentClasses();
        }, 
        function () {
            $log.info('Modal dismissed at: ' + new Date());
            $scope.getStudentClasses();
        });
    };
    
    $scope.openClassSearch = function (size) {
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
            $scope.getStudentClasses();
            $log.info('Modal dismissed at: ' + new Date());
        });
    };

}]);

// Please note that $uibModalInstance represents a modal window (instance) dependency.
// It is not the same as the $uibModal service used above.
app.controller('classSearchInstanceCtrl',['$scope','$uibModalInstance', '$http', 'classes', 'student',
    function ($scope, $uibModalInstance, $http, classes, student) {
    
    $scope.classes = classes;
    $scope.student = student;
    $scope.classSearch="";
    $scope.selected = [];
    
    $scope.check = function(index){
        console.log("Index #" + index + " clicked");    
    };
    
    $scope.onOkClick = function () {
        console.log("About to add student to classes.....");
        var ids = [];
        for(var i = 0; i < $scope.selected.length; i++){
            if($scope.selected[i]){
                ids.push($scope.classes[i]["classID"]);
            }
        }
        console.log("ids to add are: "+ids);
        $scope.addStudentToClasses(ids)
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

    $scope.addStudentToClasses = function(classIDs){
        var params = JSON.stringify({classID:classIDs, admin:"FALSE"});
        $http.post(apiBase+'/students/' + $scope.student["studentID"] + '/classes'
        , params).success(function(data){
            console.log("Add student to classes result: "+data["message"]);
            $uibModalInstance.dismiss('cancel');
        }).error(function(data){
            alert(data["message"]);
            $uibModalInstance.dismiss('cancel');
        });
    };
    $scope.addClasses = function(){
        $scope.onOkClick();
    };

}]);

// Please note that $uibModalInstance represents a modal window (instance) dependency.
// It is not the same as the $uibModal service used above.
app.controller('classAddInstanceCtrl',['$scope','$uibModalInstance', '$http', 
'classes', 'student','currentClass',
    function ($scope, $uibModalInstance, $http, classes, student, currentClass) {
    
    $scope.currentClass = currentClass;
    $scope.classes = classes;
    $scope.student = student;
    $scope.edit = false;

    if(typeof $scope.currentClass ==="undefined"){
        $scope.cName = "";
        $scope.cShort = "";
        $scope.cLong = "";
    }else{
        $scope.edit = true;
        $scope.cName = $scope.currentClass["name"];
        $scope.cShort = $scope.currentClass["short_desc"];
        $scope.cLong = $scope.currentClass["long_desc"];
    }

    console.log($scope.currentClass);

    $scope.formOK = function(){
        return ($scope.cName != "" 
            && $scope.cShort != "" 
            && $scope.cLong != "");
    }; 
    
    $scope.ok = function () {
        if($scope.edit){
            console.log("edit called");
            $scope.editclass();
        }else{
            $scope.addclass();
        }
    };

    $scope.editclass = function(){
        var params = JSON.stringify({"name":$scope.cName, 
            "short":$scope.cShort,
            "long":$scope.cLong});
        console.log("params are:  " + params);
        $http.put(apiBase+'/classes/'+$scope.currentClass["classID"], params).
        success(function(data){
            console.log("Edit was success: "+data["data"][0]);
            $uibModalInstance.dismiss('cancel');
        }).error(function(data){
            $uibModalInstance.dismiss('cancel');
        });
    };

    $scope.addclass = function(){
        var params = JSON.stringify({"name":$scope.cName, 
            "short":$scope.cShort,
            "long":$scope.cLong});
        console.log("Adding class with params: "+params);
        $http.post(apiBase+'/classes', params).
        success(function(data){
            console.log(data);
            console.log("about to add student to new class");
            $scope.addStudentToClass(data["data"][0]["classID"]);
        }).error(function(data){
            console.log("there was an error");
        });
    };

    $scope.addStudentToClass = function(classID){
        console.log("Adding student "+$scope.student["studentID"] +" to class...");
        var params = JSON.stringify({classID:[classID], admin:"TRUE"});
        console.log("params are:  "+params);
        $http.post(apiBase+'/students/' + $scope.student["studentID"] + '/classes'
        , params).success(function(data){
            console.log("Add student to class result: "+data["message"]);
            $uibModalInstance.dismiss('concel');
        }).error(function(data){
            console.log(data);
            alert(data["message"]);
            $uibModalInstance.dismiss('concel');
        });
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };


}]);
