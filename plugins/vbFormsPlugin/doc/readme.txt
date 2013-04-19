plugins required:
- sfShortcutsPlugin
- sfDeployPlugin
- sfShortcutsTestingPlugin
- sfUtils


This is form system made easy to use

1) How to create a form based on simple model
schema.yml

propel:
  user:
    id:
    name:
      type: varchar(255)
      required: true
    email:
      type: varchar(255)
      required: true
    password:
      type: varchar(255)
      required: true
    birth_date:
      type: date
    created_at:
    updated_at:

------
# generate the files using
symfony propel:build-all

File form/base/BaseUserForm.class.php
<?php

/**
 * User form base class.
 *
 * @package    form
 * @subpackage user
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 15484 2009-02-13 13:13:51Z fabien $
 */
class BaseUserForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInput(),
      'email'      => new sfWidgetFormInput(),
      'password'   => new sfWidgetFormInput(),
      'birth_date' => new sfWidgetFormDate(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 255)),
      'email'      => new sfValidatorString(array('max_length' => 255)),
      'password'   => new sfValidatorString(array('max_length' => 255)),
      'birth_date' => new sfValidatorDate(array('required' => false)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
      'updated_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }


}


#make a copy of the files to be used by the form system
symfony forms:copy-base

form/vbBase/vbBaseUserForm.class.php

<?php

/**
 * User form base class.
 *
 * @package    form
 * @subpackage user
 * @version    SVN: $Id: sfPropelFormGeneratedTemplate.php 15484 2009-02-13 13:13:51Z fabien $
 */
class vbBaseUserForm extends vbBaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInput(),
      'email'      => new sfWidgetFormInput(),
      'password'   => new sfWidgetFormInput(),
      'birth_date' => new sfWidgetFormDate(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'User', 'column' => 'id', 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 255)),
      'email'      => new sfValidatorString(array('max_length' => 255)),
      'password'   => new sfValidatorString(array('max_length' => 255)),
      'birth_date' => new sfValidatorDate(array('required' => false)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
      'updated_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }


}



----------



UserForm.class.php

<?php


/**
 * User form.
 *
 * @package    form
 * @subpackage user
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class UserForm extends vbBaseUserForm {
	public function configure() {
    $this->validatorSchema['email'] = new sfValidatorEmail();

    $this->widgetSchema['password'] = new sfWidgetFormInputPassword();

	}
}


----------
created form

<?php


/**
 * User form.
 *
 * @package    form
 * @subpackage user
 * @version    SVN: $Id: sfPropelFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class UserLoginForm extends UserForm {
	public function configure() {
		parent :: configure();
		$this->unsetAllFieldNamesExcept(array (
			'email',
			'password'
		));

	}

	public function postValidate() {
		if (!vbUserUtils :: isLoginValid($this->get_param('email'), $this->get_param('password'))) {
			$this->addErrorMessage('Invalid login');
			return false;
		}
		return true;
	}

}

------
Actions
public function executeLogin($request) {
		$this->form = new UserLoginForm();

		if ($this->is_post()) {
			$this->form = new UserLoginForm(UserPeer :: retrieveByEmail($this->get_param('email', null, 'user')));
			$this->form->bind($request->getParameter('user'));
			if ($this->form->isValid()) {
				vbUserUtils :: loginByUser($this->form->getObject());
				$this->redirect('@homepage');
			}
		}
	}

-------
Testing
Do not forget to turn off the CSRF token if you decide to use another bootstrap

<?php

include(dirname(__FILE__).'/../../../plugins/sfShortcutsTestingPlugin/lib/bootstrap/functional.php');


$b = new sfTestBrowser();

register_tests (array (
    'login' => 'check if user is authenticated',
    'logout' => 'check if user is logged out',
));

// create fake user
vbUserUtils::register('Hassen Ben Tanfous FAKE', 'hassenben@gmail.com', sha1('asdasd'), '06/01/1987', false);

// test login
$t = new lime_test(2, new lime_output_color());
$b->get('/user/login');
$b->post('user/login', array (
    'user' => array (
        'email' => 'hassenben@gmail.com',
        'password' => 'asdasd',
    )
));

//echo $b->getResponse()->getContent();

$t->is(sfUser()->isAuthenticated(), true);
$user = UserPeer::retrieveByPk (sfUser()->getAttribute(myUser::USER_ID));
$t->is($user->getEmail(), 'hassenben@gmail.com');


check_test('login');

// test logout
$t = new lime_test(1, new lime_output_color());
$t->diag ('testing logout');
$b->get('/user/logout');

$t->is(sfUser()->isAuthenticated(), false);

check_test('logout');

$u = UserPeer::retrieveByEmail ('hassenben@gmail.com');
$u->delete();


stop_tests();

