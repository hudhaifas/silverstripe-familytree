<!DOCTYPE html>
<!--
>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
Simple. by Sara (saratusar.com, @saratusar) for Innovatif - an awesome Slovenia-based digital agency (innovatif.com/en)
Change it, enhance it and most importantly enjoy it!
>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
-->

<!--[if IE 6 ]><html lang="$ContentLocale" class="ie ie6"><![endif]-->
<!--[if IE 7 ]><html lang="$ContentLocale" class="ie ie7"><![endif]-->
<!--[if IE 8 ]><html lang="$ContentLocale" class="ie ie8"><![endif]-->
<!--[if !IE]><!--><html lang="$ContentLocale"><!--<![endif]-->
    <head>
        <% base_tag %>

        <title>$SiteConfig.Title - <% if $MetaTitle %>$MetaTitle<% else %>$Title<% end_if %></title>

        <link rel="shortcut icon" href="$ThemeDir/images/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="$ThemeDir/images/favicon.ico" type="image/x-icon" />

        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="Author" content="Hudhaifa Shatnawi" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="Family, Tree" />
        $MetaTags(false)

        <!--[if lt IE 9]>
        <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        $IncludeCSSContent('/css/vendors/bootstrap')
        $IncludeJSContent('/js/vendors/jquery')
        $IncludeJSContent('/js/vendors/bootstrap')
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

        <% require themedCSS('layout') %>

        <% require css('familytree/css/jquery.jOrgChart.css') %>
        <% require css('familytree/css/familytree.css') %>

        <% require javascript('familytree/js/jquery.jOrgChart.js') %>
        <% require javascript('familytree/js/dragscroll.js') %>

        <script>
        </script>
    </head>

    <body>
        <% include Header %>

        $Layout 
        
        <% include Footer %>
    </body>
</html>