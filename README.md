 ![Alsace Nature](./assets/img/logo-sorties-nature.png)

# Summary: 

- [Build With](#build-with "All versions we use for this app")
- [Configuration](#configuration "All you need to run this app on your server")
    - [**If you want to make some changes in dev envrionement**](#if-you-want-to-make-some-changes-in-dev-environment-) 
    - [**If you want to deploy on your server**](#if-you-want-to-deploy-on-your-server-)
- [Hierarchy](#hierarchy) 
- [ToDo](#todo)
- [Authors](#authors)

---

## Build With

- [PHP 7.4](https://www.php.net/)
- [Symfony](https://github.com/symfony/symfony)
- [GrumPHP](https://github.com/phpro/grumphp)
- [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [PHPStan](https://github.com/phpstan/phpstan)
- [PHPMD](http://phpmd.org)
- [ESLint](https://eslint.org/)
- [Sass-Lint](https://github.com/sasstools/sass-lint)
- [Travis CI](https://github.com/marketplace/travis-ci)

## Configuration

All you need to run this app on your server.

When you have cloned this app, you have to run a `composer install` 
> Take a coffee 

Then you have to run `yarn install`
> Take a second coffee

This two past command will download all the packages 
you need to run the app on your server. There are all available in `composer.json` and in `yarn.lock`.

Then you have to configure your `.env` :


### **If you want to make some changes in dev environment :** 

1. You have to create a `.env.local`. 
    > DATABASE_URL=mysql://_youruser_:_yourpassword_@127.0.0.1:3306/alsaceNature

    Change _youruser_ and by _yourpassword_ with your mysql credentials
    Please double check your port and your localServer address for the BDD.
    
1. Configure mailer:
    
    We develop this application with Gmail and mailer, feel free to change anything
    you want if you prefer another mailer.
    
    >MAILER_DSN=gmail://_yourmail_:_yourpassword_@default
    
    Change _yourmail_ and _yourpassword_ with your gmail credentials
     (don't forget to configure your [google account](https://support.google.com/a/answer/6260879?hl=fr#:~:text=acc%C3%A9dez%20%C3%A0%20S%C3%A9curit%C3%A9-,Applications%20moins%20s%C3%A9curis%C3%A9es.,au%20bas%20de%20la%20page.&text=Ensuite%2C%20dans%20la%20section%20Applications,relatifs%20aux%20applications%20moins%20s%C3%A9curis%C3%A9es.
    "How to configure your google account")).

1. Run `yarn encore dev` to compile all scss file 
and create all builds needed.


### **If you want to deploy on your server :**

If you use your own script for deployment please go on next topic.<br>
Else: 

1. Clone master on your server.

2. Same as dev environment [↑](#if-you-want-to-make-some-changes-in-dev-environment-) but you have to configure `.env` instead (Gmail is depreciated in prod environment).

3. Run `composer install` `yarn install`  and then run `yarn encore prod`.

4. Run `APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear`
To convert the app from dev in prod.

5. Run `yarn encore prod`.

---

#### In both case:  

1. Create your database, run `php bin/console doctrine:database:create`.

2. Run `php bin/console d:s:u --force`.

Run your app with ``symfony server:start``

Shut down your app with ``symfony server:stop``
  
---
 
## Hierarchy

There is 6 level of hierarchy on this app:
- Level 1 : **Anonymous** :  user who is not connected and just navigate in the front pages.
- Level 2 : **User** (ROLE_USER) : a user who has account but no permission and can just navigate in front page and can participate to event and add some favorite events.
- Level 3 : **Intevenant** (ROLE_EVENT) : a user who can create events for a specific structure (granted by admin structure or admin territory if the structure is in his territory or superAdmin)
- Level 4 : **Admin Structure** (ROLE_ORGA) : admin of a structure can manage events and users of his structure (granted by admin territory if the structure is in his territory or superAdmin)
- Level 5 : **Admin Territory** (ROLE_TERRITORY) : admin of a territory (department) can manage structure of his territory (granted by superAdmin)
- Level 6 : **superAdmin** (ROLE_ADMIN) : superAdmin of the app. 

  
## ToDo
 
This is the list of user story that we didn't dev.

 - Create a status validation to new structure (every new structure has to be validate, cancel or delete by an admin).
 - Add notifications when a user ask to change role.
 - Add notification when a user ask to change his role.
 - The creator of the event receive a mail when a user click on "participer" or "se desinscrire".
 - Structure admin can manage "intervenant" and events from their structure.
 - Change duration of addflash (to 7 seconds).
 - If event is cancel a banner is display on the image and in the event list the event is disable and has shade of gray. 
 - "Intervenant" can Export an event in csv or html or pdf file.
 - Add Import of file to enter mutiples events.
 - Territory admin can manage (validate, delete, edit) events of their territory.
 - Make register and login in a modal.
 - User who has authorization can duplicate an event.
 - In event information if you are "intervenant" or admin you can click on participants number to see all the participants of the event.
 - The head of table can order the table.
 - An admin territory can click on the name of the structure to create/modify the structure.
 - Admin territory can import/export events of his territory.
 - "Intervenant" can delete his own event.
 - Admin territory can see all the user from his territory.
 - When the event is finished no one can update an event details (but can add a report (already done)).
 - Admin can view but not modify users under his responsibility level.
 - Admin structure can modify fields of their structure.
 - If the structure doesn't have image the logo of Alsace nature is display.
 - Admin structure can export all the events of their structure.
 - An "intervenant" can import/export events for his structure. 
 - Group mutiples event if there are in same area (on map).
 - Pagination in list of events page.
 - Captcha on connection.
 - SuperAdmin can add new filters.
 - Add a gallery of all the images for every structures.
 - Users can filter events in the back-office (for "intervenant" and all admin)
 - Confirm email when register.
 - Change password email.
 - "intervenant" can send email to all participant of the event


## Authors

- Typhaine DEMANGEON : [GitHub](https://github.com/typhained) | [LinkedIn](https://www.linkedin.com/in/typhaine-demangeon/)
- Kevin Fernandez : [GitHub](https://github.com/Sanota77) | [LinkedIn](https://www.linkedin.com/in/fernandez-kevin/)
- Samir ARRAHMANI : [GitHub](https://github.com/samir0067) | [LinkedIn](https://www.linkedin.com/in/arrahmani-samir/)
- Paul Strentz : [GitHub](https://github.com/Strentz-Paul) | [LinkedIn](https://www.linkedin.com/in/paul-strentz/)



