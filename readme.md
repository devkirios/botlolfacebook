# BOT CHAT LOL

## Introduction
---

The bot are fashionable these days
The bot are practical and easy to use
The bot are grabbing messaging applications
The bot are here to stay

When Facebook introduced the bots as how to streamline processes in the company, I want to program a bot to League of Legends, and when I discovered that there was a contest to manage your API, I just got down to work. Unfortunately for personal reasons, I could not be implemented in this first version all the features planned, however this development can be the basis of a powerful, fast and secure information tool for fans of League of Legends.

## Development
---

The application recognizes the following phrases:

* HELP => shows the commands that accept our Bot

* LOL FREE CHAMPIONS => displays a list of free champions. Each item in this list shows the photo, name, title and role of the champion,also shows the Statistics button and Spells button.
Selecting the Statistics button displays the attack, defense, skill, difficulty and other basic statistics of the champion. By selecting the Spells button, displays the image and description of spells champions.

* CHAMPION LOL xxxx => displays a list of champions that meet the search criteria. Each item in this list shows the photo, name, title and role of the champion,also shows the Statistics button and Spells button.
Selecting the Statistics button displays the attack, defense, skill, difficulty and other basic statistics of the champion. By selecting the Spells button, displays the image and description of spells champions.

* LOL ITEM xxxx => displays a list of items that meet the search criteria. Each item in this list shows the picture, name and description of the item, also shows the Info button. By selecting the Info button detailing the information of the item, the cost of gold, which items are needed to create this item, and items that I can create with this object.

## Fun moments
---

Messenger platform is in beta, the documentation is incomplete and there is little help in the forums of the web, however I like programming and I had fun doing this basic Bot.

For example not know what http://ddragon.leagueoflegends.com/cdn/6.9.1/img/champion/Blitzcrank.png
It is a very long url to send in response to a chat and as a platform Messenger does not send me error alert, I spent many hours discovering the limit on the size of the url, it is for that reason that all images of the site http://ddragon.leagueoflegends.com are shortened using Bitly Api.

Neither knew that I can not send very long messages in a chat, for this reason, very long messages are divided

When the Bot had a programming error I did not know what was wrong, because Messenger Facebook returns nothing when it encounters errors in the script, for this reason Use the Pusher platform for real-time send me the values ​​of certain variables, for the purpose of discovering where this bug in my program. It was the easiest and quickest way I could think to create a log of transactions.

## Next steps
---

- I wish you can find basic information about a player that is implementing the command LOL SUMMONER xxxx

- I want to add a button below the button Hechizos a button called Counters, with the purpose to show the champions with which the champion is stronger or weaker.

- Recommendations build, so that depending on the line or the champion against this fighting recommend some items.

- Finally there are many options that can be added to this powerful tool


## Demo
---

They can test the application by starting a conversation with the facebook page:

https://facebook.com/gamersoficialco

However you can see the following youtube video where the bot is shown in operation:

https://www.youtube.com/watch?v=E3XSaDxp_Vo