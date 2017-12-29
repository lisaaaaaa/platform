<?php

namespace CASHMusic\Admin;

use CASHMusic\Core\CASHSystem as CASHSystem;
use CASHMusic\Core\CASHRequest as CASHRequest;
use ArrayIterator;
use CASHMusic\Admin\AdminHelper;


$admin_helper = new AdminHelper($admin_request, $cash_admin);

// parsing posted data:
if (isset($_POST['doeventadd'])) {
	// do the actual list add stuffs...
	$effective_user = $cash_admin->effective_user_id;
	$eventispublished = 0;
	$eventiscancelled = 0;
	if (isset($_POST['event_ispublished'])) { $eventispublished = 1; }
	if (isset($_POST['event_iscancelled'])) { $eventiscancelled = 1; }

	$add_response = $admin_request->request('calendar')
	                        ->action('addevent')
	                        ->with([
                                'date' => strtotime($_POST['event_date']),
                                'venue_id' => $_POST['event_venue'],
                                'comment' => $_POST['event_comment'],
                                'purchase_url' => $_POST['event_purchase_url'],
                                'published' => $eventispublished,
                                'cancelled' => $eventiscancelled,
                                'user_id' => $effective_user
							])->get();

	if ($add_response['payload']) {
		$admin_helper->formSuccess('Success. Event added.','/calendar/');
	} else {
		$admin_helper->formFailure('Error. Something just didn\'t work right.','/calendar/events/add/');
	}
}

$cash_admin->page_data['venue_options'] = $admin_helper->echoFormOptions('venues',0,false,true);
$cash_admin->page_data['form_state_action'] = 'doeventadd';
$cash_admin->page_data['event_button_text'] = 'Add the event';

$cash_admin->setPageContentTemplate('calendar_events_details');
?>
