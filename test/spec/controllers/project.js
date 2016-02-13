var environ = window.location.host;

if (environ === "localhost") {
  var baseurl = window.location.protocol + "//" + window.location.host + "/" + "rubyinvoice/";
} else {
  var baseurl = window.location.protocol + "//" + window.location.host + "/";
}

var cid = window.location.pathname.split('/').pop();

var app = angular.module('projectApp', ['mm.foundation'])
  .controller('ProjectController', ['$scope', '$http', function($scope, $http) {


    $scope.convertToHours = function(total_time) {
      var time_unit = 3600; // time measured in seconds
      time_hours = (total_time / time_unit).toFixed(2);
      return time_hours;
    }

    calcTimers = function() {
      var timers_combined = 0;
      var time_unit = 3600; // time measured in seconds
      var index;
      var index2;
      var index3;
      var time_unbilled = 0;
      var tasks_total_time = 0;

        for (index = 0; index < $scope.project_object.length; ++index) { // PROJECT LOOP

          tasks_total_time = 0;
          time_unbilled = 0;

          if ($scope.project_object[index].tasks) {


            for (index2 = 0; index2 < $scope.project_object[index].tasks.length; ++index2) { // TASK LOOP

              var total_time = 0;
              var timers_unbilled = 0;

               if ($scope.project_object[index].tasks[index2].timers) {

                 for (index3 = 0; index3 < $scope.project_object[index].tasks[index2].timers.length; ++index3) { // TIMER LOOP

                   total_time += parseInt($scope.project_object[index].tasks[index2].timers[index3].time);
                   $scope.project_object[index].tasks[index2].time_total = $scope.convertToHours(total_time);

                   // TALLY HOURS INVOICED
                   if( $scope.project_object[index].tasks[index2].timers[index3].common_id == "0" ) {
                     timers_unbilled += parseInt($scope.project_object[index].tasks[index2].timers[index3].time);

                   }

                }

                time_unbilled += timers_unbilled;
                tasks_total_time += total_time;

                division = (total_time / time_unit) / $scope.project_object[index].tasks[index2].time_estimate;
                hour_percent = Math.round((division * 100).toFixed(2));

                if (hour_percent == Number.POSITIVE_INFINITY || hour_percent == Number.NEGATIVE_INFINITY) {
                  hour_percent = 0;
                }

                $scope.project_object[index].tasks[index2].percent_complete = hour_percent;
                $scope.project_object[index].tasks[index2].time_total = $scope.convertToHours(total_time);

              } else {
                $scope.project_object[index].tasks[index2].percent_complete = 0;

              }
            }
        }

        $scope.project_object[index].hours_unbilled = $scope.convertToHours(time_unbilled);
        $scope.project_object[index].hours_worked = $scope.convertToHours(tasks_total_time);

      }
    }

    // GET PROJECT JSON
    $scope.loadProjects = function () {
     $http.get(baseurl+'index.php/clients/get_project_json/'+cid)
     .error(function (data) {
        Messenger().post({
          message: 'There was a connection error, try refreshing...',
          type: 'error'
        });

        $scope.project_object = JSON.parse(localStorage.localData);
      })
     .success(function(data) {

      if (typeof data === "undefined" || data == "null") {

        $scope.project_object = [];
        $scope.setProject("Sample Project");
        Messenger().post({
          message: 'No projects, sample project added.',
          type: 'error'
        });

       } else {

         // POPULATE LOCAL STORAGE
         if(typeof(Storage) !== "undefined") {

           localStorage.localData = JSON.stringify(data);
           // Retrieve the object from storage
           retrievedObject = JSON.parse(localStorage.localData);

         } else {
           // Sorry! No Web Storage support..
         }

         $scope.project_object = data;
         //$scope.project_object = retrievedObject;
         calcTimers();

       }
     });
    };

    $scope.loadProjects(); //initial load

    $scope.setProject = function(prj_name) {

      update = typeof update !== 'undefined' ? update : 'false'; // set this to false by default
      id = typeof id !== 'undefined' ? id : null; // set this to false by default

      $http({
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: baseurl+'index.php/clients/set_project',
        method: "POST",
        data: $.param({
          "prj_name" : prj_name,
          "cid" : cid
        }),
      })
      .error(function(data) {
        Messenger().post({
          message: 'There was a connection error, try saving again',
          type: 'error'
        });
        $scope.project_object.unshift({
          project_name: prj_name
        });
      })
      .success(function(data) {

        // PROJECT ID IS RETRIEVED FROM DB
        pid = String(data.project_id);
        // ADD THE PROJECT ID BACK TO THE NEWLY INSERT PROJECT
        $scope.project_object[0].project_id = pid;

        Messenger().post({
          message: 'Project '+data.project_name+' was added',
          type: 'success'
        });
      });
    };

    $scope.editProject = function(prj, prj_name) {

      $http({
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: baseurl+'index.php/clients/set_project',
        method: "POST",
        data: $.param({
          "prj_name" : prj_name,
          "cid" : cid,
          "project_id" : prj.project_id,
          "status" : prj.status
        }),
      })
      .error(function(data) {
        Messenger().post({
          message: 'There was a connection error, try saving again',
          type: 'error'
        });
      })
      .success(function(data) {
        Messenger().post({
          message: 'Project was edited',
          type: 'success'
        });
      });
    };

    $scope.deleteProject = function(prj) {
      $http({
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: baseurl+'index.php/clients/delete_project',
        method: "POST",
        data: $.param({
          "project_id" : prj.project_id,
          "cid" : cid
        }),
      })
      .error(function(data) {
        Messenger().post({
          message: 'There was a connection error, try again soon',
          type: 'error'
        });
      })
      .success(function(data) {
        //console.log(data);
        Messenger().post({
          message: 'Project '+prj.project_name+' was deleted',
          type: 'success'
        });
      });
    }

    $scope.setTask = function(id, tname, trate, testimate, pid, update) {

      update = typeof update !== 'undefined' ? update : 'false'; // set this to false by default
      id = typeof id !== 'undefined' ? id : null; // set this to false by default

      $http({
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: baseurl+'index.php/clients/set_task',
        method: "POST",
        data: $.param({
          "id" : id,
          "task_name" : tname,
          "project_id" : pid,
          "cid" : cid,
          "rate" : trate,
          "time_estimate" : testimate,
          "update" : update
        }),
      })
      .error(function(data) {
        Messenger().post({
          message: 'There was a connection error, try saving again',
          type: 'error'
        });
      })
      .success(function(data) {
        //console.log(data);
        Messenger().post({
          message: 'Task was saved',
          type: 'success'
        });
      });
    };

    $scope.addTaskRow = function(prj, tname, trate, testimate) {

      $http({
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: baseurl+'index.php/clients/set_task',
        method: "POST",
        data: $.param({
          "task_name" : tname,
          "project_id" : prj.project_id,
          "cid" : cid,
          "rate" : trate,
          "time_estimate" : testimate
        }),
      })
      .error(function(data) {
        Messenger().post({
          message: 'There was a connection error, try saving again',
          type: 'error'
        });
      })
      .success(function(data) {

        if (!prj.tasks) {
          prj.tasks = [];
        }

        // ADD THE TASK ID FROM THE DB
        tid = String(data.id);
        prj.tasks[0].id = tid;

        Messenger().post({
          message: 'Task was added',
          type: 'success'
        });

        //console.log(data);
      });
    };

    $scope.deleteTask = function(id) {

      $http({
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: baseurl+'index.php/clients/delete_task',
        method: "POST",
        data: $.param({
          "id" : id
        }),
      })
      .error(function(data) {
        Messenger().post({
          message: 'There was a connection error, try again soon',
          type: 'error'
        });
      })
      .success(function(data) {
        Messenger().post({
          message: 'Task was deleted',
          type: 'success'
        });
      });
    };


    $scope.deleteTimer = function(id, action) {

      $http({
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        url: baseurl+'index.php/clients/'+action,
        method: "POST",
        data: $.param({
          "timer_id" : id
        }),
      })
      .error(function(data) {
        Messenger().post({
          message: 'There was a connection error, try again soon',
          type: 'error'
        });
      })
      .success(function(data) {
        Messenger().post({
          message: 'Time log deleted',
          type: 'success'
        });
      });
    };


    // PROJECT CREATION INTERACTIONS

    $scope.getProjectForm = function() {
      return baseurl+'assets/html/project-form.html';
    }

    $scope.getEditProjectForm = function() {
      return baseurl+'assets/html/edit-project-form.html';
    }

    $scope.getProjectTemplate = function () {
      return baseurl+'assets/html/project-row.html';
    }

    $scope.showProjectForm = function() {
      $scope.pform = true;
    }

    $scope.hideProjectForm = function() {
      $scope.pform = false;
    }

    $scope.hideEditProjectForm = function(prj) {
      prj.project_form = false;
    }

    $scope.showEditProjectForm = function(prj){
      prj.project_form = true;
    }

    $scope.addProject = function(prj) {
      $scope.project_object.unshift({
        project_name: prj
      });
      $scope.setProject(prj);
    }

    $scope.updateProject = function(prj, project_name) {
      $.extend(true, prj, {
        "project_name": project_name,
        "status":prj.status
      });
      $scope.editProject(prj, project_name);
    }

    $scope.removeProject = function(prj_index, prj) {
      $scope.deleteProject(prj);
      $scope.project_object.splice(prj_index, 1);
    }


    // TASK CREATION INTERACTIONS

    $scope.addTask = function(prj, task_name, task_rate, task_estimate) {
      prj.tasks.unshift({
        "task_name": task_name,
        "rate": task_rate,
        "time_estimate": task_estimate,
        "percent_complete": 0,
        "time_total": "0",
        "project_id": prj.project_id,
        "time_unbilled": 0,
        "cid": cid,
        "complete": 0
      });

      prj.task_form = false;
      $scope.addTaskRow(prj, task_name, task_rate, task_estimate)
    }
    $scope.editTaskRow = function(task, task_name, task_rate, task_estimate) {

      $.extend(true, task, {
        "task_name":task_name,
        "rate":task_rate,
        "time_estimate":task_estimate,
        "task_form":false
      });
      $scope.setTask(task.id, task_name, task_rate, task_estimate, task.project_id, "true");
      calcTimers();
    };

    $scope.removeTaskRow = function(prj, task, task_index) {

      $scope.deleteTask(task.id);
      $scope.project_object[prj].tasks.splice(task_index, 1);

      if ( $scope.project_object[prj].tasks.length <= 0 ) {
        $scope.project_object[prj].task_form = false;
      }
    }

    $scope.getTaskForm = function() {
      return baseurl+'assets/html/task-form.html';
    }

    $scope.getEditTaskForm = function() {
      return baseurl+'assets/html/edit-task-form.html';
    }

    $scope.getTaskTemplate = function () {
      return baseurl+'assets/html/task-row.html';
    }

    $scope.showTaskForm = function(prj){
      prj.task_form = true;
    }

    $scope.hideTaskForm = function(prj) {
      prj.task_form = false;
    }

    $scope.showEditTaskForm = function(task){
      task.task_form = true;
    }

    $scope.hideEditTaskForm = function(task){
      task.task_form = false;
    }

    // TIMER CREATION INTERACTIONS
    $scope.getTaskTimer = function() {
      return baseurl+'index.php/clients/view_timer/'+$scope.timerId;
    }

    $scope.setTimerId = function(task) {
      $scope.timerId = task.id;
      //console.log(task);
    }

    $scope.getTimerRows = function() {
      return baseurl+'assets/html/timer-row.html';
    }

    $scope.removeRecord = function(id, action, prj, task, index) {
      $scope.project_object[prj].tasks[task].timers.splice(index, 1);
      calcTimers();
      $scope.deleteTimer(id, action);
    }

    $scope.draft_invoice = function(prj, $index) {

      window.location.href = baseurl+'index.php/clients/convert_invoice/'+prj.project_id+'/'+cid;
    }

    $scope.text = 'Hello World!';
    $scope.users = UserFactory.get();


}]);

// MESSENGER INIT

Messenger.options = {
    extraClasses: 'messenger-fixed messenger-on-bottom messenger-on-right',
    theme: 'air'
}
