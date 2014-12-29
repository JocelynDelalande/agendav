<?php

/*
 * Copyright 2011-2012 Jorge López Pérez <jorge@adobo.org>
 *
 *  This file is part of AgenDAV.
 *
 *  AgenDAV is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  any later version.
 *
 *  AgenDAV is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with AgenDAV.  If not, see <http://www.gnu.org/licenses/>.
 */

use \AgenDAV\DateHelper;

class Js_generator extends MY_Controller
{

    // Special methods that do should not enforce authentication
    private $not_enforced = array(
            'siteconf',
            );
    private $username;

    private $preferences_repository;

    function __construct() {
        parent::__construct();
        $this->preferences_repository = $this->container['preferences_repository'];

        if (!in_array($this->uri->segment(2), $this->not_enforced) &&
                !$this->container['session']->isAuthenticated()) {
            $expire = $this->load->view('js_code/session_expired', '', true);
            echo $expire;
            die();
        }

        $this->username = $this->container['session']->get('username');

        $this->output->set_content_type('text/javascript');
    }

    function index() {
    }

    /**
     * Session refresh code
     */
    function session_refresh() {
        $session_options = $this->config->item('sessions');
        $seconds = $session_options['refresh'];
        $seconds++; // Give a margin of 1s to update
        $this->load->view('js_code/session_refresh',
                array('every' => $seconds));
    }

    /**
     * Keep session alive
     */
    function keepalive() {
        $this->output->set_output('');
    }

    /**
     * Sets application options
     */
    function siteconf()
    {
        $this->output->set_header(
                'Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        $this->output->set_header(
                'Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header(
                'Cache-Control: post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache'); 

        $options = array(
            'base_url' => base_url(),
            'base_app_url' => site_url() . '/',
            'agendav_version' => \AgenDAV\Version::V,
            'enable_calendar_sharing' => $this->config->item('enable_calendar_sharing'),
            'prefs_timeformat_option' => $this->config->item('default_time_format'),
            'prefs_timeformat' => DateHelper::getTimeFormatFor(
                'fullcalendar',
                $this->config->item('default_time_format')
            ),
            'prefs_timeformat_moment' => DateHelper::getTimeFormatFor(
                'moment',
                $this->config->item('default_time_format')
            ),
            'prefs_dateformat_option' => $this->config->item('default_date_format'),
            'prefs_dateformat' => DateHelper::getDateFormatFor(
                'datepicker',
                $this->config->item('default_date_format')
            ),
            'prefs_dateformat_moment' => DateHelper::getDateFormatFor(
                'moment',
                $this->config->item('default_date_format')
            ),
            'prefs_firstday' => $this->config->item('default_first_day'),
            'timepicker_base' => array(
                'timeFormat' => ($this->config->item('default_time_format') === '24')
                            ? 'H:i' : 'h:i A',
                'step' => 30,
                'maxTime' => ($this->config->item('default_time_format') === '24')
                            ? '23:30' : '11:30 PM',
            ),
            'csrf_cookie_name' => $this->config->item('cookie_prefix') 
                                . $this->config->item('csrf_cookie_name'),
            'csrf_token_name' => $this->config->item('csrf_token_name'),
            'calendar_colors' => $this->config->item('calendar_colors'),
        );

        $options['default_calendar_color'] = '#' . $options['calendar_colors'][0];

        $this->load->view('js_code/siteconf', array(
            'options' => $options,
        ));

    }


    function userprefs()
    {
        $this->output->set_header(
                'Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        $this->output->set_header(
                'Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header(
                'Cache-Control: post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache'); 

        $preferences = $this->preferences_repository->userPreferences($this->username);

        $data_preferences = [
            'default_calendar' => $preferences->get('default_calendar', null),
            'hidden_calendars' => $preferences->get('hidden_calendars', []),
            'timezone' => $preferences->get('timezone', $this->config->item('default_timezone')),
        ];

        $this->load->view('js_code/userprefs', array(
            'preferences' => $data_preferences,
        ));
    }

}
