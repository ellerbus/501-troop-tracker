<?php

declare(strict_types=1);

namespace App\Actions;

use App\Domain\Results\LoginResult;
use App\Domain\Results\LoginStatus;
use App\Domain\Services\AuthenticationService;
use App\Domain\Services\FlashMessageService;
use App\Domain\Services\TrooperService;
use App\Payloads\LoginPayload;
use App\Responders\HtmlResponder;
use App\Responders\RedirectResponder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginSubmitAction
{
    public function __construct(
        private readonly HtmlResponder $html_responder,
        private readonly RedirectResponder $redirect_responder,
        private readonly AuthenticationService $authentication,
        private readonly TrooperService $troopers,
        private readonly FlashMessageService $flash
    ) {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $result = LoginResult::failed('Unknown Error');

        $payload = new LoginPayload($request->getParsedBody() ?? []);

        if ($payload->isValid())
        {
            $result = $this->authentication->login($payload);

            if ($result->isSuccess())
            {
                $this->troopers->synchronize();

                return $this->redirect_responder->respond($response, 'index.php');
            }
            else
            {
                $this->flash->danger($result->getErrorMessage());
            }
        }

        $data = $result->getDataPayload('password');

        return $this->html_responder->respond($response, 'pages/login.html', $data);
    }
}

