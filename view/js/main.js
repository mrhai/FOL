//api get film
var root_apt = "http://api-haimv.ultimatefreehost.in";
var apiGetListFilm = root_apt+"/php/getfilm.php";
var apt_add_update_film = root_apt+"/api/add_film_api.php?data=";
var api_get_all_catalog = root_apt+"/api/get_all_catalog.php?type=";
var api_get_all_sub_catalog = root_apt+"/api/get_all_sub_catalog.php";
var api_get_list_film = root_apt+"/api/get_list_film.php?type=%type&page=%page&id=%id";
var api_get_film_server = root_apt+"/api/get_server.php?id=%id&part=%part";
var api_get_film_server_count = root_apt+"/api/get_film_pagging.php?id=";
var api_get_film_detail = root_apt+"/api/get_film_details.php?id=";
var apt_get_all_catalog = root_apt+"/api/get_all_catalog.php";
var api_get_top_film = root_apt+"/api/get_top_film.php";
var api_add_film_view = root_apt+"/api/add_view.php?id=";
var app = angular.module('app', ['ngRoute']);
var current_link = "";
var current_main_page = 1;
var page_title = "Film";
var root_scope;

app.config(
  function($routeProvider,$httpProvider) {
    $routeProvider.
	when('/main', {
        templateUrl: 'theme/main.html',
		controller: 'mainController'
    }).when('/main/:page', {
        templateUrl: 'theme/main.html',
		controller: 'mainController'
    }).when('/main/:type/:filter/:page', {
        templateUrl: 'theme/main.html',
		controller: 'filterController'
    }).
	when('/view/:part/:id', {
        templateUrl: 'theme/view.html',
		controller: 'viewController'
    }).
	when('/add_film/:token', {
        templateUrl: 'theme/admin/add_film.html',
		controller: 'addFilmController'
    }).
	when('/edit_film/:token', {
        templateUrl: 'theme/admin/add_film.html',
		controller: 'editFilmController'
    }).
	when('/list_film/:token', {
        templateUrl: 'theme/admin/list_film.html',
		controller: 'listFilmController'
    }).
      otherwise({
        redirectTo: '/main'
      });
	
	$httpProvider.defaults.useXDomain = true;
	$httpProvider.defaults.withCredentials = true;
	delete $httpProvider.defaults.headers.common["X-Requested-With"];
	$httpProvider.defaults.headers.common["Accept"] = "application/json";
	$httpProvider.defaults.headers.common["Content-Type"] = "application/json";

});
app.run(function($rootScope,$http) {
	
	add_header($http);
	
    $http.get(apt_get_all_catalog).then(function(response) {
		$rootScope.catalog_list = response.data;
		
	});
	
	$rootScope.root_page_title = page_title;
	root_scope = $rootScope;
	
});
//controller
app.controller('mainController', function($scope,$http,$routeParams) {
   loading();
   add_header($http);
   root_scope.root_page_title = page_title;
   var page = 1;
   if($routeParams.page != "" && $routeParams.page != undefined ){
	   page = $routeParams.page;
   }
	current_main_page = page;
   var api = api_get_list_film.replace("%type"," ").replace("%page",page);
	$http.get(api).then(function(response) {//type
		$scope.filmbinding = response.data.film;
		var range = [];
		for(var i=0;i<response.data.page;i++) {
		  range.push(i);
		}
		$scope.pagging = range;
		$scope.pagging_link = "#main/";

		hide_loading();
		main_page_select();
	});
	
});
app.controller('viewController', function($scope,$http,$routeParams) {
	loading();
	add_header($http);
	var id = $routeParams.id;
	id = id.split("-");
	id = id[0];
	
	var api = api_get_film_server.replace("%id",id).replace("%part",$routeParams.part);
	
	if(id == 1){
		$http.get(api_add_film_view+id).then(function(response) {//type
		
		});
	}
	
	$http.get(api).then(function(response) {//type
		var server = response.data;
		$http.get(api_get_film_server_count+id).then(function(response) {//type
			var pagging = response.data;
			$http.get(api_get_film_detail+id).then(function(response) {//type
				var film_detail = response.data;
				$scope.film_detail = film_detail;
				root_scope.root_page_title = film_detail.name;
				view_part_select();
				hide_loading();
			});
			$scope.pagging = pagging;
		});
		
		$scope.server = server;
		
		current_link = server.link;
		
		start_film();
	});
	
	$http.get(api_get_top_film).then(function(response) {//type
		$scope.top_fim = response.data;
	});
});
app.controller('filterController', function($scope,$routeParams,$http) {
	loading();
	add_header($http);
	current_main_page = $routeParams.page;
	var api = api_get_list_film.replace("%type",$routeParams.type).replace("%page",$routeParams.page).replace("%id",$routeParams.filter);
	$http.get(api).then(function(response) {//type
		$scope.filmbinding = response.data.film;
		var range = [];
		for(var i=0;i<response.data.page;i++) {
		  range.push(i);
		}
		$scope.pagging = range;
		$scope.pagging_link = "#main/"+$routeParams.type+"/"+$routeParams.filter+"/";
		hide_loading();
	});
});
app.controller('addFilmController', function($scope,$http,$routeParams) {
	add_header($http);
	$http.get(api_get_all_catalog+1).then(function(response) {//type
		$scope.typeValue = response.data;
	});
	
	$http.get(api_get_all_catalog+2).then(function(response) {//country
			$scope.countryValue = response.data;
		});
	$http.get(api_get_all_catalog+3).then(function(response) {//catalog
			$scope.catalogValue = response.data;
		});
	
	$scope.master = {};
	
	$scope.save = function(){
		loading();
		var api = apt_add_update_film+JSON.stringify($scope.add);
		$http.get(api).then(function(response) {
			if(response.data == 1){
				$scope.reset();
			}else{
				alert("An error while process. Please check your inputed!");
			}
			hide_loading();
		});
	}
	
	$scope.reset = function() {
        $scope.add = angular.copy($scope.master);
		$scope.add.insert = 1;
      };

    $scope.reset();
	
});
app.controller('editFilmController', function($scope,$http) {
   loading();
   add_header($http);
   hide_loading();
	
});
app.controller('listFilmController', function($scope,$routeParams) {
	loading();
	add_header($http);
	$scope.find = function(){
		document.writeln(JSON.stringify($scope.finder));
	}
	
	$scope.reset = function() {
        $scope.find_value = "";
      };

    $scope.reset();
	hide_loading();
});
//declare funcion
app.directive('script', function() {
    return {
      restrict: 'E',
      scope: false,
      link: function(scope, elem, attr) {
        if (attr.type=='text/javascript-lazy') {
          var code = elem.text();
          var f = new Function(code);
          f();
        }
      }
    };
  });
