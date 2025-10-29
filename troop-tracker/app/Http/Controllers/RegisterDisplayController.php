<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ClubService;
use App\Services\FlashMessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RegisterDisplayController extends Controller
{
    public function __construct(
        private readonly FlashMessageService $flash,
        private readonly ClubService $clubs
    ) {

    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $clubs = $this->clubs->findAllActive(include_squads: true);

        $data = [
            'clubs' => $clubs
        ];

        return view('pages.register', $data);
    }


    /*
    // Request access
if(isset($_GET['do']) && $_GET['do'] == "requestaccess")
{
    if($_POST['submitRequest'])
    {
        // Check we have all the data we need server side. JQuery should do this, but this is a backup
        $failed = false;

        echo '<ul>';

        // If name empty
        if($_POST['name'] == "")
        {
            $failed = true;
            echo '<li>Please enter your name.</li>';
        }

        // Set TKID
        $tkid = !empty($_POST['tkid']) ? $_POST['tkid'] : 0;

        // If Forum ID empty
        if($_POST['forumid'] == "")
        {
            $failed = true;
            echo '<li>Please enter your '.garrison.' Forum Username.</li>';
        }

        // If TKID is greather than 11 characters
        if(strlen($tkid) > 11 && in_array($_POST['squad_request'], $validSquadIDs))
        {
            $failed = true;
            echo '<li>TKID must be less than eleven (11) characters.</li>';
        }

        // If TKID is not numeric
        if(!is_numeric($tkid) && in_array($_POST['squad_request'], $validSquadIDs))
        {
            $failed = true;
            echo '<li>TKID must be an integer.</li>';
        }

        // Check if TKID can be 0
        if($_POST['accountType'] == 1 && $tkid == 0 && in_array($_POST['squad_request'], $validSquadIDs))
        {
            $failed = true;
            echo '<li>TKID cannot be zero.</li>';
        }

        // Check if TKID cannot be 0
        if($_POST['accountType'] == 4 && $tkid > 0 && in_array($_POST['squad_request'], $validSquadIDs))
        {
            $failed = true;
            echo '<li>TKID must be zero for a handler account.</li>';
        }

        // Set squad variable
        $squad = $_POST['squad_request'];

        // Set up ID Check
        $idcheck = 0;

        // Check if 501st
        if(in_array($squad, $validSquadIDs))
        {
            // Build the SQL placeholders
            $placeholders = implode(',', array_fill(0, count($validSquadIDs), '?'));
            $sql = "SELECT id FROM troopers WHERE tkid = ? AND squad IN ($placeholders)";

            // Prepare the statement
            $statement = $conn->prepare($sql);

            // Create types string (tkid + all squad IDs are integers)
            $types = str_repeat('i', count($validSquadIDs) + 1);

            // Combine tkid with squad IDs
            $params = array_merge([$tkid], $validSquadIDs);

            // Bind parameters
            $statement->bind_param($types, ...$params);
            $statement->execute();
            $statement->store_result();
            $idcheck = $statement->num_rows;
        }

        // Query forum
        $statement = $conn->prepare("SELECT forum_id FROM troopers WHERE forum_id = ?");
        $statement->bind_param("s", $_POST['forumid']);
        $statement->execute();
        $statement->store_result();
        $forumcheck = $statement->num_rows;

        // Check if 501st forum exists
        if($forumcheck > 0)
        {
            $failed = true;
            echo '<li>'.garrison.' Forum Name is already taken. Please contact the '.garrison.' Webmaster for further assistance.</li>';
        }

        // Loop through clubs
        foreach($clubArray as $club => $club_value)
        {
            // If DB3 defined
            if($club_value['db3Name'] != "")
            {
                // Query Club - if specified
                if($_POST[$club_value['db3']] != "")
                {
                    $statement = $conn->prepare("SELECT ".$club_value['db3']." FROM troopers WHERE ".$club_value['db3']." = ?");
                    $statement->bind_param("s", $_POST[$club_value['db3']]);
                    $statement->execute();
                    $statement->store_result();
                    $clubid = $statement->num_rows;


                    // Check if club ID exists
                    if($clubid > 0)
                    {
                        $failed = true;
                        echo '<li>'.$club_value['name'].' ID is already taken. Please contact the '.garrison.' Webmaster for further assistance.</li>';
                    }
                }
            }
        }

        // Check if ID exists, if not set to 0
        if($_POST["accountType"] == 1 && $idcheck > 0)
        {
            $failed = true;
            echo '<li>TKID is taken. If you have troops on the old troop tracker, <a href="index.php?action=setup">click here to request access</a>.</li>';
        }

        // Login with forum
        $forumLogin = loginWithForum($_POST['forumid'], $_POST['forumpassword']);

        // Verify forum and password
        if(!isset($forumLogin['success']))
        {
            $failed = true;
            echo '<li>Incorrect '.garrison.' Board username and password.</li>';
        } else {
            // Query user_id to prevent duplicates
            $statement = $conn->prepare("SELECT user_id FROM troopers WHERE user_id = ?");
            $statement->bind_param("i", $forumLogin['user']['user_id']);
            $statement->execute();
            $statement->store_result();
            $forumcheck = $statement->num_rows;

            // Check if user_id exists
            if($forumcheck > 0)
            {
                $failed = true;
                echo '<li>You already have a Troop Tracker account.</li>';
            }
        }

        if(!@validPhone($_POST['phone']) && !empty($_POST['phone']))
        {
            $failed = true;
            echo '<li>Enter a valid phone number.</li>';
        }

        // If failed
        if(!$failed)
        {
            // Set up permission vars
            $p501 = 0;

            // Loop through clubs
            foreach($clubArray as $club => $club_value)
            {
                // Add permission vars
                ${$club_value['db']} = 0;
            }

            // Set permissions
            // 501
            if(in_array($_POST['squad_request'], $validSquadIDs))
            {
                $p501 = $_POST['accountType'];
            }

            // Hash password
            $password = password_hash($_POST['forumpassword'], PASSWORD_DEFAULT);

            // Insert
            $_POST['phone'] = cleanInput($_POST['phone']);
            $_POST['phone'] = preg_replace('/\D/', '', $_POST['phone']);
            $statement = $conn->prepare("INSERT INTO troopers (user_id, name, tkid, email, forum_id, p501, phone, squad, password) VALUES (?, ?, ?, ?, ?,?, ?, ?, ?)");
            $statement->bind_param("isissisis", $forumLogin['user']['user_id'], $_POST['name'], $tkid, $forumLogin['user']['email'], $_POST['forumid'], $p501, $_POST['phone'], $squad, $password);
            $statement->execute();

            // Last ID
            $last_id = $conn->insert_id;

            // Loop through clubs
            foreach($clubArray as $club => $club_value)
            {
                // If has value
                if(isset($_POST[$club_value['db3']]) && ($_POST[$club_value['db3']] != "" || $_POST[$club_value['db3']] > 0))
                {
                    // Change value
                    ${$club_value['db']} = $_POST['accountType'];
                }
                else
                {
                    // If contains ID (which would make the value an int)
                    if (isset($_POST[$club_value['db3']]) && strpos($club_value['db3'], "id") !== false)
                    {
                        // Set as int
                        $_POST[$club_value['db3']] = 0;
                    }
                }

                // Make member of club
                if($club_value['squadID'] == $_POST['squad_request'])
                {
                    // Change value
                    ${$club_value['db']} = $_POST['accountType'];
                }

                // Club membership DB value set
                if($club_value['db'] != "")
                {
                    $statement = $conn->prepare("UPDATE troopers SET " . $club_value['db'] . " = ? WHERE id = ?");
                    $statement->bind_param("si", ${$club_value['db']}, $last_id);
                    $statement->execute();
                }

                // If Club membership DB member identifer set
                if($club_value['db3'] != "")
                {
                    $statement = $conn->prepare("UPDATE troopers SET " . $club_value['db3'] . " = ? WHERE id = ?");
                    $statement->bind_param("si", $_POST[$club_value['db3']], $last_id);
                    $statement->execute();
                }
            }

            echo '<li>Request submitted! You will receive an e-mail when your request is approved or denied.</li>';
        }

        echo '</ul>';
    }
} */
}
