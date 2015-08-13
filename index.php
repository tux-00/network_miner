<!DOCTYPE html>
<html>

<head>
    <title>Network Miner</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/custom.css" type="text/css">
    <link rel="icon" href="img/favicon.ico">
</head>

<body>
    <div class="container-fluid">

        <header>
            <div class="page-header">
                <h2>Network Miner</h2>
            </div>
        </header>

        <section>
            <div class="row-fluid">
                <div class="well">
                    <fieldset>
                        <form class="form-inline">
                            <button type="button" id="discover_btn" class="btn btn-primary pull-left" data-loading-text="Discover in progress...">
                                <span class="glyphicon glyphicon-eye-open"></span> Discover
                            </button>
                            <div class="input-group pull-right">
                                <div class="scrollable-dropdown-menu">
                                    <input type="text" class="form-control" id="search_input" autocomplete="off" placeholder="Device search...">
                                </div>
                                <span class="input-group-btn">
									<button class="btn btn-default" type="button" id="search_btn">Go!</button>
								</span>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
            <div class="row-fluid" id="notif"></div>
        </section>

    </div>

    <script type="text/javascript" src="bower_components/d3/d3.min.js"></script>
    <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="bower_components/jqueryui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="bower_components/jqueryui/themes/smoothness/jquery-ui.min.css"></script>
    <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="bower_components/typeahead.js/dist/typeahead.bundle.min.js"></script>
    <script type="text/javascript" src="functions.js"></script>

</body>

</html>
