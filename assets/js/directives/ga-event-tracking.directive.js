'use strict';
/*globals ga*/
var app = angular.module('rubyApp', []).directive('gaEventTracking', function() {
  return {
    restrict: 'A',
    scope: {
      eventCategory: '@',
      eventAction: '@',
      eventLabel: '@?',
      eventValue: '@?'
    },
    link: function($scope, element){
      element.bind('click',function(){
        ga('send', 'event', $scope.eventCategory, $scope.eventAction, $scope.eventLabel, $scope.eventValue);
      });
    }
  };
});
