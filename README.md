# Golf

This web app, written in PHP and JavaScript, allows someone to register with their name and a password. They can then use the app to help them record the scores and points of players allocated to teams for a golf competition.

## Usage

Someone who wants to administer a golf comptetition can register with this app by giving a username and password. Before creating a competition in the app they need to create a course and some players. The courses require the par and stroke index for each hole. The players need to have a name and valid email address. They can then create competitions. For any competition they can choose which course it will be held at. They can choose team names for the competition and add players to the teams.

Once they are satisfied they have the right teams and players they can click a "send emails" button. All the players will be sent an email inviting them to follow a link to a webpage which they can use to submit their handicap and their scores for each hole. On submitting these the results are displayed on a leader board page which can be accessed publicly.

In order to avoid players needing to register with the site while still providing a secure page for them to submit their scores, an unguessable token is generated for each email sent out. This forms part of the link which they use to submit their scores. They are only able to submit their scores if the link they use has this token. Effectively this means that a given player can only submit their own scores assuming only they have access to their email account.

### `authentication/`

This directory contains the code for handling registration and login. It is based on the `authentication` app which has a separate github repository (https://github.com/stevespages/authentication). This version will diverge from the version in the `authentication` repository as they are linked. It might be a good idea to somehow use that version from this app so that improvements to `authentication` become incorporated into this code base. It will not be a good idea to have similar but diverged versions of the same basic functionality.

### `sqlite/database.db`

This `sqlite` database is used to store user data. The content is likely to become out of date as the version here is not the version on the server that gets updated when the app is used.
