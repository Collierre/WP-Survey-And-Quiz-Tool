<?php
require_once WPSQT_DIR.'lib/Wpsqt/Tokens.php';

/**
 * Handle sending the notification emails. 
 * 
 * @author Iain Cambridge
 * @copyright Fubra Limited 2010-2011, all rights reserved.
 * @license http://www.gnu.org/licenses/gpl.html GPL v3 
 * @package WPSQT
 */
class Wpsqt_Mail {
	
	/**
	 * Sends the notification email to respondent
	 *
	 * @since 2.11.1
	 */
	private function _sendRespondentMail($address) {

		$quizName = $_SESSION['wpsqt']['current_id'];
		
		$objTokens = Wpsqt_Tokens::getTokenObject();
		$objTokens->setDefaultValues();
		
		$emailMessage = $_SESSION['wpsqt'][$quizName]['details']['email_template'];
		
		if ( empty($emailMessage) ){
			$emailMessage = get_option('wpsqt_email_template');
		}
		
		if ( empty($emailMessage) ){
			
			$emailMessage  = 'There is a new result to view'.PHP_EOL.PHP_EOL;
			$emailMessage .= 'Person Name : %USER_NAME%'.PHP_EOL;
			$emailMessage .= 'Result can be viewed at %RESULT_VIEW_URL%'.PHP_EOL;
			
		}
		
		$emailMessage = $objTokens->doReplacement($emailMessage);
									
		$quizDetails = $_SESSION['wpsqt'][$quizName]['details'];
		$emailTemplate = (empty($quizDetails['email_template'])) ? get_option('wpsqt_email_template'):$quizDetails['email_template'];
		$fromEmail = ( get_option('wpsqt_from_email') ) ?  get_option('wpsqt_from_email') : get_option('admin_email');

		$type = ucfirst($_SESSION['wpsqt'][$quizName]['details']['type']);
		$blogname = get_bloginfo('name');
		$emailSubject = $type.' Notification From '.$blogname;
		$headers = 'From: '.$blogname.' <'.$fromEmail.'>' . "\r\n";

		wp_mail($address,$emailSubject,$emailMessage,$headers);
	}
	
	public static function sendRespondentResults($address) {
		$blogname = get_bloginfo('name');
		$emailSubject = $type.' Notification From '.$blogname;
		$headers = 'From: '.$blogname.' <'.$fromEmail.'>' . "\r\n";
		$headers .= "Content-Type: text/html; charset='UTF-8'\r\n";
		
		$quizName = $_SESSION['wpsqt']['current_id'];
		
		$emailMessage = "<html>
		<head>
		</head>
		<body>
		<h1 style='font-size:22px'>The Policy from Science Project Toolkit for the Appraisal of Reviews of Toxicological Research</h1>";
		if(array_key_exists("enter the citation information for the review you are appraising", $_SESSION['wpsqt'][$quizName]['person'])) {
			$emailMessage .= "<h2>" . stripslashes($_SESSION['wpsqt'][$quizName]['person']['enter the citation information for the review you are appraising']) . "</h2>";
		}
		$emailMessage .= "<h2>Thank you for completing the survey. The answers you gave are below.</h2>";
		
		$answerNames = ['Yes', 'No', 'Unclear'];
		$answerColours = ['#43a117', '#c91616', '#e19d17'];
		

		foreach ($_SESSION['wpsqt'][$quizName]['sections'] as $sectionKey => $section ) {
			$emailMessage .= "<table id='survey-answers' style='border-spacing:0;border-collapse:collapse'>
				<tr>
					<th style='border: 1px solid #000; padding: 5px'>Question</th>
					<th style='border: 1px solid #000; padding: 5px'>Judgement</th>
					<th style='border: 1px solid #000; padding: 5px'>Comment</th>
				</tr>";
				foreach ($section['questions'] as $questionKey => $questionArray) {
			
					$questionId = $questionArray['id'];
					$emailMessage .= "<tr>	
					<td style='border: 1px solid #000; padding: 5px; color: #000'>" . stripslashes($questionArray['name']) . "</td>";
					$givenAnswerName = '';
					$givenAnswerColour = '';
					if(isset($section['answers'][$questionId]['given'])) {
						$givenAnswer = $section['answers'][$questionId]['given'][0];
						$givenAnswerName = $answerNames[$givenAnswer];
						$givenAnswerColour = $answerColours[$givenAnswer];
					}
				
					$emailMessage .= "<td width=80 style='border: 1px solid #000; padding: 5px; background:" . $givenAnswerColour . "'><div class='table-answer' style='font-weight:bold;color:#fff;text-align:center;'>" . $givenAnswerName . "</div></td>
					<td style='border: 1px solid #000; padding: 5px'>" . $section['comment'][$questionKey][0] . "</td>
				
				</tr>";
			}
			$emailMessage .= "</table>";
		}
		
		$emailMessage .= "</body></html>";

		wp_mail($address,$emailSubject,$emailMessage,$headers);
	}

