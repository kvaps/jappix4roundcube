<?php
/**
 * jappix4roundcube
 *
 * Plugin to integrate Mini jappix in roundcube
 * Mini jappix : https://mini.jappix.com/get
 *
 * @version 1.0
 * @author RD
 * @url https://code.google.com/p/jappix4roundcube/
 */
 
class jappix4roundcube extends rcube_plugin {
  public $task = 'mail|settings|addressbook|jappix';
  
  function init() {
	$rcmail = rcmail::get_instance();

	$this->load_config();
	$this->add_texts('localization/', false);
	$this->require_plugin('jqueryui');
	
	
	if ($rcmail->config->get('jappix_task') && $rcmail->config->get('jabber_username') !=''){
		$this->register_task('jappix');
		$this->register_action('index', array($this, 'action'));
		$this->register_action('redirect', array($this, 'redirect'));
		$this->add_button(array(
	        'command' => 'jappix',
	        'class'	=> 'button-jappix4roundcube',
	        'classsel' => 'button-jappix4roundcube button-selected',                                                                                                               
	        'innerclass' => 'button-inner',                                                                                                                                
	        'label'	=> 'jappix4roundcube.task',
            ), 'taskbar');
	}
  
	if ($rcmail->config->get('jappix_embedded')){
		$this->include_script('jappix/server/get.php?l=en&t=js&g=mini.xml');
	} else {
		$this->include_script($rcmail->config->get('jappix_url').'/server/get.php?l=en&t=js&g=mini.xml');
	}
	$this->include_stylesheet('jappix4roundcube.css');
	
	$rcmail->output->set_env('jabber_username', $rcmail->config->get('jabber_username'));
	$rcmail->output->set_env('jabber_domain', $rcmail->config->get('jabber_domain'));
	$rcmail->output->set_env('jabber_password', $rcmail->config->get('jabber_password'));
	
	if ($rcmail->task == 'settings') {
		$this->add_hook('preferences_sections_list', array($this, 'preferences_section'));
		$this->add_hook('preferences_list', array($this, 'preferences_list'));
		$this->add_hook('preferences_save', array($this, 'preferences_save'));
	}
	
	if ($rcmail->task != 'jappix' && $rcmail->config->get('jabber_username') !='') {
		if ($rcmail->action == '' || $rcmail->action == 'compose' || ($rcmail->action == 'show' && $rcmail->task == 'mail')){
			$this->include_script('jappix.js');
		}
	}
	
  }

  function preferences_section($args) {
    $this->add_texts('localization/', false);
		$args['list']['jabber'] = array(
			'id'      => 'jabber',
			'section' => Q($this->gettext('jappixSection'))
		);
	return($args);
  }

  function preferences_list($args) {
    if ($args['section'] == 'jabber') {
		$rcmail = rcmail::get_instance();
		$this->add_texts('localization');
		$this->load_config();

		$jabber_username = $rcmail->config->get('jabber_username');
		$input = new html_inputfield(array('name' => '_jabber_username', 'id' => $field_id, 'size' => 25));
		$args['blocks']['jabber']['options']['jabber_username'] = array(
			'title' => html::label($field_id, Q($this->gettext('jappixUsername'))),
			'content' => $input->show($jabber_username),
		);
		
		$jabber_password = $rcmail->config->get('jabber_password');
		$input = new html_passwordfield(array('name' => '_jabber_password', 'id' => $field_id, 'size' => 25));
		$args['blocks']['jabber']['options']['jabber_password'] = array(
			'title' => html::label($field_id, Q($this->gettext('jappixPassword'))),
			'content' => $input->show($jabber_password),
		);
		
		$jabber_domain = $rcmail->config->get('jabber_domain');
		$input = new html_inputfield(array('name' => '_jabber_domain', 'id' => $field_id, 'size' => 25));
		$args['blocks']['jabber']['options']['jabber_domain'] = array(
			'title' => html::label($field_id, Q($this->gettext('jappixDomain'))),
			'content' => $input->show($jabber_domain),
		);
		
		if ($rcmail->config->get('jappix_embedded')){
			$args['blocks']['jabber']['options']['jabber_manager'] = array(
				'title' => html::label($field_id, Q($this->gettext('manager'))),
				'content' => '<a target=\'_blank\' href=\'plugins/jappix4roundcube/jappix/?m=manager\'>'.Q($this->gettext('manager')).'</a>',
			);
		} else {
			$args['blocks']['jabber']['options']['jabber_manager'] = array(
				'title' => html::label($field_id, Q($this->gettext('manager'))),
				'content' => '<a target=\'_blank\' href=\''.$rcmail->config->get('jappix_url').'/?m=manager\'>'.Q($this->gettext('manager')).'</a>',
			);
		}
    }
    return $args;
  }

  function preferences_save($args) {
	if ($args['section'] == 'jabber') {
		$args['prefs']['jabber_username'] = get_input_value('_jabber_username', RCUBE_INPUT_POST);
		$args['prefs']['jabber_password'] = get_input_value('_jabber_password', RCUBE_INPUT_POST);
		$args['prefs']['jabber_domain'] = get_input_value('_jabber_domain', RCUBE_INPUT_POST);
		
		$rcmail = rcmail::get_instance();
		$rcmail->output->set_env('jabber_username', get_input_value('_jabber_username', RCUBE_INPUT_POST));
		$rcmail->output->set_env('jabber_domain', get_input_value('jabber_domain', RCUBE_INPUT_POST));
		$rcmail->output->set_env('jabber_password', get_input_value('_jabber_password', RCUBE_INPUT_POST));
	}
    return $args;
  }
	
	function action()
    {
        $rcmail = rcmail::get_instance();

        $rcmail->output->add_handlers(array('jappix4roundcubeframe' => array($this, 'frame')));
        $rcmail->output->set_pagetitle($this->gettext('title'));
        $rcmail->output->send('jappix4roundcube.jappix4roundcube');
    }

    function frame()
    {
        $rcmail = rcmail::get_instance();

        $this->load_config();

		if ($rcmail->config->get('jappix_embedded')){
			$src  = 'plugins/jappix4roundcube/jappix/index.php';
		} else {
			$src  = $rcmail->config->get('jappix_url').'/';
		}
		
        $user = $rcmail->config->get('jabber_username');
        $pass = $rcmail->config->get('jabber_password');
		$domaine = $rcmail->config->get('jabber_domain');

        $src = $src.'?u='.$user.'@'.$domaine.'&h=1&q='.$pass;
		
//		$this->include_script('jappix/js/httpauth.js');
//		$this->include_script('jappixFullLogin.js');
	    
        if ($rcmail->config->get('jappix_iframe')){
            return '<iframe id="jappix4roundcubeframe" width="100%" height="100%" frameborder="0" src="' . $src. '"></iframe>';
        } else {
        	return '<script type="text/javascript"> window.location.href ="' . $src. '";</script>';
        } 
    }
}

?>
