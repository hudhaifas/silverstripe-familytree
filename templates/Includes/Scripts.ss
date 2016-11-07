<!-- CSS -->

<!-- Bootstrap -->
$IncludeCSSContent('/css/vendors/bootstrap')
<% if isRTL %>
    $IncludeCSSContent('/css/bootstrap-rtl')
<% end_if %>

<!-- Javascript -->
$IncludeJSContent('/js/vendors/jquery')
$IncludeJSContent('/js/vendors/bootstrap')

<!-- TODO: ADD CONDITION TO CHECK IS GOOGLE MAPS API IS REQUIRED-->
<!-- google map -->
$IncludeJSContent_URL('http://maps.google.com/maps/api/js?sensor=false&amp;language=en')
$IncludeJSContent('/js/vendors/gmap3')

<!-- Fonts -->
$IncludeCSSContent('/fonts/font-awesome')
<link href='//fonts.googleapis.com/css?family=Cinzel+Decorative:400,700,900' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

<!-- Custom Theme files -->
$IncludeCSSContent_Theme('/css/reset')
$IncludeCSSContent_Theme('/css/typography')
$IncludeCSSContent_Theme('/css/form')
$IncludeCSSContent_Theme('/css/layout')

$IncludeJSContent_Theme('/js/script')
<!-- Smooth Scrolling -->
$IncludeJSContent_Theme('/js/move-top')
$IncludeJSContent_Theme('/js/easing')
<script>
    jQuery(document).ready(function ($) {
        $(".scroll").click(function (event) {
            event.preventDefault();

            $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
        });
    });
</script>

<% if isRTL %>
$IncludeCSSContent_Theme('/css/rtl')
<% end_if %>