/*

        IF forumLogin.success == 1:
            UPDATE troopers SET forum_id = forumLogin.user.username WHERE user_id = forumLogin.user.user_id

        SELECT trooper FROM troopers WHERE forum_id = POST.tkid LIMIT 1
        trooperCount = 0

        FOR EACH db IN query result:
            trooperCount += 1

            // IF forumLogin.success == 1 AND forumLogin.user.is_banned == 1:
            //     // User is banned from the forum
            //     EXIT loop

            IF db.permissions == 3:
                // RIP Trooper — account belongs to someone no longer active
                EXIT loop

            IF forumLogin.success == 1 OR (password_verify(POST.password, db.password) AND db.permissions == 1):
                // Credentials are valid — either via forum or local password

                IF db.approved != 0:
                    // Trooper has been approved

                    IF canAccess(db.id):
                        // Trooper has access rights

                        SESSION.id = db.id
                        SESSION.tkid = db.tkid

                        IF forumLogin.success == 1:
                            // Forum login succeeded — sync password, email, and user ID
                            hashedPassword = hash(POST.password)
                            UPDATE troopers SET password = hashedPassword, email = forumLogin.user.email, user_id = forumLogin.user.user_id WHERE id = db.id

                        IF POST.keepLog == 1:
                            // User wants to stay logged in — set cookies
                            SET cookie TroopTrackerUsername = db.forum_id
                            SET cookie TroopTrackerPassword = POST.password

                        IF COOKIE.TroopTrackerLastEvent is set:
                            // Redirect to last event and clear cookie
                            REDIRECT to index.php?event=COOKIE.TroopTrackerLastEvent
                            CLEAR cookie TroopTrackerLastEvent
                        ELSE:
                            // No last event — redirect to home
                            REDIRECT to index.php
                    ELSE:
                        // Trooper is retired — no access
                        DISPLAY "Account is retired"
                ELSE:
                    // Trooper not yet approved
                    DISPLAY "Access not approved"
            ELSE:
                // Invalid credentials
                DISPLAY "Incorrect username or password"
                DISPLAY "Contact Webmaster or post help request"

        IF trooperCount == 0:
            // No matching trooper found
            DISPLAY "Account not found"
            DISPLAY "Contact Webmaster or post help request"
    ELSE:
        // No login submission — show login form
        DISPLAY login form

 // Display submission for register account, otherwise show the form
if (isset($_POST['loginWithTK'])) {
    // Login with forum
    $forumLogin = loginWithForum($_POST['tkid'], $_POST['password']);

    // Check credentials
    if (isset($forumLogin['success']) && $forumLogin['success'] == 1) {
        // Update username if changed
        $statement = $conn->prepare("UPDATE troopers SET forum_id = ? WHERE user_id = ?");
        $statement->bind_param("si", $forumLogin['user']['username'], $forumLogin['user']['user_id']);
        $statement->execute();
    }

    // Get data
    $statement = $conn->prepare("SELECT * FROM troopers WHERE forum_id = ? LIMIT 1");
    $statement->bind_param("s", $_POST['tkid']);
    $statement->execute();

    // Trooper count
    $i = 0;

    if ($result = $statement->get_result()) {
        while ($db = mysqli_fetch_object($result)) {
            // Increment trooper count
            $i++;


            // Check if RIP trooper
            if ($db->permissions == 3) {
                echo '
                <p>
                </p>';

                break;
            }

            // Check credentials
            if (isset($forumLogin['success']) && $forumLogin['success'] == 1 || (password_verify($_POST['password'], $db->password) && $db->permissions == 1)) {
                if ($db->approved != 0) {
                    if (canAccess($db->id)) {
                        // Set session
                        $_SESSION['id'] = $db->id;
                        $_SESSION['tkid'] = $db->tkid;

                        // If logged in with forum details, and password does not match
                        if (isset($forumLogin['success']) && $forumLogin['success'] == 1) {
                            // Update password, e-mail, and user ID
                            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                            $statement = $conn->prepare("UPDATE troopers SET password = ?, email = ?, user_id = ? WHERE id = ?");
                            $statement->bind_param("ssii", $password, $forumLogin['user']['email'], $forumLogin['user']['user_id'], $db->id);
                            $statement->execute();
                        }

                        // Set log in cookie, if set to keep logged in
                        if (isset($_POST['keepLog']) && $_POST['keepLog'] == 1) {
                            // Set cookies
                            setcookie("TroopTrackerUsername", $db->forum_id, time() + (10 * 365 * 24 * 60 * 60));
                            setcookie("TroopTrackerPassword", $_POST['password'], time() + (10 * 365 * 24 * 60 * 60));
                        }

                        // Cookie set
                        if (isset($_COOKIE["TroopTrackerLastEvent"])) {
                            echo '
                            <meta http-equiv="refresh" content="5; URL=index.php?event=' . cleanInput($_COOKIE["TroopTrackerLastEvent"]) . '" />

                            <div style="margin-top: 25px; color: green; text-align: center; font-weight: bold;">
                            You have now logged in!
                            <br /><br />
                            <a href="index.php?event=' . cleanInput($_COOKIE["TroopTrackerLastEvent"]) . '">Click here to view the event</a> or you will be redirected shortly.
                            </div>';

                            // Clear cookie
                            setcookie("TroopTrackerLastEvent", "", time() - 3600);
                        } else {
                            // Cookie not set
                            echo '
                            <div style="margin-top: 25px; color: green; text-align: center; font-weight: bold;">
                            You have now logged in!
                            <br /><br />
                            <a href="index.php">Click here to go home.</a>
                            </div>';
                        }
                    } else {
                        echo '
                        Your account is retired. Please contact your squad / club leader for further instructions on how to get re-approved.';
                    }
                } else {
                    echo '
                    ';
                }
            } else {
                echo '
                <p>
                    Incorrect username or password. <a href="/login">Try again?</a>
                </p>

                <p>
                    If you are unable to access your account, please contact the ' . garrison . ' Webmaster, or post a help request on the forums. Your FL Garrison boards name may not match the Troop Tracker records.
                </p>';
            }
        }
    }

    // An account does not exist
    if ($i == 0) {
        echo '
        <p>Account not found. <a href="/login">Try again?</a></p>

        <p>Please contact the Garrison Webmaster or post a help request on the forums, if you continue to have issues. Your FL Garrison boards name may not match the Troop Tracker records.</p>';
    }
} else {
    echo '
    <form action="/login" method="POST" name="loginForm" id="loginForm">
        <p>Board Name:</p>
        <input type="text" name="tkid" id="tkid" />

        <p>Password:</p>
        <input type="password" name="password" id="password" />

        <br /><br />

        <input type="checkbox" name="keepLog" value="1" /> Keep me logged in

        <br /><br />

        <input type="submit" value="Login!" name="loginWithTK" />
    </form>

    <p>
        <small>
            <b>Remember:</b><br />Login with your ' . garrison . ' board username and password.
        </small>
    </p>';
}*/