	/**
	 * Sends the notification mail.
	 * 
	 * @since 2.0
	 */
	public static function sendMail(){
				
		global $wpdb;
				
		$quizName = $_SESSION['wpsqt']['current_id'];
		
		$objTokens = Wpsqt_Tokens::getTokenObject();
		$objTokens->setDefaultValues();
		
		$emailMessage = $_SESSION['wpsqt'][$quizName]['details']['email_template'];
		
		if ( empty($emailMessage) ){
			$emailMessage = get_option('wpsqt_email_template');
		}
		
		if ( empty($emailMessage) ){
			
			$emailMessage  = 'There is a new result to view'.PHP_EOL.PHP_EOL;
			$emailMessage .= 'Person Name : %USER_NAME%'.PHP_EOL;
			$emailMessage .= 'Result can be viewed at %RESULT_VIEW_URL%'.PHP_EOL;
			
		}
		
		$emailMessage = $objTokens->doReplacement($emailMessage);
									
		$quizDetails = $_SESSION['wpsqt'][$quizName]['details'];
		$emailTemplate = (empty($quizDetails['email_template'])) ? get_option('wpsqt_email_template'):$quizDetails['email_template'];
		$fromEmail = ( get_option('wpsqt_from_email') ) ?  get_option('wpsqt_from_email') : get_option('admin_email');
		
		$role = get_option('wpsqt_email_role');
		$personName = ( isset($_SESSION['wpsqt'][$quizName]['person']['user_name']) && !empty($_SESSION['wpsqt'][$quizName]['person']['user_name']) ) ? $_SESSION['wpsqt'][$quizName]['person']['user_name'] : 'Anonymous';
		
		if ( !empty($role) && $role != 'none' ){
			$this_role = "'[[:<:]]".$role."[[:>:]]'";
	  		$query = "SELECT * 
	  				  FROM ".$wpdb->users." 
	  				  WHERE ID = ANY 
	  				  	(
	  				  		SELECT user_id 
	  				  		FROM ".$wpdb->usermeta." 
	  				  		WHERE meta_key = 'wp_capabilities' 
	  				  			AND meta_value RLIKE ".$this_role."
						) 
	  				  ORDER BY user_nicename ASC LIMIT 10000";
	  		$users = $wpdb->get_results($query,ARRAY_A);
	  		$emailList = array();
	  		foreach($users as $user){
	  			$emailList[] = $user['user_email'];
	  		}
		}
		
		if ( isset($_SESSION['wpsqt'][$quizName]['details']['send_user']) 
		  && $_SESSION['wpsqt'][$quizName]['details']['send_user'] == "yes" && isset($_SESSION['wpsqt'][$quizName]['person']['email']) ) {
			self::_sendRespondentMail($_SESSION['wpsqt'][$quizName]['person']['email']);
		}
		
		if (isset($_SESSION['wpsqt'][$quizName]['person']['email'])) {
			self::sendRespondentResults($_SESSION['wpsqt'][$quizName]['person']['email']);
		}
		
		if ( $_SESSION['wpsqt'][$quizName]['details']['notification_type'] == 'instant' ){
			$emailTrue = true;
		} elseif ( $_SESSION['wpsqt'][$quizName]['details']['notification_type'] == 'instant-100' 
					&& $percentRight == 100 ) {
			$emailTrue = true;	
		} elseif ( $_SESSION['wpsqt'][$quizName]['details']['notification_type'] == 'instant-75' 
					 && $percentRight > 75 ){
			$emailTrue = true;
		} elseif ( $_SESSION['wpsqt'][$quizName]['details']['notification_type'] == 'instant-50'  
					&& $percentRight > 50 ){
			$emailTrue = true;
		}
		
		if ( !isset($emailList) || empty($emailList) || $emailTrue === TRUE ){
			$emailAddress = get_option('wpsqt_contact_email');
			if ( !empty($_SESSION['wpsqt'][$quizName]['details']['notification_email'])  ){
				$emailList[] = $_SESSION['wpsqt'][$quizName]['details']['notification_email'];
			}
		}

		$type = ucfirst($_SESSION['wpsqt'][$quizName]['details']['type']);
		$blogname = get_bloginfo('name');
		$emailSubject = $type.' Notification From '.$blogname;
		$headers = 'From: '.$blogname.' <'.$fromEmail.'>' . "\r\n";
		if (isset($emailList) && is_array($emailList)) {
			foreach( $emailList  as $emailAddress ){
				wp_mail($emailAddress,$emailSubject,$emailMessage,$headers);
			}	
		}
	}
	
}
