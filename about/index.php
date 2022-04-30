<?php
/**
 * root/about/index.php
 *
 * Description of the app to help user
 *
 * PHP version 7.0.33-0+deb9u12
 *
 * @category  ?
 * @package   ?
 * @author    Steve Greig <greigsteve@gmail.com>
 * @copyright 2022 Steve Greig
 * @license   GPL-3.0-or-later gnu.org
 * @version   0
 * @link      http://stevespages.org.uk/
 */

session_start();
/*
 * Error reporting
 * Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../header.php';
?>
    <title>Golf</title>
  </head>
  <body>
    <nav> <p> <a href="../">Home</a> </p> </nav>
    <h1>About</h1>
    <p>
      Firstly a competition is created by providing a name and optionaly other information. Players can then be created by entering a name and email address for each player. Teams can be created by entering a name for each team. Players are then assigned to teams.
    </p>
    <p>
      When the players and teams for the competition have been created, you can bulk send all the players an email inviting them to click on a link to a web page from which they can enter their handicap and scores. As they do so, their scores and calculated points are available to you. For players who prefer not to submit their scores in this way you can submit the data for them. Alternatively you can download the data from this website to, for example, a spreadsheet and then complete the data entry for the competition there.
    </p>
  </body>
</html>
