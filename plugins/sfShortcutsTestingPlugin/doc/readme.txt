**Description:
A couple of tasks to make testing faster & easier.
A couple of functions and shortcuts for testing

**Plugins required:
sfShortcutsPlugin

-----------------------------
You have to create a database for the tests

in databases.yml

test:
  propel:
    class:          sfPropelDatabase
    param:
      dsn:          mysql://root:password@localhost/cms_revolution_test


--------
In your functional test
test/functional/frontend/userActionsTest.php

<?php

include(dirname(__FILE__).'/../../../plugins/sfShortcutsTestingPlugin/lib/bootstrap/functional.php');

$b = new sfTestBrowser();

register_tests (array (
    'login' => 'check if user is authenticated',
    'logout' => 'check if user is logged out',
    'register' => 'check user values and invitation code',
    'lostPassword' => 'check lost password process',
    'updateOnlineStatus' => 'check user online status'
));

// test login
$t = new lime_test(2, new lime_output_color());
$b->get('/user/login');
$b->post('user/postLogin', array (
    'email' => 'hassenben@gmail.com',
    'password' => 'asdasd'
));

$t->is(sfUser()->isAuthenticated(), true);
$t->is(sfUser()->getAttribute(myUser::USER_ID), 2);

check_test('login');

// test logout
$t = new lime_test(1, new lime_output_color());
$t->diag ('testing logout');
$b->get('/user/logout');

$t->is(sfUser()->isAuthenticated(), false);

check_test('logout');

// test Register
$i = InvitationCodePeer :: retrieveByCode('123');
$i->setUserId(null);
$i->save();

$u = UserPeer::retrieveByEmail ('hassenbentanfous@gmail.com');
$u->delete();

$t = new lime_test(11, new lime_output_color());
$t->diag('testing register');
$b->get('user/register');
$b->post('user/postRegister', array (
    'first_name' => 'hassen',
    'last_name' => 'ben tanfous',
    'email' => 'hassenbentanfous@gmail.com',
    'password' => 'asdasd',
    'invitation_code' => '123',
    'sex' => '1',
    'country_id' => '43', //CAN
    'birth_date' => array ('year' => 1987, 'month' => 6, 'day' => 1),

    'terms' => '1',
));
$u = UserPeer::retrieveByEmail ('hassenbentanfous@gmail.com');
$i = InvitationCodePeer :: retrieveByCode('123');

$t->isnt($u, null);
$t->is($i->getUserId(), $u->getId());
$t->is(sfUser()->getAttribute(myUser::USER_ID), $u->getId());
$t->is(sfUser()->isAuthenticated(), true);
$t->is($u->getPassword(), 'asdasd');
$t->is($u->getFirstName(), 'hassen');
$t->is($u->getLastName(), 'ben tanfous');
$t->is($u->getFormattedTitle(), 'Hassen Ben Tanfous');
$t->is($u->getBirthDate(), '1987-06-01');
$t->is($u->getCountryId(), 43);
$t->is($u->getSex(), 1);

$i->setUserId(null);
$i->save();
$u->delete();
vbTestUtils::logout($b);

check_test('register');

// test LostPassword
//$t = new lime_test(0, new lime_output_color());
//$t->fail('test');
//$b->get('/user/lostPassword');


// test UpdateOnlineStatus
$t = new lime_test(2, new lime_output_color());
$t->diag ('testing updateOnlineStatus');

vbTestUtils::login($b);
$b->get('/user/updateOnlineStatus');
$u = vbTestUtils::getUser();
$t->is($u->isOnline(), true);
vbTestUtils::logout($b);
$u = vbTestUtils::getUser();
$t->is($u->isOnline(), false);

check_test('updateOnlineStatus');

stop_tests();