app.directive('dynFbCommentBox', function () {
    function createHTML(href, numposts, colorscheme,width) {
        return '<div class="fb-comments" ' +
                       'data-numposts="' + numposts + '" ' +
                       'data-colorsheme="' + colorscheme + '" ' +
					   'data-width="'+ width +'">'+
               '</div>';
    }

    return {
        restrict: 'A',
        scope: {},
        link: function postLink(scope, elem, attrs) {
            attrs.$observe('pageHref', function (newValue) {
                var href        = newValue;
                var numposts    = attrs.numposts    || 5;
                var colorscheme = attrs.colorscheme || 'light';
				var width = attrs.widths || '100%';

                elem.html(createHTML(href, numposts, colorscheme,width));
                FB.XFBML.parse(elem[0]);
            });
        }
    };
});

function loading(){
	var loading = $("#loading");
	loading.show();
}

function hide_loading(){
	var loading = $("#loading");
	loading.hide();
}

function start_film(){
	
	setTimeout(function(){
			jwplayer.key="XJ38Vr++31SQ1qsGaLKpEqSZ86mN6yZq0mOmuQ==";
			jwplayer("video-view").setup({
			  sources: [{file:current_link,"type":"mp4"}],
			  shuffle: true
			});
		 }, 100);
}

function add_header($http){
	$http.defaults.headers.put = {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers': 'Content-Type, X-Requested-With'
        };
        $http.defaults.useXDomain = true;
		 delete $http.defaults.headers.common['X-Requested-With'];
}

function main_page_select(){
			setTimeout(function(){
			var currentPageID = ".pagination #"+current_main_page;
			$(".pagination li").removeClass("active");
			$(currentPageID).addClass("active");
			},300);
}

function view_part_select(){
	setTimeout(function(){
				var current_part = $(".video-part #videoPart").val();
				var li = ".video-part #"+current_part;
				var li_active = $(li).addClass("active");
			},500);
}