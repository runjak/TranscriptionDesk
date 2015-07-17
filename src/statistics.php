<html lang="en">
    <head>
        <?php require_once 'head.php';?>
        <script src="js/statistics.js"></script>
        <script src="js/Chart.min.js"></script>
        <title>Statistics</title>
        <style>
            .chart-legend li span{
                display: inline-block;
                width: 12px;
                height: 12px;
                margin-right: 5px;
            }
        </style>
    </head>
    <body><?php include_once('navbar.php'); ?>
        <div class="container">
            <div class="row">
                <h1>Statistical overview of the Transcriptiondesk Project</h1>
                <p>Here you can look at different data collected from our Database. Like the current activity, highscorse which languages are used more frequently and much more!</p>
                <h2>Overall activity this month:</h2>
                <canvas id="lineChart" height="50"></canvas>
            </div>
            <div class="row">
                <h2>Languages transcribed:</h2>
                <div class="col-md-6">
                    <canvas id="barChart" height="100"></canvas>
                    <div id="barLegend" class="chart-legend"></div>
                </div>
                <div class="col-md-6">
                    <h2></h2>
                    <canvas id="languageChart" height="100"></canvas>
                    <div id="languageLegend" class="chart-legend"></div>
                </div>
            </div>
            <div class="row">
                <h2>Highscores</h2>
                <div class="col-md-4">
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <h3 align="center">Overall</h3>
                            <table class="table table-condensed">
                                <tr>
                                    <th>Place</th>
                                    <th>User</th>
                                    <th>Points</th>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Xian-Xu</td>
                                    <td>9001</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Hans</td>
                                    <td>500</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Max</td>
                                    <td>400</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>DarkLoard</td>
                                    <td>350</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>MLG_TranscribeZ</td>
                                    <td>325</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Anonymous</td>
                                    <td>310</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Jakob</td>
                                    <td>300</td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Franz</td>
                                    <td>290</td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>IntelClub</td>
                                    <td>289</td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Wolfmother</td>
                                    <td>269</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <h3 align="center">This month</h3>
                            <table class="table table-condensed">
                                <tr>
                                    <th>Place</th>
                                    <th>User</th>
                                    <th>Points</th>
                                <tr>
                                    <td>1</td>
                                    <td>Xian-Xu</td>
                                    <td>901</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Hans</td>
                                    <td>50</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Max</td>
                                    <td>40</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>DarkLoard</td>
                                    <td>35</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>MLG_TranscribeZ</td>
                                    <td>32</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Anonymous</td>
                                    <td>31</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Jakob</td>
                                    <td>30</td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Franz</td>
                                    <td>29</td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>IntelClub</td>
                                    <td>29</td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Wolfmother</td>
                                    <td>26</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <h3 align="center">Today</h3>
                            <table class="table table-condensed">
                                <tr>
                                    <th>Place</th>
                                    <th>User</th>
                                    <th>Points</th>
                                <tr>
                                    <td>1</td>
                                    <td>Xian-Xu</td>
                                    <td>90</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Hans</td>
                                    <td>5</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Max</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>DarkLoard</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>MLG_TranscribeZ</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Anonymous</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Jakob</td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Franz</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>IntelClub</td>
                                    <td>2</td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Wolfmother</td>
                                    <td>1</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
