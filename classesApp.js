(function(){
    var app = angular.module('classes', []);
    app.controller('classes', function(){
        this.output = function(){
            console.log("From other controller: "+this.data.name);    
        };
    });

})();
