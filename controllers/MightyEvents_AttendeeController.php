<?php
namespace Craft;

class MightyEvents_AttendeeController extends BaseController
{
	protected $allowAnonymous = true;

    public function actionSaveAttendee()
    {
    	$this->requirePostRequest();

    	foreach (craft()->request->getPost() as $key => $value) {
    		// Cleanse the data as much as possible
    		$encodedValue = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    		$encodedValue = htmlspecialchars($value, ENT_QUOTES);
    		$encodedValue = htmlentities($encodedValue);

    		$data[substr($key, 9)] = $encodedValue;
    	}

		$attendee = new MightyEvents_AttendeeModel();
		$attendee->name = $data['name'];
		$attendee->email = $data['email'];
		$attendee->seats = $data['seats'];

		// You need to declare a rules() method in your model for the
		// validate method to work.
		if ($attendee->validate()) {
			craft()->mightyEvents_attendees->SaveAttendee($attendee);
			craft()->userSession->setNotice('Your reservation was successful!');
		} else {
			craft()->userSession->setError('Something wasn\'t right about your reservation. Try submitting it again.');

			craft()->urlManager->setRouteVariables(array(
				'attendee' => $attendee
			));
		}

		$this->redirectToPostedUrl();
    }
}