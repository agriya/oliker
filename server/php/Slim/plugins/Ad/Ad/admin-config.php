<?php
$menus = array(
    'ads' => array(
        'title' => 'Ads',
        'icon_template' => '<span class="fa fa-newspaper-o"></span>',
        'order' => 2
    ) ,
     'Master' => array(
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-user"></span>',
        'child_sub_menu' => array(
                'categories' => array(
                'title' => 'Categories',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 1,
            ) ,
              'advertiser_types' => array(
                'title' => 'Advertiser Types',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 2,
            ) ,                       
        ) ,
        'order' => 7
    ) ,
    'Misc' => array(
        'title' => 'Misc',
        'icon_template' => '<span class="fa fa-newspaper-o"></span>',
        'child_sub_menu' => array(
            'ad_searches' => array(
                'title' => 'Ad Searches',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 1,
            ) ,
            'ad_views' => array(
                'title' => 'Ad Views',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 2, 
            ) ,
        ) ,
        'order' => 8
    ) ,    
);
$tables = array(
    'ads' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                ) ,
                1 => array(
                    'name' => 'title',
                    'label' => 'Title',
                    'map' => array(
                        0 => 'truncate',
                    ) ,

                ) ,
                2 => array(
                    'name' => 'ad_owner.username',
                    'label' => 'User',
                ) ,
                3 => array(
                    'name' => 'category.name',
                    'label' => 'Category',
                ) ,
                4 => array(
                    'name' => 'advertiser_type.name',
                    'label' => 'Type',
                ) ,
                5 => array(
                    'name' => 'price',
                    'label' => 'Price',
                ) ,
                6 => array(
                    'name' => 'advertiser_name',
                    'label' => 'Advertiser Name',
                ) ,
                11 => array(
                    'name' => 'ad_end_date',
                    'label' => 'Ad Expiry Date',
                ) ,
                12 => array(
                    'name' => 'ad_view_count',
                    'label' => 'View',
                    'template' => '<a href="#/ad_views/list?search=%7B%22ad_id%22:{{entry.values.id}}%7D">{{entry.values.ad_view_count}}</a>',
                ) ,
                13 => array(
                    'name' => 'ad_report_count',
                    'label' => 'Report',
                    'template' => '<a href="#/ad_reports/list?search=%7B%22ad_id%22:{{entry.values.id}}%7D">{{entry.values.ad_report_count}}</a>',

                ) ,                
            ) ,
            'title' => 'Ads',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
           'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
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
                    'name' => 'advertiser_type_id',
                    'label' => 'Advertiser Type',
                    'targetEntity' => 'advertiser_types',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array(
                    'name' => 'is_urgent',
                    'label' => ' Urgent?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'is_highlighted',
                    'label' => ' Highlighted?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_show_ad_in_top',
                    'label' => ' In Top?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'is_show_as_top_ads',
                    'label' => ' As Top Ads?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
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
                'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'user.name',
                    'label' => 'User',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'slug',
                    'label' => 'Slug',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'category.name',
                    'label' => 'Category',
                    'type' => 'number',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'advertiser_type.name',
                    'label' => 'Advertiser Type',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'is_an_exchange_item',
                    'label' => ' An Exchange Item?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'price',
                    'label' => 'Price',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'is_negotiable',
                    'label' => ' Negotiable?',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                8 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                9 => array(
                    'name' => 'city.name',
                    'label' => 'City',
                    'type' => 'number',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                10 => array(
                    'name' => 'state.name',
                    'label' => 'State',
                    'type' => 'number',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                11 => array(
                    'name' => 'country.name',
                    'label' => 'Country',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                12 => array(
                    'name' => 'location',
                    'label' => 'Location',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                13 => array(
                    'name' => 'is_send_email_when_user_contact',
                    'label' => 'Send Email When User Contact?',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                14 => array(
                    'name' => 'latitude',
                    'label' => 'Latitude',
                    'type' => 'number',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                15 => array(
                    'name' => 'longitude',
                    'label' => 'Longitude',
                    'type' => 'number',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                16 => array(
                    'name' => 'is_show_as_top_ads',
                    'label' => 'Top?',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                17 => array(
                    'name' => 'advertiser_name',
                    'label' => 'Advertiser Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                18 => array(
                    'name' => 'ad_in_top_end_date',
                    'label' => 'Ad In Top End Date',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                19 => array(
                    'name' => 'phone_number',
                    'label' => 'Phone Number',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                20 => array(
                    'name' => 'top_ads_end_date',
                    'label' => 'Top Ads End Date',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                21 => array(
                    'name' => 'is_show_ad_in_top',
                    'label' => 'Is Show Ad In Top',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                22 => array(
                    'name' => 'urgent_end_date',
                    'label' => 'Urgent End Date',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                23 => array(
                    'name' => 'highlighted_end_date',
                    'label' => 'Highlighted End Date',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                24 => array(
                    'name' => 'is_removed_by_admin',
                    'label' => ' Removed By Admin?',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                25 => array(
                    'name' => 'is_urgent',
                    'label' => ' Urgent?',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                26 => array(
                    'name' => 'is_highlighted',
                    'label' => 'Highlighted?',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                27 => array(
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                28 => array(
                    'name' => 'is_price_reduced',
                    'label' => 'Price Reduced?',
                    'type' => 'boolean',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                29 => array(
                    'name' => 'ad_start_date',
                    'label' => 'Ad Start Date',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                30 => array(
                    'name' => 'ad_end_date',
                    'label' => 'Ad End Date',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
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
                1 => array(
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array(
                    'name' => 'advertiser_type_id',
                    'label' => 'Advertiser Type',
                    'targetEntity' => 'advertiser_types',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                3 => array(
                    'name' => 'is_an_exchange_item',
                    'label' => ' An Exchange Item?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'price',
                    'label' => 'Price',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'is_negotiable',
                    'label' => ' Negotiable?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'location',
                    'label' => 'Location',
                    'template' => '<google-places entry="entry" entity="entity"></google-places>',
                ) ,
                8 => array(
                    'name' => 'address',
                    'label' => 'Address',
                ) ,
                9 => array(
                    'name' => 'address1',
                    'label' => 'Address1',
                ) ,
                10 => array(
                    'name' => 'city.name',
                    'label' => 'City',
                    'type' => 'string',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                11 => array(
                    'name' => 'state.name',
                    'label' => 'State',
                    'type' => 'string',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                12 => array(
                    'name' => 'country.iso_alpha2',
                    'label' => 'Country',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                13 => array(
                    'name' => 'latitude',
                    'label' => 'Latitude',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                14 => array(
                    'name' => 'longitude',
                    'label' => 'Longitude',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                
                15 => array(
                    'name' => 'advertiser_name',
                    'label' => 'Advertiser Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                
                16 => array(
                    'name' => 'phone_number',
                    'label' => 'Phone Number',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                 17 => array(
                    'name' => 'is_show_ad_in_top',
                    'label' => 'In Top?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                 18 => array(
                    'name' => 'ad_in_top_end_date',
                    'label' => 'Ad In Top End Date',
                     'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                 19 => array(
                    'name' => 'is_show_as_top_ads',
                    'label' => 'Top Ads?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                20 => array(
                    'name' => 'top_ads_end_date',
                    'label' => 'Top Ads End Date',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                21 => array(
                    'name' => 'is_urgent',
                    'label' => 'Urgent?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                22 => array(
                    'name' => 'urgent_end_date',
                    'label' => 'Urgent End Date',
                   'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                23 => array(
                    'name' => 'is_highlighted',
                    'label' => 'Highlighted?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                24 => array(
                    'name' => 'highlighted_end_date',
                    'label' => 'Highlighted End Date',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
               
                
                25 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
             'title' => 'Ads',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                ) ,
                1 => array(
                    'name' => 'title',
                    'label' => 'title',
                ) ,
                2 => array(
                     'name' => 'ad_owner.username',
                    'label' => 'User',
                ) ,
                3 => array(
                    'name' => 'category.name',
                    'label' => 'Category',
                ) ,
                4 => array(
                    'name' => 'advertiser_type.name',
                    'label' => 'Type',
                ) ,
                5 => array(
                    'name' => 'price',
                    'label' => 'Price',
                ) ,
                6 => array(
                    'name' => 'advertiser_name',
                    'label' => 'Advertiser Name',
                ) ,
                7 => array(
                    'name' => 'is_show_as_top_ads',
                    'label' => ' Top?',
                    'type' => 'boolean',
                ) ,
                8 => array(
                    'name' => 'is_show_ad_in_top',
                    'label' => ' In Top?',
                    'type' => 'boolean',
                ) ,
                9 => array(
                    'name' => 'is_urgent',
                    'label' => 'Urgent?',
                    'type' => 'boolean',
                ) ,
                10 => array(
                    'name' => 'is_highlighted',
                    'label' => ' Highlighted?',
                    'type' => 'boolean',
                ) ,
                11 => array(
                    'name' => 'ad_view_count',
                    'label' => 'View',
                ) ,
                12 => array(
                    'name' => 'ad_favorite_count',
                    'label' => 'Favorite',
                ) ,
                13 => array(
                    'name' => 'ad_report_count',
                    'label' => 'Report',
                ) ,                
                14 => array(
                    'name' => 'ad_end_date',
                    'label' => 'Ad Expiry Date',
                ) ,
                15 => array(
                    'name' => 'ad_form_field',
                    'label' => 'Form Fields',
					'template' => '<ad-category-form-field entry="entry" entity="entity"></ad-category-form-field>'
                ) ,
            ) ,
             'title' => 'Ads',
        ) ,
    ) ,
    'ad_searches' => array(
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
                2 => array(
                    'name' => 'keyword',
                    'label' => 'Keyword',
                ) ,
                3 => array(
                    'name' => 'category.name',
                    'label' => 'Category',
                ) ,
                4 => array(
                    'name' => 'is_search_in_description',
                    'label' => 'Search In Description?',
                    'type' => 'boolean',
                ) ,
                5 => array(
                    'name' => 'is_only_ads_with_images',
                    'label' => 'Only Ads With Images?',
                    'type' => 'boolean',
                ) ,
                6 => array(
                    'name' => 'is_notify_whenever_new_ads_posted',
                    'label' => 'Notify Whenever New Ads Posted?',
                    'type' => 'boolean',
                ) ,
                7 => array(
                    'name' => 'ip.ip',
                    'label' => 'Ip',
                ) ,
                8 => array(
                    'name' => 'created_at',
                    'label' => 'created',
                ) ,
            ) ,
            'title' => 'Ad Searches',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'delete',
            ) ,
                'batchActions' => array(
                'delete'
            ),
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
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
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
        ) ,
    ) ,
    'ad_views' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                ) ,
                1 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                2 => array(
                    'name' => 'ad.title',
                    'label' => 'Ad',
                ) ,
                3 => array(
                    'name' => 'ip.ip',
                    'label' => 'Ip',
                ) ,
            ) ,
            'title' => 'Ad Views',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'delete',
            ) ,
            'batchActions' => array(
                'delete'
            ),
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
        ) ,
       
    ) ,
    
    'advertiser_types' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                    'detailLinkRoute' => 'show',
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
            ) ,
            'title' => 'Advertiser Types',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
            ) ,
            'permanentFilters' => '',
            'batchActions' => array(
                'delete'
            ),
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
               'title' => 'Advertiser Types',
        ) ,
       
    ) ,
    'categories' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                    'detailLinkRoute' => 'show',
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'parent.name',
                    'label' => 'Parent',
                ) ,
                3 => array(
                    'name' => 'allowed_free_ads_count',
                    'label' => 'Allowed Free Ads',
                ) ,
                4 => array(
                    'name' => 'post_ad_fee',
                    'label' => 'Post Ad Fee',
                ) ,
                6 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Categories',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
            'batchActions' => array(
                '<batch-deactive type="deactive" action="categories" selection="selection"></batch-deactive>',
                '<batch-active type="active" action="categories" selection="selection"></batch-active>',
                'delete'
            ),
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
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
                    'name' => 'parent_id',
                    'label' => 'Parent',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                 2=> array(
			    'name'=> 'filter',
                'type'=> 'choice',
                'label'=> 'Active?',
                'choices'=> array(
                  0 => 
           			array (
                 		'label' => 'Yes',
               			 'value' => 'active',
               ),
               1 => 
               array (
                'label' => 'No',
                'value' => 'inactive',
               ),
                )
			   )
            ) ,
            'permanentFilters' => '',
            
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'parent_id',
                    'label' => 'Parent',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                 1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'description',
                    'label' => 'Description',
                     'type' => 'string',
                    'validation' => 
                         array (
                        'required' => false,
                        ),
                ) ,
                  3 => 
        array (
          'name' => 'is_popular',
          'label' => 'Is Popular',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        4 => 
        array (
          'name' => 'allowed_free_ads_count',
          'label' => 'Allowed Free Ads',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
         5 => 
        array (
          'name' => 'post_ad_fee',
          'label' => 'Post Ad Fee',
          'type' => 'number',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
         6 => 
        array (
          'name' => 'is_active',
          'label' => 'Is Active',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        7 => 
        array (
           'name' => 'image',
            'label'=> 'Image',
            'type'=> 'file',
            'uploadInformation'=> 
			array (
              'url' => 'api/v1/attachments',
              'apifilename'=> 'attachment'
            ),
        ),
       8 => 
        array (
           'name'=> 'form_field',
            'label'=> 'Form Fields',
            'type'=> 'embedded_list',
			'targetFields'=> array (
			   0 => array(
			   'name'=> 'name',
                'required'=> true,
                'label'=> 'Name',
                'type'=> 'string',
			   ),
			   1 => array(
			    'name'=> 'display_name',
                'required'=> true,
                'label'=> 'Display Name',
                'type'=> 'string',
			   ),
			   2 => array(
			    'name'=> 'input_type_id',
                'targetEntity'=> 'input_types',
                'targetField'=> 'name',
                'type'=> 'reference',
                'label'=> 'Input Type',
                'remoteComplete'=> true,
                'required'=> true,
			   ),
			   3 => array(
			   'name'=> 'is_required',
                'type'=> 'choice',
                'label'=> 'Required',
                'required'=> true,
                'choices'=> array(
                  0 => 
           		array (
                 'label' => 'Yes',
                'value' => true,
               ),
               1 => 
               array (
                'label' => 'No',
                'value' => false,
               ),
                )
			   ),
			   4 => array(
			    'name'=> 'label',
                'required'=> true,
                'label'=> 'label',
                'type'=> 'string'
			   ),
			    5 => array(
			  'name'=> 'depends_on',
                'label'=> 'Depend On',
                'type'=> 'string'
			   ),
			    6 => array(
			   'name'=> 'depend_value',
                'type'=> 'string',
                'label'=> 'Depend value'
			   ),
                7 => array(
			   'name'=> 'options',
                'type'=> 'string',
                'label'=> 'options'
			   ),
			    8 => array(
			   'name'=> 'info',
                'type'=> 'string',
                'label'=> 'Info'
			   ),
			    9 => array(
			   'name'=> 'display_order',
                'type'=> 'number',
                'required'=> true,
                'label'=> 'Display Order'
			   ),
			    10 => array(
			    'name'=> 'is_active',
                'type'=> 'choice',
                'label'=> 'Active?',
                'required'=> true,
                'choices'=> array(
                  0 => 
           			array (
                 		'label' => 'Yes',
               			 'value' => true,
               ),
               1 => 
               array (
                'label' => 'No',
                'value' => false,
               ),
                )
			   )
			   
			)
        ),
            ) ,
            'title' => 'Categories',
        ) ,
        'editionview' => array(
            'fields' =>array(
                0 => array(
                    'name' => 'parent_id',
                    'label' => 'Parent',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                 1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'description',
                    'label' => 'Description',
                     'type' => 'string',
                    'validation' => 
                         array (
                        'required' => false,
                        ),
                ) ,
                  3 => 
        array (
          'name' => 'is_popular',
          'label' => 'Is Popular',
          'type' => 'choice',
          'defaultValue' => false,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        4 => 
        array (
          'name' => 'allowed_free_ads_count',
          'label' => 'Allowed Free Ads',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
         5 => 
        array (
          'name' => 'post_ad_fee',
          'label' => 'Post Ad Fee',
          'validation' => 
          array (
            'required' => true,
          ),
        ),
         6 => 
        array (
          'name' => 'is_active',
          'label' => 'Is Active',
          'type' => 'choice',
          'defaultValue' => true,
          'validation' => 
          array (
            'required' => true,
          ),
          'choices' => 
          array (
            0 => 
            array (
              'label' => 'Yes',
              'value' => true,
            ),
            1 => 
            array (
              'label' => 'No',
              'value' => false,
            ),
          ),
        ),
        7 => 
        array (
           'name' => 'image',
            'label'=> 'Image',
            'type'=> 'file',
            'uploadInformation'=> 
			array (
              'url' => 'api/v1/attachments',
              'apifilename'=> 'attachment'
            ),
        ),
       8 => 
        array (
           'name'=> 'form_field',
            'label'=> 'Form Fields',
            'type'=> 'embedded_list',
			'targetFields'=> array (
			   0 => array(
			   'name'=> 'name',
                'required'=> true,
                'label'=> 'Name',
                'type'=> 'string',
			   ),
			   1 => array(
			    'name'=> 'display_name',
                'required'=> true,
                'label'=> 'Display Name',
                'type'=> 'string',
			   ),
			   2 => array(
			    'name'=> 'input_type_id',
                'targetEntity'=> 'input_types',
                'targetField'=> 'name',
                'type'=> 'reference',
                'label'=> 'Input Type',
                'remoteComplete'=> true,
                'required'=> true,
			   ),
			   3 => array(
			   'name'=> 'is_required',
                'type'=> 'choice',
                'label'=> 'Required',
                'required'=> true,
                'choices'=> array(
                  0 => 
           		array (
                 'label' => 'Yes',
                'value' => true,
               ),
               1 => 
               array (
                'label' => 'No',
                'value' => false,
               ),
                )
			   ),
			   4 => array(
			    'name'=> 'label',
                'required'=> true,
                'label'=> 'label',
                'type'=> 'string'
			   ),
			    5 => array(
			  'name'=> 'depends_on',
                'label'=> 'Depend On',
                'type'=> 'string'
			   ),
			    6 => array(
			   'name'=> 'depend_value',
                'type'=> 'string',
                'label'=> 'Depend value'
			   ),
                7 => array(
			   'name'=> 'options',
                'type'=> 'string',
                'label'=> 'options'
			   ),
			    8 => array(
			   'name'=> 'info',
                'type'=> 'string',
                'label'=> 'Info'
			   ),
			    9 => array(
			   'name'=> 'display_order',
                'type'=> 'number',
                'required'=> true,
                'label'=> 'Display Order'
			   ),
			    10 => array(
			    'name'=> 'is_active',
                'type'=> 'choice',
                'label'=> 'Active?',
                'required'=> true,
                'choices'=> array(
                  0 => 
           			array (
                 		'label' => 'Yes',
               			 'value' => true,
               ),
               1 => 
               array (
                'label' => 'No',
                'value' => false,
               ),
                )
			   )
			   
			)
        ),
            )  ,
            'title' => 'Categories',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                     'name' => 'name',
                    'label' => 'Name',
                ) ,
                1 => array(
                   'name' => 'parent.name',
                    'label' => 'Parent Category',
                ) ,
                2 => array(
                    'name' => 'allowed_free_ads_count',
                    'label' => 'Allowed Free Ads Count',
                ) ,
                3 => array(
                     'name' => 'post_ad_fee',
                    'label' => 'Post Ad Fee',
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => ' Active?',
                ) ,
                5 => array(
                    'name' => 'ad_count',
                    'label' => 'Ad Count',
                ) ,
                6 => array(
                    'name' => 'form_field',
                    'label' => 'Form Fields',
					'template' => '<category-form-field entry="entry" entity="entity"></category-form-field>'
                ) ,
            ) ,
            'title' => 'Categories',
        ) ,
    ) ,
);
$dashboard = array (
  'ads' => array (
    'addCollection' => array (
      'fields' => array (
        0 => array (
          'name' => 'id',
          'label' => 'ID',
          'isDetailLink'=> true,
          'detailLinkRoute' => 'show',
        ),
        1 => array (
          'name' => 'title',
          'label' => 'Title'
        ),
        2 => array (
          'name' => 'ad_owner.username',
          'label' => 'User'
        ),
        3 => array (
          'name' => 'category.name',
          'label' => 'Category',
        ),
         4 => array (
          'name' => 'advertiser_type.name',
          'label' => 'Type',
        ),
         5 => array (
          'name' => 'price',
          'label' => 'Price',
        ),
         6 => array (
          'name' => 'is_show_as_top_ads',
          'label' => 'Top?',
          'type' => 'boolean',
        ),
         7 => array (
          'name' => 'is_highlighted',
          'label' => 'Highlighted?',
          'type' => 'boolean',
        ),
         8 => array (
          'name' => 'is_urgent',
          'label' => 'Urgent?',
          'type' => 'boolean',
        ),
         9 => array (
          'name' => 'is_show_ad_in_top',
          'label' => 'In Top?',
          'type' => 'boolean',
        ),
         10 => array (
          'name' => 'ad_view_count',
          'label' => 'Views',
          'type' => 'number',
        ),
        11 => array (
          'name' => 'ad_favorite_count',
          'label' => 'Favorites',
          'type' =>'number',
        )        
      ),
      'title' => 'Recent Ads',
      'name' => 'recent_ads',
      'perPage' => 5,
      'order' => 2,
      'template' => '<div class="col-lg-6"><div class="panel"><ma-dashboard-panel collection="dashboardController.collections.recent_ads" entries="dashboardController.entries.recent_ads" datastore="dashboardController.datastore"></ma-dashboard-panel></div></div>'
    )
  )
);
if(isPluginEnabled('Ad/Ad')) {
  $milestone_table = array (
    'categories' => array (
      'listview' => array (
        'fields' => array (
             5 => array(
                     'name' => 'ad_count',
                    'label' => 'Ad Count',
                    'template' => '<a href="#/ads/list?search=%7B%22category_id%22:{{entry.values.id}}%7D">{{entry.values.ad_count}}</a>',
                 ),
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}
 if(isPluginEnabled('Ad/AdFavorite')) {
  $milestone_table = array (
    'ads' => array (
      'listview' => array (
        'fields' => array (
            13 => array(
                    'name' => 'ad_favorite_count',
                    'label' => 'Favorite',
                    'template' => '<a href="#/ad_favorites/list?search=%7B%22ad_id%22:{{entry.values.id}}%7D">{{entry.values.ad_favorite_count}}</a>',
                ) ,
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}
 if(isPluginEnabled('Ad/AdExtra')) {
  $milestone_table = array (
    'ads' => array (
      'listview' => array (
        'fields' => array (
             7 => array(
                    'name' => 'is_show_as_top_ads',
                    'label' => ' Top?',
                    'type' => 'boolean',
                ) ,
                8 => array(
                    'name' => 'is_show_ad_in_top',
                    'label' => ' In Top?',
                    'type' => 'boolean',
                ) ,
                9 => array(
                    'name' => 'is_urgent',
                    'label' => 'Urgent?',
                    'type' => 'boolean',
                ) ,
                10 => array(
                    'name' => 'is_highlighted',
                    'label' => ' Highlighted?',
                    'type' => 'boolean',
                ) ,
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}

 
 

               