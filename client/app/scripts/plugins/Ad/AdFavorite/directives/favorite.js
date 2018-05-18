angular.module('olikerApp.Ad.AdFavorite')
    .directive('adfavorite', function(AdFavoritesFactory, AdFavoriteFactory) {
        return {
            templateUrl: 'scripts/plugins/Ad/AdFavorite/views/default/ad_favorite.html',
            restrict: 'EA',
            replace: true,
            scope: {
                item: '=',
                authentication: '=',
                callbackFn: '&',
            },
            bindToController: true,
            controller: 'FavoriteAdController',
            controllerAs: 'vm',
            link: function postLink(scope, element, attr) {}
        };
    })
    .directive('favorite', function(AdFavoritesFactory, AdFavoriteFactory) {
        return {
            templateUrl: 'scripts/plugins/Ad/AdFavorite/views/default/favorite.html',
            restrict: 'EA',
            replace: true,
            scope: {
                item: '=',
                authentication: '=',
                callbackFn: '&',
            },
            bindToController: true,
            controllerAs: 'vm',
            controller: 'FavoriteAdController',
            link: function postLink(scope, element, attr) {}
        };
    });