<!--
        else
        {
            // Get count for awards
            $statement = $conn->prepare("SELECT id FROM event_sign_up WHERE status = '3' AND trooperid = ?");
            $statement->bind_param("i", $profile);
            $statement->execute();
            $statement->store_result();
            $count = $statement->num_rows;

            // Set up award count
            $j = 0;

            echo '
			</table>
			</div>
			
			<div class="profile-awards">
			
			<h2 class="tm-section-header" id="awards-header">Awards</h2>';

            // Check if supporter
            if (isSupporter($profile))
            {
                echo '<img src="images/flgdonate.png" />';
            }

            echo '
			<ul>';

            // Reduce array to unique values
            $troopedSquads = array_unique($troopedSquads);

            // Remove garrison from array
            $troopedSquads = array_diff($troopedSquads, array(0, -1));

            // If trooped in every squad, show an award
            if (count($troopedSquads) == count($squadArray))
            {
                echo '<li><b>Trooped Every Squad!<b></li>';
            }

            if ($count >= 1)
            {
                echo '<li>First Troop Completed!</li>';
                $j++;
            }

            if ($count >= 10)
            {
                echo '<li>10 Troops</li>';
            }

            if ($count >= 25)
            {
                echo '<li>25 Troops</li>';
            }

            if ($count >= 50)
            {
                echo '<li>50 Troops</li>';
            }

            if ($count >= 75)
            {
                echo '<li>75 Troops</li>';
            }

            if ($count >= 100)
            {
                echo '<li>100 Troops</li>';
            }

            if ($count >= 150)
            {
                echo '<li>150 Troops</li>';
            }

            if ($count >= 200)
            {
                echo '<li>200 Troops</li>';
            }

            if ($count >= 250)
            {
                echo '<li>250 Troops</li>';
            }

            if ($count >= 300)
            {
                echo '<li>300 Troops</li>';
            }

            if ($count >= 400)
            {
                echo '<li>400 Troops</li>';
            }

            if ($count >= 500)
            {
                echo '<li>500 Troops</li>';
            }

            if ($count >= 501)
            {
                echo '<li>501 Troops Award</li>';
            }

            // Get data from custom awards - load award user data
            $statement = $conn->prepare("SELECT award_troopers.awardid, award_troopers.trooperid, awards.id, awards.title, awards.icon FROM award_troopers LEFT JOIN awards ON awards.id = award_troopers.awardid WHERE award_troopers.trooperid = ?");
            $statement->bind_param("i", $profile);
            $statement->execute();

            if ($result2 = $statement->get_result())
            {
                while ($db2 = mysqli_fetch_object($result2))
                {
                    // If has icon...
                    if ($db2->icon == "")
                    {
                        echo '<li>' . $db2->title . '</li>';
                    }
                    else
                    {
                        echo '<li style="list-style-image: url(\'images/icons/' . $db2->icon . '\');">' . $db2->title . '</li>';
                    }

                    $j++;
                }
            }

            if ($j == 0)
            {
                echo '<li>No awards yet!</li>';
            }

            echo '
			</ul>
			</div>

			<h2 class="tm-section-header" id="photo_section">Tagged Photos</h2>

			<div class="profile-donations">';

            // Set results per page
            $results = 5;

            // Get total results - query
            $statement = $conn->prepare("SELECT COUNT(uploads.id) AS total FROM uploads LEFT JOIN tagged ON uploads.id = tagged.photoid WHERE tagged.trooperid = ? AND admin = 0");
            $statement->bind_param("i", $profile);
            $statement->execute();
            $statement->bind_result($rowPage);
            $statement->fetch();
            $statement->close();

            // Set total pages
            $total_pages = ceil($rowPage / $results);

            // If page set
            if (isset($_GET['page']))
            {
                // Get page
                $page = intval($_GET['page']);

                // Start from
                $startFrom = ($page - 1) * $results;
            }
            else
            {
                // Default
                $page = 1;

                // Start from - default
                $startFrom = 0;
            }

            // Query database for photos
            $statement = $conn->prepare("SELECT uploads.filename, uploads.trooperid, uploads.id FROM uploads LEFT JOIN tagged ON uploads.id = tagged.photoid WHERE tagged.trooperid = ? AND admin = 0 ORDER BY uploads.date DESC LIMIT ?, ?");
            $statement->bind_param("iii", $profile, $startFrom, $results);
            $statement->execute();

            // Count photos
            $i = 0;

            if ($result = $statement->get_result())
            {
                while ($db = mysqli_fetch_object($result))
                {
                    echo '
					<div class="container-image">
						<a href="images/uploads/' . $db->filename . '" data-lightbox="photosadmin" data-title="Uploaded by ' . getName($db->trooperid) . '" id="photo' . $db->id . '"><img src="images/uploads/resize/' . getFileName($db->filename) . '.jpg" width="200px" height="200px" class="image-c" /></a>
						
						<p class="container-text">
							<a href="index.php?action=editphoto&id=' . $db->id . '">Edit</a>
							<br />
							<a href="#" photoid="' . $db->id . '" name="tagged">' . (isInPhoto($db->id, $_SESSION['id']) ? 'Untag Me' : 'Tag Me') . '</a>
						</p>
					</div>';

                    $i++;
                }

                // If photos
                if ($i > 0)
                {
                    echo '
					<p class="center-content">
						<i>Press photos for full resolution version.</i>
					</p>';
                }
                else
                {
                    echo '
					<p class="center-content">
						No tagged photos to display.
					</p>';
                }
            }

            // If photos
            if ($total_pages > 1)
            {
                echo '<p>Pages: ';

                // Loop through pages
                for ($j = 1; $j <= $total_pages; $j++)
                {
                    // If we are on this page...
                    if ($page == $j)
                    {
                        echo '
						' . $j . '';
                    }
                    else
                    {
                        echo '
						<a href="index.php?profile=' . cleanInput($_GET['profile']) . '&page=' . $j . '#photo_section">' . $j . '</a>';
                    }

                    // If not that last page, add a comma
                    if ($j != $total_pages)
                    {
                        echo ', ';
                    }
                }

                echo '</p>';
            }

            echo '
			</div>
			
			<div class="profile-donations">
			
			<h2 class="tm-section-header" id="donation-header">Donations</h2>
			
			<ul>';

            // Find the position of the last slash
            $lastSlashPos = strrpos($forumURL, '/');

            // If a slash was found, truncate the URL after it
            if ($lastSlashPos !== false)
            {
                $cleanedURL = substr($forumURL, 0, $lastSlashPos + 1);
            }

            // Get JSON
            $json = file_get_contents($cleanedURL . 'user-upgrades.php');
            $obj = json_decode($json, true);

            // Check if the JSON was decoded properly
            if ($obj === null)
            {
                die('Error decoding JSON data.');
            }

            // Initialize a variable to store the total cost
            $getSupportNum = 0;

            // Get user_id
            $user_id = getUserID($profile);

            // Reset for donations
            $j = 0;

            // Check if the combinedResults array exists
            if (isset($obj['combinedResults']) && is_array($obj['combinedResults']))
            {
                // Loop through each result in combinedResults
                foreach ($obj['combinedResults'] as $result)
                {
                    foreach ($obj['userUpgrades'] as $result2)
                    {
                        // Check if this result has the specific user_upgrade_id
                        if (isset($result2['user_upgrade_id']) && $result2['user_upgrade_id'] == $result['user_upgrade_id'] && $result['user_id'] == $user_id)
                        {
                            foreach ($obj['paymentLog'] as $result3)
                            {
                                // Check if this result has the specific purchase_request_key
                                if ($result['purchase_request_key'] == $result3['purchase_request_key'])
                                {
                                    $obj2 = json_decode($result3['log_details'], true);
                                    $timestamp = strtotime($obj2['payment_date']);
                                    $date = new DateTime();
                                    $date->setTimestamp($timestamp);

                                    echo '<li>$' . intval($obj2['payment_gross']) . ' on ' . $date->format('m/d/Y') . '</li>';
                                    $j++;
                                }
                            }
                        }
                    }
                }
            }

            // Get data from custom awards - load award user data
            $statement = $conn->prepare("SELECT * FROM donations WHERE trooperid = ? ORDER BY datetime DESC");
            $statement->bind_param("i", $profile);
            $statement->execute();

            if ($result2 = $statement->get_result())
            {
                while ($db2 = mysqli_fetch_object($result2))
                {
                    $formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);

                    echo '<li>' . $formatter->formatCurrency($db2->amount, 'USD') . ' on ' . date("m/d/Y", strtotime($db2->datetime)) . '</li>';
                    $j++;
                }
            }

            if ($j == 0)
            {
                echo '<li>No donations yet!</li>';
            }
            echo '
			</ul>
			
			</div>';
        }
      -->