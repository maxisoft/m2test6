<?php
include_once 'autoload.php';
$session = new \website\utils\Session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>What's up</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Jquery -->
    <script src="js/jquery-1.11.3.min.js"></script>

    <!-- Select2 -->
    <link href="css/select2.min.css" rel="stylesheet"/>
    <script src="js/select2.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">
    <label>
        <select class="select2-user-search" style="min-width: 150pt">
        </select>
    </label>
</div>
<!-- /container -->
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
<script>

    function formatData(data) {
        if (data.loading) return data.text;
        return data.first_name + " " + data.last_name;
    }

    function formatDataSelection(data) {
        if (data.id === "") return data.text;
        return data.first_name + " " + data.last_name;
    }


    $(function () {
        $(".select2-user-search").select2({
            ajax: {
                url: "api/user/search.php",
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {results: data.items};
                },
                cache: true
            },
            placeholder: "Select a user",
            allowClear: true,
            minimumInputLength: 2,
            templateResult: formatData,
            templateSelection: formatDataSelection
        });
    });
</script>
</body>
</html>
