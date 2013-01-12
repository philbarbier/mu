<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsRvkq_6P9NcGuzUTBWNDnuRK3WirwuKM&sensor=false"></script>

<script type="text/javascript">
    $(document).ready(function() {
    }
</script>

<body>

<ul>
<?
    foreach($mashups as $k => $v) {
        
        echo "\n" . '<li><a href="index.php/view?muid='.$v->id.'">'.$v->title.'</a></li>';
    }
?>
</ul>

</body>
