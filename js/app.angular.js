angular.module('app', ['ui.bootstrap','ngFileUpload','ui.sortable','ngTouch','ui.tinymce','ngRoute'])
	
	.factory('AJAX', function($http, $q) {
		return {
			'get':function(action,obj) {
				var deferred = $q.defer(),
					config = {
						method: 'GET',
						url: '/wp-admin/admin-ajax.php?action='+action+'&'+jQuery.param({data:obj}),
					};
		
				$http(config)
					.success(function(data, status, headers, config) {
						deferred.resolve(data);
					});
				return deferred.promise;
			},
			'post':function(action,obj) {
				var deferred = $q.defer();
				$http({
					method: 'POST',
					url: '/wp-admin/admin-ajax.php?action='+action,
					headers: {
						'Content-type': 'application/json'
					},
					data: JSON.stringify(obj)
				})
				.success(function(data, status, headers, config) {
					deferred.resolve(data);
				});
			
				return deferred.promise;
			},
			'simple':function(url){
				var deferred = $q.defer();
				$http({url:url})
					.success(function(data, status, headers, config) {
						deferred.resolve(data);
					});
				return deferred.promise;
			},
			'jsonp':function(action,obj) {
				var deferred = $q.defer();
				if( typeof obj != 'undefined' )
				{
					var urlPostfix = '&'+this.serialize(obj);
				}
				else
				{
					var urlPostfix = '';
				}
				$http.jsonp(action+'?callback=JSON_CALLBACK'+urlPostfix)
					.success(function(data, status, headers, config) {
						deferred.resolve(data);
					});
			
				return deferred.promise;
			},
			'serialize': function(obj) {
				var str = [];
				for(var p in obj)
				if (obj.hasOwnProperty(p)) {
					str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
				}
				return str.join("&");
			}
		}
	})
	
	.controller('global', function($scope) {
		
	})