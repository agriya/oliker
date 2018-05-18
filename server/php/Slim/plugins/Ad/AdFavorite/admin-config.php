<?php
$menus = array(
    'Misc' => array(
        'title' => 'Misc',
        'icon_template' => '<span class="fa fa-newspaper-o"></span>',
        'child_sub_menu' => array(
           'ad_favorites' => array(
            'title' => 'Ad Favorites',
            'icon_template' => '<span class="glyphicon glyphicon-heart"></span>',
             'suborder' => 3,
    ) ,
           
        ) ,
        'order' => 8
    ) ,
);
$tables = array(
    'ad_favorites' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID'
                ) ,
                1 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                3 => array(
                    'name' => 'ip.ip',
                    'label' => 'Ip',
                ) ,
            ) ,
            'title' => 'Ad Favorites',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
                1 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array(
                    'name' => 'ad_id',
                    'label' => 'Ad',
                    'targetEntity' => 'ads',
                    'targetField' => 'title',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
            ) ,
            'batchActions' => array(
                'delete'
            ),
        ) ,
    ) ,
);
if(isPluginEnabled('Ad/AdFavorite')) {
  $milestone_table = array (
    'ad_favorites' => array (
      'listview' => array (
        'fields' => array (
         2 => array(
                    'name' => 'ad.title',
                    'label' => 'Ad',
                ) ,
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}