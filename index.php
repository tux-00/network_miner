<!DOCTYPE html>
<html>

<head>
    <title>Network Miner</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="bootstrap/css/custom.css" type="text/css">
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
                                    <input type="text" class="form-control" id="search_input" autocomplete="off" placeholder="Node search...">
                                </div>
                                <span class="input-group-btn">
										<button class="btn btn-default" type="button" id="search_btn" disabled>Go!</button>
									</span>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
            <div class="row-fluid" id="notif"></div>
        </section>

    </div>

    <script type="text/javascript" src="./js/d3js/d3.min.js"></script>
    <script type="text/javascript" src="./js/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="./js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript" src="./js/jquery-ui-1.11.4/themes/smoothness/jquery-ui.css"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./js/typeahead.js/typeahead.bundle.min.js"></script>
    <script type="text/javascript" src="./js/functions.js"></script>

</body>

</html>
